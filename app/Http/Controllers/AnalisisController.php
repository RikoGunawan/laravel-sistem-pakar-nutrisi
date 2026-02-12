<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Makanan;
use App\Models\MetodePengolahan;
use App\Models\AnalisisNutrisi;
use App\Models\AnalisisMetode;
use App\Models\TracePenalaran;
use App\Models\Rekomendasi;
use Illuminate\Support\Facades\DB;

class AnalisisController extends Controller
{
    /**
     * Show analisis form
     */
    public function index()
    {
        $makananList = Makanan::orderBy('name', 'asc')->get();
        $metodeList = MetodePengolahan::all();

        return view('analisis.index', compact('makananList', 'metodeList'));
    }

    /**
     * Process analisis (Forward Chaining)
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'makanan_id' => 'required|exists:makanan,id',
            'metode_ids' => 'required|array|min:1',
            'metode_ids.*' => 'exists:metode_pengolahan,id',
        ]);

        try {
            DB::beginTransaction();

            // 1. Get makanan data
            $makanan = Makanan::findOrFail($request->makanan_id);
            $nutrisiMentah = $makanan->getNutrisiMentah();

            // 2. Create analisis record
            $analisis = AnalisisNutrisi::create([
                'makanan_id' => $makanan->id,
                'nutrisi_mentah' => $nutrisiMentah,
                'session_id' => session()->getId(),
                'ip_address' => $request->ip(),
            ]);

            // 3. Process each metode (Forward Chaining)
            $hasilKomparasi = [];
            $stepOrder = 1;

            foreach ($request->metode_ids as $metodeId) {
                $metode = MetodePengolahan::findOrFail($metodeId);
                $rule = $metode->getRule();

                if ($rule) {
                    // Apply rule (Forward Chaining)
                    $hasil = $rule->applyRule($nutrisiMentah);

                    // Save to analisis_metode
                    $analisisMetode = AnalisisMetode::create([
                        'analisis_nutrisi_id' => $analisis->id,
                        'metode_pengolahan_id' => $metodeId,
                        'rule_id' => $rule->id,
                        'nutrisi_hasil' => $hasil['nutrisi_hasil'],
                        'perubahan_persen' => $hasil['perubahan_persen'],
                    ]);

                    // Save trace penalaran
                    TracePenalaran::create([
                        'analisis_nutrisi_id' => $analisis->id,
                        'fakta_awal' => "Makanan: {$makanan->name}, Nutrisi Mentah: " . json_encode($nutrisiMentah),
                        'rule_used' => $rule->kode_rule,
                        'proses' => "Menerapkan rule {$rule->kode_rule}: {$rule->kondisi}. Perubahan: " . json_encode($rule->perubahan_nutrisi),
                        'fakta_baru' => "Nutrisi setelah {$metode->name}: " . json_encode($hasil['nutrisi_hasil']),
                        'step_order' => $stepOrder++,
                    ]);

                    // Collect hasil for comparison
                    $hasilKomparasi[$metode->name] = [
                        'metode_id' => $metodeId,
                        'nutrisi_hasil' => $hasil['nutrisi_hasil'],
                        'perubahan_persen' => $hasil['perubahan_persen'],
                        'penjelasan' => $rule->penjelasan,
                    ];
                }
            }

            // 4. Generate summary & recommendations
            $summary = $this->generateSummary($hasilKomparasi, $nutrisiMentah);
            $metodeTerbaik = $this->findBestMethod($hasilKomparasi);

            // Update analisis
            $analisis->update([
                'hasil_komparasi' => $hasilKomparasi,
                'summary' => $summary,
                'metode_terbaik' => $metodeTerbaik,
            ]);

            // 5. Generate recommendations
            $this->generateRecommendations($analisis, $hasilKomparasi);

            DB::commit();

            return redirect()->route('analisis.result', $analisis->id)
                ->with('success', 'Analisis berhasil dilakukan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show analysis result
     */
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

    /**
     * Show trace penalaran
     */
    public function trace($id)
    {
        $analisis = AnalisisNutrisi::with(['tracePenalaran' => function($query) {
            $query->ordered();
        }])->findOrFail($id);

        return view('analisis.trace', compact('analisis'));
    }

