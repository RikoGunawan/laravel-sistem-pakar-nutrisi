<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MetodePengolahan;
use App\Models\Rule;

class RuleSeeder extends Seeder
{
    public function run(): void
    {
        // Get metode IDs
        $goreng = MetodePengolahan::where('name', 'Goreng')->first();
        $kukus = MetodePengolahan::where('name', 'Kukus')->first();
        $rebus = MetodePengolahan::where('name', 'Rebus')->first();
        $bakar = MetodePengolahan::where('name', 'Bakar')->first();
        $tumis = MetodePengolahan::where('name', 'Tumis')->first();

        $rulesData = [
            // Rule Goreng
            [
                'metode_pengolahan_id' => $goreng->id,
                'kode_rule' => 'FR1',
                'kondisi' => 'IF menggoreng makanan',
                'perubahan_nutrisi' => [
                    'protein' => 0,
                    'lemak' => 35,
                    'karbohidrat' => 12,
                    'kalori' => 70,
                    'vitamin_c' => -20,
                    'vitamin_b_complex' => -25,
                ],
                'prioritas' => 1,
                'penjelasan' => 'Penggorengan menyebabkan penyerapan minyak yang meningkatkan lemak dan kalori secara signifikan (30-40%). Vitamin larut air seperti Vitamin C dan B kompleks berkurang 20-30% karena suhu tinggi. Protein tetap stabil karena denaturasi normal.',
                'sumber_referensi' => 'Liu et al. (2023), Kansas Living (2020), Healthline (2019)',
            ],

            // Rule Kukus
            [
                'metode_pengolahan_id' => $kukus->id,
                'kode_rule' => 'ST1',
                'kondisi' => 'IF mengukus makanan',
                'perubahan_nutrisi' => [
                    'protein' => 0,
                    'lemak' => 0,
                    'karbohidrat' => 0,
                    'kalori' => 0,
                    'vitamin_c' => -10,
                    'vitamin_b_complex' => -12,
                ],
                'prioritas' => 1,
                'penjelasan' => 'Pengukusan adalah metode terbaik untuk mempertahankan nutrisi. Retensi vitamin mencapai 85-90%, lebih tinggi dari metode lain. Tidak ada penambahan lemak sehingga kalori tetap stabil. Protein terjaga dengan baik karena suhu tidak terlalu tinggi.',
                'sumber_referensi' => 'Lee et al. (2018), Yuan et al. (2009), Tufts (2019)',
            ],

            // Rule Rebus
            [
                'metode_pengolahan_id' => $rebus->id,
                'kode_rule' => 'BL1',
                'kondisi' => 'IF merebus makanan',
                'perubahan_nutrisi' => [
                    'protein' => 0,
                    'lemak' => -15,
                    'karbohidrat' => -15,
                    'kalori' => -8,
                    'vitamin_c' => -55,
                    'vitamin_b_complex' => -45,
                ],
                'prioritas' => 1,
                'penjelasan' => 'Perebusan menyebabkan kehilangan vitamin larut air yang signifikan (40-60%) karena larut dalam air rebusan. Karbohidrat dan mineral juga ikut larut ke air. Namun tidak ada penambahan lemak, sehingga kalori bisa sedikit turun. Protein relatif stabil.',
                'sumber_referensi' => 'Rahman et al. (2023), Coe and Spiro (2022), Healthline (2019)',
            ],

            // Rule Bakar
            [
                'metode_pengolahan_id' => $bakar->id,
                'kode_rule' => 'GR1',
                'kondisi' => 'IF membakar makanan',
                'perubahan_nutrisi' => [
                    'protein' => -3,
                    'lemak' => -25,
                    'karbohidrat' => 0,
                    'kalori' => -12,
                    'vitamin_c' => -38,
                    'vitamin_b_complex' => -35,
                ],
                'prioritas' => 1,
                'penjelasan' => 'Pembakaran mengurangi lemak alami karena meleleh dan menetes (20-30%). Vitamin berkurang karena panas tinggi (30-40%). Kalori turun karena kehilangan lemak. Protein sedikit terdenaturasi. Cocok untuk diet rendah lemak namun perlu perhatian karena vitamin berkurang signifikan.',
                'sumber_referensi' => 'Red Field Ranch (2025), Healthline (2019), Kansas Living (2020)',
            ],

            // Rule Tumis
            [
                'metode_pengolahan_id' => $tumis->id,
                'kode_rule' => 'SF1',
                'kondisi' => 'IF menumis makanan',
                'perubahan_nutrisi' => [
                    'protein' => 0,
                    'lemak' => 12,
                    'karbohidrat' => 3,
                    'kalori' => 15,
                    'vitamin_c' => -18,
                    'vitamin_b_complex' => -12,
                ],
                'prioritas' => 1,
                'penjelasan' => 'Penumisan menggunakan sedikit minyak dan waktu masak cepat. Lemak naik sedikit (10-15%) tapi lebih rendah dari menggoreng. Vitamin lebih terjaga (retensi >85%) karena waktu masak singkat. Metode ini balance antara rasa dan nutrisi.',
                'sumber_referensi' => 'Kansas Living (2020), Yuan et al. (2009), Healthline (2019)',
            ],
        ];

        foreach ($rulesData as $data) {
            Rule::create($data);
        }

        $this->command->info('✓ Berhasil menambahkan ' . count($rulesData) . ' rules');
    }
}
