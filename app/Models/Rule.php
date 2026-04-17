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
    // Helper method untuk apply rule
    public function applyRule($nutrisiMentah)
    {
        $nutrisiHasil = [];
        $perubahanPersen = $this->perubahan_nutrisi;

        foreach ($nutrisiMentah as $key => $nilai) {
            if ($nilai === null) {
                $nutrisiHasil[$key] = null;
                continue;
            }

            if (isset($perubahanPersen[$key]) && is_numeric($nilai)) {
                $perubahan = $perubahanPersen[$key];
                $nutrisiHasil[$key] = $nilai * (1 + ($perubahan / 100));
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
