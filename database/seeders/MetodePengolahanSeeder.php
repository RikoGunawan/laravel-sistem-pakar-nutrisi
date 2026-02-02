<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MetodePengolahan;

class MetodePengolahanSeeder extends Seeder
{
    public function run(): void
    {
        $metodeData = [
            [
                'name' => 'Goreng',
                'icon' => '🍳',
                'description' => 'Memasak dengan minyak panas. Meningkatkan lemak dan kalori, mengurangi vitamin larut air.',
            ],
            [
                'name' => 'Kukus',
                'icon' => '♨️',
                'description' => 'Memasak dengan uap air. Metode terbaik untuk mempertahankan nutrisi, terutama vitamin.',
            ],
            [
                'name' => 'Rebus',
                'icon' => '🫕',
                'description' => 'Memasak dalam air mendidih. Vitamin larut air akan berkurang signifikan.',
            ],
            [
                'name' => 'Bakar',
                'icon' => '🔥',
                'description' => 'Memasak dengan panas langsung. Mengurangi lemak namun bisa mengurangi vitamin.',
            ],
            [
                'name' => 'Tumis',
                'icon' => '🥘',
                'description' => 'Memasak cepat dengan sedikit minyak. Lebih baik dari goreng untuk mempertahankan nutrisi.',
            ],
        ];

        foreach ($metodeData as $data) {
            MetodePengolahan::create($data);
        }

        $this->command->info('✓ Berhasil menambahkan ' . count($metodeData) . ' metode pengolahan');
    }
}