    /**
     * HELPER: Generate summary
     */
    private function generateSummary($hasilKomparasi, $nutrisiMentah)
    {
        $summary = [
            'total_metode_dibandingkan' => count($hasilKomparasi),
            'perubahan_tertinggi' => [],
            'perubahan_terendah' => [],
        ];

        // Find highest and lowest changes
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

    /**
     * HELPER: Find best method (lowest calorie increase, highest vitamin retention)
     */
    private function findBestMethod($hasilKomparasi)
    {
        if (empty($hasilKomparasi)) {
            return null;
        }

        $scores = [];

        foreach ($hasilKomparasi as $metode => $data) {
            $score = 0;

            // Lower calorie increase = better
            $kaloriChange = $data['perubahan_persen']['kalori'] ?? 0;
            $score -= abs($kaloriChange);

            // Lower fat increase = better
            $lemakChange = $data['perubahan_persen']['lemak'] ?? 0;
            $score -= abs($lemakChange);

            // Higher vitamin retention = better
            $vitaminCChange = $data['perubahan_persen']['vitamin_c'] ?? 0;
            $score += (100 + $vitaminCChange); // Convert negative to positive score

            $scores[$metode] = $score;
        }

        arsort($scores);
        return array_key_first($scores);
    }

    /**
     * HELPER: Generate recommendations based on analysis
     * FIXED: Prevent null metode_rekomendasi
     */
    private function generateRecommendations($analisis, $hasilKomparasi)
    {
        // Check if empty
        if (empty($hasilKomparasi)) {
            return;
        }

        $recommendations = [];

        // 1. Rekomendasi untuk diet rendah kalori
        $minKalori = PHP_FLOAT_MAX; // FIXED: Use FLOAT_MAX instead of INT_MAX
        $metodeKaloriRendah = null;

        foreach ($hasilKomparasi as $metode => $data) {
            if (isset($data['nutrisi_hasil']['kalori']) && $data['nutrisi_hasil']['kalori'] < $minKalori) {
                $minKalori = $data['nutrisi_hasil']['kalori'];
                $metodeKaloriRendah = $metode;
            }
        }

        // Only add if metode found
        if ($metodeKaloriRendah !== null) {
            $recommendations[] = [
                'jenis' => 'diet_rendah_kalori',
                'deskripsi' => 'Rekomendasi untuk diet rendah kalori',
                'metode_rekomendasi' => $metodeKaloriRendah,
                'alasan' => "Metode {$metodeKaloriRendah} menghasilkan kalori terendah (" . number_format($minKalori, 2) . " kkal) dibanding metode lain.",
            ];
        }

        // 2. Rekomendasi untuk maksimalkan vitamin
        $maxVitaminC = -PHP_FLOAT_MAX; // FIXED: Use FLOAT_MAX
        $metodeVitaminTerbaik = null;

        foreach ($hasilKomparasi as $metode => $data) {
            $vitaminCChange = $data['perubahan_persen']['vitamin_c'] ?? -100;
            if ($vitaminCChange > $maxVitaminC) {
                $maxVitaminC = $vitaminCChange;
                $metodeVitaminTerbaik = $metode;
            }
        }

        // Only add if metode found
        if ($metodeVitaminTerbaik !== null) {
            $recommendations[] = [
                'jenis' => 'maksimal_vitamin',
                'deskripsi' => 'Rekomendasi untuk mempertahankan vitamin',
                'metode_rekomendasi' => $metodeVitaminTerbaik,
                'alasan' => "Metode {$metodeVitaminTerbaik} mempertahankan vitamin C terbaik dengan hanya kehilangan " . abs($maxVitaminC) . "%.",
            ];
        }

        // 3. Rekomendasi metode yang sebaiknya dihindari
        $maxKaloriIncrease = -PHP_FLOAT_MAX; // FIXED: Use FLOAT_MAX
        $metodeHindari = null;

        foreach ($hasilKomparasi as $metode => $data) {
            $kaloriChange = $data['perubahan_persen']['kalori'] ?? 0;
            if ($kaloriChange > $maxKaloriIncrease) {
                $maxKaloriIncrease = $kaloriChange;
                $metodeHindari = $metode;
            }
        }

        // Only add if metode found
        if ($metodeHindari !== null) {
            $recommendations[] = [
                'jenis' => 'hindari',
                'deskripsi' => 'Metode yang sebaiknya dihindari untuk diet',
                'metode_rekomendasi' => $metodeHindari,
                'alasan' => "Metode {$metodeHindari} meningkatkan kalori hingga {$maxKaloriIncrease}%, tidak cocok untuk diet rendah kalori.",
            ];
        }

        // Save recommendations only if we have any
        foreach ($recommendations as $rec) {
            Rekomendasi::create([
                'analisis_nutrisi_id' => $analisis->id,
                'jenis' => $rec['jenis'],
                'deskripsi' => $rec['deskripsi'],
                'metode_rekomendasi' => $rec['metode_rekomendasi'],
                'alasan' => $rec['alasan'],
            ]);
        }
    }
}
