<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Makanan;
use App\Models\MetodePengolahan;
use App\Models\AnalisisNutrisi;
use App\Models\AnalisisMetode;
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
                    'rules_dievaluasi'     => $result['rules_diterapkan'],
                ]);

                $currentNutrisi = $nutrisiMentah;   // reset ke nutrisi mentah untuk setiap metode

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
        // Tentukan kategori makanan
        $kategoriMakanan = strtolower($analisis->makanan->kategori ?? '');
        $isProteinKarbo = in_array($kategoriMakanan, ['protein', 'karbohidrat']);

        $penjelasanSpesifik = $this->getPenjelasanSpesifik($hasilKomparasi, $analisis);

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
            'isProteinKarbo',
            'summary',
            'penjelasanSpesifik'
        ));
    }

    // ====================== TRACE PENALARAN ======================
    public function trace($id)
    {
        $analisis = AnalisisNutrisi::with([
            'makanan',
            'analisisMetode.metodePengolahan',
            'analisisMetode.rule',
        ])->findOrFail($id);

        return view('analisis.trace', compact('analisis'));
    }

    // ====================== HELPER METHODS ======================

    private function hitungRingkasan(array $hasilKomparasi): array
    {
        $maxVitamin        = -PHP_FLOAT_MAX;
        $minVitamin        = PHP_FLOAT_MAX;
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
            if ($totalVitamin < $minVitamin) $minVitamin = $totalVitamin;
            if ($kalori > $maxKalori)        $maxKalori  = $kalori;
            if ($lemak < $minLemak)          $minLemak   = $lemak;
            if ($kalori < $minKalori)        $minKalori  = $kalori;
            if ($kaloriChange > $maxKaloriIncrease) $maxKaloriIncrease = $kaloriChange;
            if ($protein > $maxProtein)        $maxProtein = $protein;
        }

        // Pass 2: kumpulkan semua metode yang mencapai nilai ekstrem tersebut
        $metodeTerbaik      = [];
        $metodeKehilanganVitamin = [];
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
            if ($totalVitamin == $minVitamin)        $metodeKehilanganVitamin[] = $nama;
            if ($kalori == $maxKalori)               $metodeKaloriTinggi[] = $nama;
            if ($lemak == $minLemak)                 $metodeLemakRendah[]  = $nama;
            if ($kalori == $minKalori)               $metodeKaloriRendah[] = $nama;
            if ($kaloriChange == $maxKaloriIncrease) $metodeHindari[]      = $nama;
        }

        return [
            'metodeTerbaik'      => $metodeTerbaik,
            'metodeKehilanganVitamin' => $metodeKehilanganVitamin,
            'metodeKaloriTinggi' => $metodeKaloriTinggi,
            'metodeProteinTinggi' => $metodeProteinTinggi,
            'maxProtein'         => $maxProtein,
            'metodeLemakRendah'  => $metodeLemakRendah,
            'metodeKaloriRendah' => $metodeKaloriRendah,
            'metodeHindari'      => $metodeHindari,
            'maxVitamin'         => $maxVitamin,
            'minVitamin'              => $minVitamin,
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

    private function getPenjelasanSpesifik(array $hasilKomparasi, AnalisisNutrisi $analisis): array
    {
        $penjelasan = [];

        foreach ($analisis->analisisMetode as $am) {
            $namaMetode = $am->metodePengolahan->name;

            // Ambil penjelasan dari rule yang benar-benar diterapkan (sudah tersimpan di analisis_metode)
            $penjelasanDariRule = $am->rule?->penjelasan ?? null;
            $kodeRule           = $am->rule?->kode_rule ?? null;
            $umumData           = $this->getPenjelasanUmum($namaMetode);

            $penjelasan[$namaMetode] = [
                'kode_rule'  => $kodeRule,
                'spesifik'   => $penjelasanDariRule,
                'umum'       => $umumData['teks'] ?? null,
                'umum_link'  => $umumData['link'] ?? null,
            ];
        }

        return $penjelasan;
    }

    private function getPenjelasanUmum(string $namaMetode): ?array
    {
        $nama = strtolower($namaMetode);

        if (str_contains($nama, 'tepung') || str_contains($nama, 'breaded') || str_contains($nama, 'battered')) {
            return [
                'teks' => "Lapisan tepung/batter pada goreng tepung bertindak sebagai insulasi termal yang melindungi bagian
        dalam dari panas langsung, sehingga dapat lebih baik mempertahankan beberapa nutrisi internal. Namun, lapisan ini 
        cenderung menyerap minyak lebih banyak, sehingga total kalori dan lemak meningkat dibanding goreng tanpa coating.",
                'link' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC10888343/',
            ];
        }

        if (str_contains($nama, 'deep') || str_contains($nama, 'rendam')) {
            return [
                'teks' => "Deep frying merendam bahan sepenuhnya dalam minyak panas sehingga terbentuk kerak luar (crust) dengan cepat 
        yang berfungsi sebagai barrier. Proses ini menyebabkan penyerapan minyak yang signifikan, peningkatan kalori/lemak, 
        serta perubahan nutrisi dan vitamin sensitif panas. Bagian dalam terpapar panas lebih singkat dibanding beberapa metode lain, tapi secara keseluruhan meningkatkan kandungan lemak.",
                'link' => 'https://link.springer.com/article/10.1007/s00217-024-04482-3',
            ];
        }

        if (str_contains($nama, 'goreng') || str_contains($nama, 'fry')) {
            return [
                'teks' => "Metode goreng (frying) menyebabkan makanan menyerap minyak sehingga
                kandungan lemak dan kalori meningkat signifikan. Jika suhu menggoreng tinggi, vitamin cenderung berkurang.
                Walaupun demikian, Vitamin A (Retinol) dan Carotenoids relatif stabil jika suhu memasak tidak terlalu tinggi.",
                'link' => 'https://onlinelibrary.wiley.com/doi/abs/10.1111/nbu.12584',
            ];
        }

        if (str_contains($nama, 'stew') || str_contains($nama, 'simmer') || str_contains($nama, 'lama')) {
            return [
                'teks' => "Pada metode stew/simmer, bahan dimasak dalam cairan dalam waktu lama dengan api kecil-sedang (di bawah suhu 100°C / tidak mendidih). 
        Vitamin larut air (B dan C) serta mineral yang keluar dari bahan makanan masuk ke dalam kuah. 
        Karena kuah biasanya ikut dikonsumsi, nutrisi tersebut tidak hilang melainkan tetap tersedia. 
        Ini berbeda dengan boiling biasa di mana air rebusan sering dibuang.",
                'link' => 'https://onlinelibrary.wiley.com/doi/abs/10.1111/nbu.12584',  // Ringkasan berbasis studi (update 2025); atau cari review spesifik braising/stewing
            ];
        }

        if (str_contains($nama, 'rebus') || str_contains($nama, 'boil')) {
            return [
                'teks' => "Vitamin B dan C merupakan nutrisi yang sensitif terhadap panas dan larut air. Vitamin tersebut larut ke dalam air
                rebusan sehingga nilai nutrisi pada makanan itu sendiri menurun.",
                'link' => 'https://onlinelibrary.wiley.com/doi/abs/10.1111/nbu.12584',
            ];
        }

        if (str_contains($nama, 'kukus') || str_contains($nama, 'steam')) {
            return [
                'teks' => "Makanan tidak bersentuhan langsung dengan air dan suhu uap yang tidak melampaui
                100°C dapat meminimalkan kehilangan nutrisi terkhususnya nutrisi sensitif panas dan larut air.",
                'link' => 'https://onlinelibrary.wiley.com/doi/abs/10.1111/nbu.12584',
            ];
        }

        if (str_contains($nama, 'panggang') || str_contains($nama, 'roast')) {
            return [
                'teks' => "Panggang (oven) menggunakan panas kering yang dapat menyebabkan 
                lemak pada daging meleleh dan menetes selama proses memasak sehingga kandungan lemak 
                total pada bahan berkurang. Suhu tinggi dapat menyebabkan denaturasi protein yang artinya penyederhanaan 
                struktur protein sehingga protein lebih mudah dicerna oleh tubuh 
                namun jika panasnya terlalu tinggi, terjadi reaksi Maillard yang merusak lisina sehingga nilai gizi 
                proteinnya berkurang lebih banyak lagi.",
                'link' => 'https://www.allresearchjournal.com/archives/2025/vol11issue10/PartC/11-10-20-294.pdf',
            ];
        }
        // Bakar / grill
        if (str_contains($nama, 'bakar') || str_contains($nama, 'grill')) {
            return [
                'teks' => "Pembakaran menyebabkan lemak meleleh dan menetes keluar (drip loss)
                sehingga kandungan lemak turun. Vitamin dan nutrisi sensitif panas juga berkurang akibat suhu tinggi.
                Suhu tinggi juga dapat menyebabkan denaturasi protein yang artinya penyederhanaan 
                struktur protein sehingga protein lebih mudah dicerna oleh tubuh 
                namun jika panasnya terlalu tinggi, terjadi reaksi Maillard yang merusak lisina sehingga nilai gizi 
                proteinnya berkurang lebih banyak lagi.",
                'link' => 'https://www.allresearchjournal.com/archives/2025/vol11issue10/PartC/11-10-20-294.pdf',
            ];
        }
        return null;
    }
}
