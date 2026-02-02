<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        $this->call([
            MakananSeeder::class,
            MetodePengolahanSeeder::class,
            RuleSeeder::class,
            InformasiGiziSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('✓ Semua seeders berhasil dijalankan!');
        $this->command->info('========================================');
    }
    // public function run(): void
    // {
    //     // User::factory(10)->create();

    //     User::factory()->create([
    //         'name' => 'Test User',
    //         'email' => 'test@example.com',
    //     ]);
    // }
}
