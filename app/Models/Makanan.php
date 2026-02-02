<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Makanan extends Model
{
    use HasFactory;

    protected $table = 'makanan';

    protected $fillable = [
        'name',
        'kategori',
        'description',
        'image',
        'sumber_data',
        'protein',
        'lemak',
        'karbohidrat',
        'kalori',
        'serat',
        'gula',
        'vitamin_a',
        'vitamin_b1',
        'vitamin_b2',
        'vitamin_b3',
        'vitamin_b5',
        'vitamin_b6',
        'vitamin_b12',
        'vitamin_c',
        'natrium',
    ];

    protected $casts = [
        'protein' => 'decimal:2',
        'lemak' => 'decimal:2',
        'karbohidrat' => 'decimal:2',
        'kalori' => 'decimal:2',
        'serat' => 'decimal:2',
        'gula' => 'decimal:2',
        'vitamin_a' => 'decimal:2',
        'vitamin_b1' => 'decimal:2',
        'vitamin_b2' => 'decimal:2',
        'vitamin_b3' => 'decimal:2',
        'vitamin_b5' => 'decimal:2',
        'vitamin_b6' => 'decimal:2',
        'vitamin_b12' => 'decimal:2',
        'vitamin_c' => 'decimal:2',
        'natrium' => 'decimal:2',
    ];

    // Relationships
    public function analisisNutrisi()
    {
        return $this->hasMany(AnalisisNutrisi::class);
    }

    // Helper Methods
    public function getNutrisiMentah()
    {
        return [
            'protein' => $this->protein,
            'lemak' => $this->lemak,
            'karbohidrat' => $this->karbohidrat,
            'kalori' => $this->kalori,
            'vitamin_c' => $this->vitamin_c,
            'vitamin_b_complex' => ($this->vitamin_b1 + $this->vitamin_b2 + $this->vitamin_b3) / 3,
        ];
    }

    public function getDetailNutrisi()
    {
        return [
            'nama' => $this->name,
            'kategori' => $this->kategori,
            'nutrisi' => $this->getNutrisiMentah(),
        ];
    }

    // Scope untuk filter
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }
}
