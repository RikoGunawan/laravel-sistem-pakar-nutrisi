<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnalisisNutrisi extends Model
{
    use HasFactory;

    protected $table = 'analisis_nutrisi';

    protected $fillable = [
        'makanan_id',
        'nutrisi_mentah',
        'hasil_komparasi',
        'summary',
        'metode_terbaik',
        'session_id',
        'ip_address',
    ];

    protected $casts = [
        'nutrisi_mentah' => 'array',
        'hasil_komparasi' => 'array',
    ];

    // Relationships
    public function makanan()
    {
        return $this->belongsTo(Makanan::class);
    }

    public function analisisMetode()
    {
        return $this->hasMany(AnalisisMetode::class);
    }

    public function metodePengolahan()
    {
        return $this->belongsToMany(
            MetodePengolahan::class,
            'analisis_metode',
            'analisis_nutrisi_id',
            'metode_pengolahan_id'
        )->withPivot(['nutrisi_hasil', 'perubahan_persen', 'rule_id'])
          ->withTimestamps();
    }

    public function tracePenalaran()
    {
        return $this->hasMany(TracePenalaran::class);
    }

    public function rekomendasi()
    {
        return $this->hasMany(Rekomendasi::class);
    }

    // Helper method
    public function generateSummary()
    {
        $metodeTerbaik = null;
        $minKalori = PHP_INT_MAX;

        foreach ($this->analisisMetode as $analisis) {
            $nutrisiHasil = $analisis->nutrisi_hasil;
            if ($nutrisiHasil['kalori'] < $minKalori) {
                $minKalori = $nutrisiHasil['kalori'];
                $metodeTerbaik = $analisis->metodePengolahan->name;
            }
        }

        return [
            'metode_terbaik' => $metodeTerbaik,
            'total_metode' => $this->analisisMetode->count(),
        ];
    }
}
