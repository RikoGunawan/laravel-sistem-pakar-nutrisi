<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnalisisMetode extends Model
{
    use HasFactory;

    protected $table = 'analisis_metode';

    protected $fillable = [
        'analisis_nutrisi_id',
        'metode_pengolahan_id',
        'rule_id',
        'nutrisi_hasil',
        'perubahan_persen',
    ];

    protected $casts = [
        'nutrisi_hasil' => 'array',
        'perubahan_persen' => 'array',
    ];

    // Relationships
    public function analisisNutrisi()
    {
        return $this->belongsTo(AnalisisNutrisi::class);
    }

    public function metodePengolahan()
    {
        return $this->belongsTo(MetodePengolahan::class);
    }

    public function rule()
    {
        return $this->belongsTo(Rule::class);
    }

    // Helper methods
    public function getPerubahanDetail()
    {
        $nutrisiMentah = $this->analisisNutrisi->nutrisi_mentah;
        $nutrisiHasil = $this->nutrisi_hasil;
        $detail = [];

        foreach ($nutrisiMentah as $key => $nilaiMentah) {
            $nilaiHasil = $nutrisiHasil[$key] ?? $nilaiMentah;
            $perubahan = $this->perubahan_persen[$key] ?? 0;

            $detail[$key] = [
                'mentah' => $nilaiMentah,
                'hasil' => $nilaiHasil,
                'perubahan_persen' => $perubahan,
                'status' => $perubahan > 0 ? 'naik' : ($perubahan < 0 ? 'turun' : 'stabil'),
            ];
        }

        return $detail;
    }
}
