<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InformasiGizi;

class InformasiGiziSeeder extends Seeder
{
    public function run(): void
    {
        $informasiData = [
            [
                'judul' => 'Pentingnya Vitamin C',
                'kategori' => 'fakta',
                'konten' => 'Vitamin C adalah antioksidan kuat yang membantu sistem kekebalan tubuh. Namun, vitamin C sangat sensitif terhadap panas dan mudah rusak saat dimasak. Metode kukus mempertahankan hingga 90% vitamin C, sementara perebusan bisa menghilangkan hingga 70%.',
                'icon' => '🍊',
                'sumber' => 'Kementerian Kesehatan RI',
                'is_published' => true,
            ],
            [
                'judul' => 'Cara Terbaik Masak Sayuran',
                'kategori' => 'tips',
                'konten' => 'Untuk mempertahankan nutrisi sayuran, gunakan metode kukus atau tumis cepat. Hindari merebus terlalu lama karena vitamin larut air akan hilang. Jika harus merebus, gunakan air sedikit dan manfaatkan air rebusannya untuk kuah.',
                'icon' => '🥦',
                'sumber' => 'Ahli Gizi Indonesia',
                'is_published' => true,
            ],
            [
                'judul' => 'Bahaya Gorengan Berlebihan',
                'kategori' => 'fakta',
                'konten' => 'Makanan yang digoreng menyerap banyak minyak, meningkatkan lemak dan kalori hingga 60-100%. Konsumsi gorengan berlebihan dapat meningkatkan risiko obesitas, penyakit jantung, dan diabetes. Batasi konsumsi gorengan maksimal 2-3 kali seminggu.',
                'icon' => '🍟',
                'sumber' => 'WHO, Kemenkes RI',
                'is_published' => true,
            ],
            [
                'judul' => 'Protein untuk Otot',
                'kategori' => 'panduan',
                'konten' => 'Protein adalah nutrisi penting untuk membangun dan memperbaiki jaringan tubuh. Kebutuhan protein harian: 0.8-1 gram per kg berat badan. Sumber protein terbaik: ayam, ikan, telur, kacang-kacangan. Metode masak yang baik: kukus, panggang, atau tumis.',
                'icon' => '💪',
                'sumber' => 'Departemen Gizi FKUI',
                'is_published' => true,
            ],
            [
                'judul' => 'Karbohidrat Kompleks vs Sederhana',
                'kategori' => 'panduan',
                'konten' => 'Karbohidrat kompleks (nasi merah, kentang, oatmeal) dicerna lebih lambat, memberikan energi stabil. Karbohidrat sederhana (gula, nasi putih) cepat meningkatkan gula darah. Pilih karbohidrat kompleks untuk diet sehat dan mengontrol berat badan.',
                'icon' => '🍚',
                'sumber' => 'TKPI 2020',
                'is_published' => true,
            ],
        ];

        foreach ($informasiData as $data) {
            InformasiGizi::create($data);
        }

        $this->command->info('✓ Berhasil menambahkan ' . count($informasiData) . ' informasi gizi');
    }
}
