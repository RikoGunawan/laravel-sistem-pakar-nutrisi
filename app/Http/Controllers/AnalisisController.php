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

            $summary       = $this->generateSummary($hasilKomparasi);

            // Update record analisis
            $analisis->update([
                'hasil_komparasi' => $hasilKomparasi,
                'summary'         => $summary,
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

    // ====================== KIRIM DATA KE HALAMAN RESULT ======================
    public function result($id)
    {
        $analisis = AnalisisNutrisi::with([
            'makanan',
            'analisisMetode.metodePengolahan',
            'analisisMetode.rule',
            'tracePenalaran',
            'rekomendasi'
        ])->findOrFail($id);

        $summary = json_decode($analisis->summary, true);

        // Bangun hasilKomparasi dari relasi
        $hasilKomparasi = [];
        foreach ($analisis->analisisMetode as $am) {
            $nama = $am->metodePengolahan->name;
            $hasilKomparasi[$nama] = [
                'metode_id'        => $am->metode_pengolahan_id,
                'nutrisi_hasil'    => $am->nutrisi_hasil,
                'perubahan_persen' => $am->perubahan_persen ?? [],
            ];
        }
        $ringkasan = $this->hitungRingkasan($hasilKomparasi);
        // Kumpulkan SEMUA rules_diterapkan dari semua metode (supaya footnote bisa ambil semua sumber)
        $rulesDiterapkanSemua = collect();

        foreach ($analisis->analisisMetode as $am) {
            if ($am->rule) {
                $rulesDiterapkanSemua->push([
                    'rule'          => $am->rule,
                    'kode_rule'     => $am->rule->kode_rule,
                    'penjelasan'    => $am->rule->penjelasan,
                    'perubahan'     => $am->perubahan_persen ?? [],
                ]);
            }
        }

        return view('analisis.result', compact(
            'analisis',
            'rulesDiterapkanSemua',
            'ringkasan',
            'summary'
        ));
    }

    // ====================== TRACE PENALARAN ======================
    public function trace($id)
{
    $analisis = AnalisisNutrisi::with([
        'makanan',
        'tracePenalaran' => fn($q) => $q->ordered(),
        'analisisMetode.metodePengolahan',
        'analisisMetode.rule',
    ])->findOrFail($id);

    return view('analisis.trace', compact('analisis'));
}

    // ====================== HELPER METHODS ======================

    private function hitungRingkasan(array $hasilKomparasi): array
    {
        $maxVitamin        = -PHP_FLOAT_MAX;
        $maxKalori         = 0;
        $minLemak          = PHP_FLOAT_MAX;
        $minKalori         = PHP_FLOAT_MAX;
        $maxKaloriIncrease = -PHP_FLOAT_MAX;
        $maxProtein        = -PHP_FLOAT_MAX;

        // Pass 1: cari nilai ekstrem dulu
        foreach ($hasilKomparasi as $nama => $data) {
            $nutrisi      = $data['nutrisi_hasil'];
            $totalVitamin = $this->hitungTotalVitamin($nutrisi);
            $kalori       = $nutrisi['kalori'] ?? 0;
            $lemak        = $nutrisi['lemak'] ?? 0;
            $kaloriChange = $data['perubahan_persen']['kalori'] ?? 0;
            $protein      = $nutrisi['protein'] ?? 0;

            if ($totalVitamin > $maxVitamin) $maxVitamin = $totalVitamin;
            if ($kalori > $maxKalori)        $maxKalori  = $kalori;
            if ($lemak < $minLemak)          $minLemak   = $lemak;
            if ($kalori < $minKalori)        $minKalori  = $kalori;
            if ($kaloriChange > $maxKaloriIncrease) $maxKaloriIncrease = $kaloriChange;
            if ($protein > $maxProtein)        $maxProtein = $protein;
        }

        // Pass 2: kumpulkan semua metode yang mencapai nilai ekstrem tersebut
        $metodeTerbaik      = [];
        $metodeKaloriTinggi = [];
        $metodeLemakRendah  = [];
        $metodeKaloriRendah = [];
        $metodeHindari      = [];
        $metodeProteinTinggi = [];

        foreach ($hasilKomparasi as $nama => $data) {
            $nutrisi      = $data['nutrisi_hasil'];
            $totalVitamin = $this->hitungTotalVitamin($nutrisi);
            $kalori       = $nutrisi['kalori'] ?? 0;
            $lemak        = $nutrisi['lemak'] ?? 0;
            $kaloriChange = $data['perubahan_persen']['kalori'] ?? 0;
            $protein      = $nutrisi['protein'] ?? 0;

            if ($protein == $maxProtein)             $metodeProteinTinggi[] = $nama;
            if ($totalVitamin == $maxVitamin)        $metodeTerbaik[]      = $nama;
            if ($kalori == $maxKalori)               $metodeKaloriTinggi[] = $nama;
            if ($lemak == $minLemak)                 $metodeLemakRendah[]  = $nama;
            if ($kalori == $minKalori)               $metodeKaloriRendah[] = $nama;
            if ($kaloriChange == $maxKaloriIncrease) $metodeHindari[]      = $nama;
        }

        return [
            'metodeTerbaik'      => $metodeTerbaik,
            'metodeKaloriTinggi' => $metodeKaloriTinggi,
            'metodeProteinTinggi' => $metodeProteinTinggi,
            'maxProtein'         => $maxProtein,
            'metodeLemakRendah'  => $metodeLemakRendah,
            'metodeKaloriRendah' => $metodeKaloriRendah,
            'metodeHindari'      => $metodeHindari,
            'maxVitamin'         => $maxVitamin,
            'maxKalori'          => $maxKalori,
            'minLemak'           => $minLemak,
            'minKalori'          => $minKalori,
            'maxKaloriIncrease'  => $maxKaloriIncrease,
        ];
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

    // SEMENTARA INI SUMMARY TIDAK DIPAKAI DI UI
    private function generateSummary(array $hasilKomparasi): string
    {
        $summary = [
            'total_metode_dibandingkan' => count($hasilKomparasi),
            'perubahan_tertinggi'       => [],
            'perubahan_terendah'        => [],
        ];

        foreach (
            [
                'protein',
                'lemak',
                'karbohidrat',
                'kalori',
                "vitamin_a",
                'beta_karoten',
                'vitamin_b1',
                'vitamin_b2',
                'vitamin_b3',
                'vitamin_c'
            ] as $nutrisi
        ) {
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

    private function generateRecommendations(AnalisisNutrisi $analisis, array $hasilKomparasi): void
    {
        $ringkasan = $this->hitungRingkasan($hasilKomparasi);
        $namaMetodeTerbaik      = implode(' dan ', $ringkasan['metodeTerbaik']);
        $namaKaloriRendah       = implode(' dan ', $ringkasan['metodeKaloriRendah']);
        $namaHindari            = implode(' dan ', $ringkasan['metodeHindari']);
        $namaProteinTinggi      = implode(' dan ', $ringkasan['metodeProteinTinggi']);

        $recommendations = [];

        if ($ringkasan['metodeKaloriRendah'] !== null) {
            $recommendations[] = [
                'jenis'              => 'diet_rendah_kalori',
                'deskripsi'          => 'Rekomendasi untuk diet rendah kalori',
                'metode_rekomendasi' => $namaKaloriRendah,
                'alasan'             => "Metode {$namaKaloriRendah} menghasilkan kalori terendah (" .
                    number_format($ringkasan['minKalori'], 2) . " kkal).",
            ];
        }

        if ($ringkasan['metodeTerbaik'] !== null) {
            $recommendations[] = [
                'jenis'              => 'maksimal_vitamin',
                'deskripsi'          => 'Rekomendasi untuk mempertahankan vitamin',
                'metode_rekomendasi' => $namaMetodeTerbaik,
                'alasan'             => "Metode {$namaMetodeTerbaik} memiliki total vitamin tertinggi.",
            ];
        }

        if ($ringkasan['metodeHindari'] !== null && $ringkasan['maxKaloriIncrease'] > 0) {
            $recommendations[] = [
                'jenis'              => 'hindari',
                'deskripsi'          => 'Metode yang sebaiknya dihindari untuk diet',
                'metode_rekomendasi' => $namaHindari,
                'alasan'             => "Metode {$namaHindari} meningkatkan kalori hingga {$ringkasan['maxKaloriIncrease']}%.",
            ];
        }

        if ($ringkasan['metodeProteinTinggi'] !== null) {
            $recommendations[] = [
                'jenis'              => 'protein_tertinggi',
                'deskripsi'          => 'Makan porsi sedikit tapi proteinnya tinggi',
                'metode_rekomendasi' => $namaProteinTinggi,
                'alasan'             => "Metode {$namaProteinTinggi} menghasilkan protein tertinggi (" .
                    number_format($ringkasan['maxProtein'], 2) . "g).",
            ];
        }

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
