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

    public function analisisMetode()
    {
        return $this->hasMany(AnalisisMetode::class);
    }

    // Helper method untuk apply rule
    public function applyRule($nutrisiMentah)
    {
        $nutrisiHasil = [];
        $perubahanPersen = $this->perubahan_nutrisi;

        foreach ($nutrisiMentah as $key => $nilai) {
            if (isset($perubahanPersen[$key])) {
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

    // Check kondisi (untuk forward chaining)
    public function cekKondisi($metodeId)
    {
        return $this->metode_pengolahan_id == $metodeId;
    }
}
