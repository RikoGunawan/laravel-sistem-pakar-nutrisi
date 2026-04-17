<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Makanan;
use App\Models\MetodePengolahan;
use App\Models\AnalisisNutrisi;
use App\Models\AnalisisMetode;
use App\Models\TracePenalaran;
use App\Models\Rekomendasi;
use App\Services\RuleEngineService;
use Illuminate\Support\Facades\DB;

class AnalisisController extends Controller
{
    // ====================== SHOW FORM ANALISIS ======================
    public function index()
    {
        $makananList = Makanan::orderBy('name', 'asc')->get();
        $metodeList  = MetodePengolahan::all();

        return view('analisis.index', compact('makananList', 'metodeList'));
    }

    // ====================== PROSES ANALISIS (FORWARD CHAINING) ======================
    public function analyze(Request $request)
    {
        $request->validate([
            'makanan_id'   => 'required|exists:makanan,id',
            'metode_ids'   => 'required|array|min:1',
            'metode_ids.*' => 'exists:metode_pengolahan,id',
        ]);

        $makanan = Makanan::findOrFail($request->makanan_id);

        // Validasi: Cek apakah metode cocok dengan makanan
        $metodeTidakCocok = $this->validasiMetodeCocok($makanan, $request->metode_ids);

        if (!empty($metodeTidakCocok)) {
            return back()->with('error', sprintf(
                "Metode %s tidak cocok untuk %s. %s",
                implode(', ', $metodeTidakCocok),
                $makanan->name,
                $makanan->catatan_pengolahan ?: "Silakan pilih metode lain."
            ));
        }

        try {
            DB::beginTransaction();

            $nutrisiMentah = $makanan->getNutrisiMentah();

            // Buat record analisis utama
            $analisis = AnalisisNutrisi::create([
                'makanan_id'     => $makanan->id,
                'nutrisi_mentah' => $nutrisiMentah,
                'session_id'     => session()->getId(),
                'ip_address'     => $request->ip(),
            ]);

            $hasilKomparasi = [];
            $stepOrder = 1;
            $ruleEngine = new RuleEngineService();

            // Proses setiap metode pengolahan
            foreach ($request->metode_ids as $metodeId) {
                $metode = MetodePengolahan::findOrFail($metodeId);

                // === BAGIAN BARU: Gunakan Rule Engine ===
                $result = $ruleEngine->applyRules($makanan, $metodeId);

                if (empty($result['rules_diterapkan'])) {
                    // Jika tidak ada rule sama sekali untuk metode ini
                    continue;
                }

                $nutrisiHasil = $result['nutrisi_akhir'];

                // Simpan detail analisis metode (ambil rule pertama yang diterapkan sebagai representasi utama)
                $ruleUtama = $result['rules_diterapkan'][0];

                // Simpan ke AnalisisMetode
                AnalisisMetode::create([
                    'analisis_nutrisi_id'  => $analisis->id,
                    'metode_pengolahan_id' => $metodeId,
                    'rule_id'              => $ruleUtama['rule_id'] ?? null,
                    'nutrisi_hasil'        => $nutrisiHasil,
                    'perubahan_persen'     => $ruleUtama['perubahan'] ?? [],
                ]);

                // === TRACE PENALARAN FORWARD CHAINING (Per Rule) ===
                $currentNutrisi = $nutrisiMentah;   // reset ke nutrisi mentah untuk setiap metode

                foreach ($result['rules_diterapkan'] as $applied) {
                    TracePenalaran::create([
                        'analisis_nutrisi_id' => $analisis->id,
                        'fakta_awal'  => $currentNutrisi === $nutrisiMentah 
                            ? "Fakta Awal → Makanan: {$makanan->name} | Kategori: " . ($makanan->kategori ?? '-') 
                            : "Fakta sebelum rule ini → " . json_encode($currentNutrisi, JSON_PRETTY_PRINT),
                        
                        'rule_used'   => $applied['kode_rule'],
                        'proses'      => "Menerapkan rule {$applied['kode_rule']} (Tipe: {$applied['tipe_rule']}) pada metode {$metode->name}" .
                                         ($applied['penjelasan'] ? " | Alasan: {$applied['penjelasan']}" : ''),
                        
                        'fakta_baru'  => "Hasil setelah rule {$applied['kode_rule']}: " . json_encode($nutrisiHasil, JSON_PRETTY_PRINT),
                        'step_order'  => $stepOrder++,
                    ]);

                    // Update current nutrisi untuk rule berikutnya (chaining)
                    $currentNutrisi = $nutrisiHasil;

                }

                // Kumpulkan hasil komparasi
                $hasilKomparasi[$metode->name] = [
                    'metode_id'        => $metodeId,
                    'nutrisi_hasil'    => $nutrisiHasil,
                    'perubahan_persen' => $ruleUtama['perubahan'] ?? [],
                    'penjelasan'       => $ruleUtama['penjelasan'] ?? 'Rule umum diterapkan',
                    'rules_diterapkan' => $result['rules_diterapkan'],
                ];
            }

            if (empty($hasilKomparasi)) {
                throw new \Exception('Tidak ada hasil analisis yang berhasil diproses.');
            }

            // Tambahkan informasi tambahan (total vitamin + score) SEKALI SAJA
            $hasilKomparasi = $this->enrichData($hasilKomparasi);
            $summary       = $this->generateSummary($hasilKomparasi);
            $metodeTerbaik = $this->findBestMethod($hasilKomparasi);

            // Update record analisis
            $analisis->update([
                'hasil_komparasi' => $hasilKomparasi,
                'summary'         => $summary,
                'metode_terbaik'  => $metodeTerbaik,
            ]);

            // Generate rekomendasi
            $this->generateRecommendations($analisis, $hasilKomparasi);

            DB::commit();

            return redirect()->route('analisis.result', $analisis->id)
                ->with('success', 'Analisis berhasil dilakukan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ====================== HALAMAN HASIL ======================
    public function result($id)
    {
        $analisis = AnalisisNutrisi::with([
            'makanan',
            'analisisMetode.metodePengolahan',
            'analisisMetode.rule',
            'tracePenalaran',
            'rekomendasi'
        ])->findOrFail($id);

        return view('analisis.result', compact('analisis'));
    }

    // ====================== TRACE PENALARAN ======================
    public function trace($id)
    {
        $analisis = AnalisisNutrisi::with(['tracePenalaran' => function ($query) {
            $query->ordered();
        }])->findOrFail($id);

        return view('analisis.trace', compact('analisis'));
    }

    // ====================== HELPER METHODS ======================

    private function enrichData(array $hasilKomparasi): array
    {
        foreach ($hasilKomparasi as $metode => &$data) {
            $nutrisi = $data['nutrisi_hasil'];

            $data['total_vitamin'] = $this->hitungTotalVitamin($nutrisi);
            $data['kalori']        = $nutrisi['kalori'] ?? 0;
            $data['kalori_change'] = $data['perubahan_persen']['kalori'] ?? 0;
            $data['lemak_change']  = $data['perubahan_persen']['lemak'] ?? 0;

            // Score = Total vitamin - (kenaikan kalori & lemak)
            $data['score'] = $data['total_vitamin']
                - abs($data['kalori_change'])
                - abs($data['lemak_change']);
        }

        return $hasilKomparasi;
    }

    private function hitungTotalVitamin(array $nutrisi): int|float
    {
        $vitamins = [
            $nutrisi['vitamin_a']    ?? 0,
            $nutrisi['beta_karoten'] ?? 0,
            $nutrisi['vitamin_b1']   ?? 0,
            $nutrisi['vitamin_b2']   ?? 0,
            $nutrisi['vitamin_b3']   ?? 0,
            $nutrisi['vitamin_b5']   ?? 0,
            $nutrisi['vitamin_b6']   ?? 0,
            $nutrisi['vitamin_b12']  ?? 0,
            $nutrisi['vitamin_c']    ?? 0,
        ];

        return array_sum(array_filter($vitamins, fn($v) => $v !== null && $v !== 0));
    }

    private function generateSummary(array $hasilKomparasi): string
    {
        $summary = [
            'total_metode_dibandingkan' => count($hasilKomparasi),
            'perubahan_tertinggi'       => [],
            'perubahan_terendah'        => [],
        ];

        foreach (['protein', 'lemak', 'karbohidrat', 'kalori', 'vitamin_c'] as $nutrisi) {
            $changes = [];
            foreach ($hasilKomparasi as $metode => $data) {
                $changes[$metode] = $data['perubahan_persen'][$nutrisi] ?? 0;
            }

            if (!empty($changes)) {
                arsort($changes);
                $summary['perubahan_tertinggi'][$nutrisi] = array_key_first($changes);

                asort($changes);
                $summary['perubahan_terendah'][$nutrisi] = array_key_first($changes);
            }
        }

        return json_encode($summary);
    }

    private function findBestMethod(array $hasilKomparasi): ?int
    {
        if (empty($hasilKomparasi)) {
            return null;
        }

        $bestMetode = null;
        $maxScore   = -INF;

        foreach ($hasilKomparasi as $data) {
            if (isset($data['score']) && $data['score'] > $maxScore) {
                $maxScore   = $data['score'];
                $bestMetode = $data['metode_id'];
            }
        }

        return $bestMetode;
    }

    private function generateRecommendations(AnalisisNutrisi $analisis, array $hasilKomparasi): void
    {
        if (empty($hasilKomparasi)) {
            return;
        }

        $recommendations = [];

        // 1. Diet rendah kalori
        $metodeKaloriRendah = null;
        $minKalori = PHP_FLOAT_MAX;

        foreach ($hasilKomparasi as $metode => $data) {
            $kalori = $data['kalori'] ?? PHP_FLOAT_MAX;
            if ($kalori < $minKalori) {
                $minKalori = $kalori;
                $metodeKaloriRendah = $metode;
            }
        }

        if ($metodeKaloriRendah !== null) {
            $kalori = $hasilKomparasi[$metodeKaloriRendah]['kalori'];
            $recommendations[] = [
                'jenis'              => 'diet_rendah_kalori',
                'deskripsi'          => 'Rekomendasi untuk diet rendah kalori',
                'metode_rekomendasi' => $metodeKaloriRendah,
                'alasan'             => "Metode {$metodeKaloriRendah} menghasilkan kalori terendah (" .
                    number_format($kalori, 2) . " kkal).",
            ];
        }

        // 2. Maksimalkan vitamin
        $metodeVitaminTerbaik = null;
        $maxVitamin = -PHP_FLOAT_MAX;

        foreach ($hasilKomparasi as $metode => $data) {
            $totalVitamin = $data['total_vitamin'] ?? 0;
            if ($totalVitamin > $maxVitamin) {
                $maxVitamin = $totalVitamin;
                $metodeVitaminTerbaik = $metode;
            }
        }

        if ($metodeVitaminTerbaik !== null) {
            $recommendations[] = [
                'jenis'              => 'maksimal_vitamin',
                'deskripsi'          => 'Rekomendasi untuk mempertahankan vitamin',
                'metode_rekomendasi' => $metodeVitaminTerbaik,
                'alasan'             => "Metode {$metodeVitaminTerbaik} memiliki total vitamin tertinggi.",
            ];
        }

        // 3. Metode yang sebaiknya dihindari
        $metodeHindari = null;
        $maxKaloriIncrease = -PHP_FLOAT_MAX;

        foreach ($hasilKomparasi as $metode => $data) {
            $kaloriChange = $data['kalori_change'] ?? 0;
            if ($kaloriChange > $maxKaloriIncrease) {
                $maxKaloriIncrease = $kaloriChange;
                $metodeHindari = $metode;
            }
        }

        if ($metodeHindari !== null && $maxKaloriIncrease > 0) {
            $recommendations[] = [
                'jenis'              => 'hindari',
                'deskripsi'          => 'Metode yang sebaiknya dihindari untuk diet',
                'metode_rekomendasi' => $metodeHindari,
                'alasan'             => "Metode {$metodeHindari} meningkatkan kalori hingga {$maxKaloriIncrease}%.",
            ];
        }

        // Simpan semua rekomendasi ke database
        foreach ($recommendations as $rec) {
            Rekomendasi::create([
                'analisis_nutrisi_id' => $analisis->id,
                'jenis'               => $rec['jenis'],
                'deskripsi'           => $rec['deskripsi'],
                'metode_rekomendasi'  => $rec['metode_rekomendasi'],
                'alasan'              => $rec['alasan'],
            ]);
        }
    }

    // ====================== VALIDASI HELPER ======================
    private function validasiMetodeCocok(Makanan $makanan, array $metodeIds): array
    {
        $tidakCocok = [];
        foreach ($metodeIds as $id) {
            if (!$makanan->isMetodeCocok($id)) {
                $metode = MetodePengolahan::find($id);
                $tidakCocok[] = $metode?->name ?? "ID: {$id}";
            }
        }
        return $tidakCocok;
    }
}
