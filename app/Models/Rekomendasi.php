<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rekomendasi extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi';

    protected $fillable = [
        'analisis_nutrisi_id',
        'jenis',
        'deskripsi',
        'metode_rekomendasi',
        'alasan',
    ];

    // Relationships
    public function analisisNutrisi()
    {
        return $this->belongsTo(AnalisisNutrisi::class);
    }
}
