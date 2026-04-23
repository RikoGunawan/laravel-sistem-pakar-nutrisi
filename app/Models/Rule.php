<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    protected $table = 'rules';

    protected $fillable = [
        'metode_pengolahan_id',
        'makanan_id',
        'kategori',
        'kode_rule',
        'kondisi',
        'perubahan_nutrisi',
        'prioritas',
        'penjelasan',
        'sumber_referensi',
    ];

    protected $casts = [
        'perubahan_nutrisi' => 'array',
    ];

    // Relationships
    public function metodePengolahan()
    {
        return $this->belongsTo(MetodePengolahan::class);
    }

    public function makanan()                   // ← tambahan
    {
        return $this->belongsTo(Makanan::class);
    }

    public function analisisMetode()
    {
        return $this->hasMany(AnalisisMetode::class);
    }

    //Cek apakah rule ini cocok dengan makanan & metode yang diberikan
    public function matches(int $metodeId, ?Makanan $makanan = null): bool
    {
        if ($this->metode_pengolahan_id != $metodeId) {
            return false;
        }

        // Level 1: Berdasarkan makanan_id
        if ($this->makanan_id !== null) {
            return $makanan && $this->makanan_id === $makanan->id;
        }

        // Level 2: Berdasarkan kategori
        if ($this->kategori !== null) {
            return $makanan && $this->kategori === $makanan->kategori;
        }

        // Level 3: Rule umum (hanya metode)
        return true;
    }

    public function applyRule($nutrisiMentah)
    {
        $nutrisiHasil = [];
        $perubahanPersen = $this->perubahan_nutrisi;

        $makronutrien = ['karbohidrat', 'protein', 'lemak'];

        foreach ($nutrisiMentah as $key => $nilai) {
            if ($nilai === null) {
                $nutrisiHasil[$key] = null;
                continue;
            }

            if (isset($perubahanPersen[$key]) && is_numeric($nilai)) {
                $perubahan = $perubahanPersen[$key];

                // MAKRONUTRIEN
                if (in_array(strtolower($key), $makronutrien)) {
                    // Untuk kasus khusus: Karbo ayam = 0 karena tepung dia jadi 19 g naik ga bisa pakai %
                    if ($nilai == 0) {
                        $nilai = 0.1;
                        $nutrisiHasil[$key] = max(0, $nilai * ($perubahan / 100));
                    } else {
                        $nutrisiHasil[$key] = max(0, $nilai + ($nilai * ($perubahan / 100)));
                    }
                } elseif ($key === 'kalori' && $perubahan == 0) {
                    // Rumus 4-4-9, pakai nilai hasil makronutrien yang sudah dihitung
                    $nutrisiHasil[$key] =
                        (($nutrisiHasil['protein'] ?? 0) * 4) +
                        (($nutrisiHasil['karbohidrat'] ?? 0) * 4) +
                        (($nutrisiHasil['lemak'] ?? 0) * 9);
                }
                // MIKRONUTRIEN
                else {
                    $nutrisiHasil[$key] = max(0, $nilai * (1 + ($perubahan / 100)));
                }
            } else {
                $nutrisiHasil[$key] = $nilai;
            }
        }

        return [
            'nutrisi_hasil' => $nutrisiHasil,
            'perubahan_persen' => $perubahanPersen,
        ];
    }
}
