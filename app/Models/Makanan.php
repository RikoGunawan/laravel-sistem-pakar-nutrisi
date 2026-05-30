<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Makanan extends Model
{
    use HasFactory;

    protected $table = 'makanan';

    protected $fillable = [
        'name',
        'kategori',
        'sub_kategori',
        'description',
        'image',
        'sumber_data',
        'metode_cocok',
        'catatan_pengolahan',
        'protein',
        'lemak',
        'karbohidrat',
        'kalori',
        'serat',
        'gula',
        'vitamin_a',
        'beta_karoten',
        'vitamin_b1',
        'vitamin_b2',
        'vitamin_b3',
        'vitamin_b5',
        'vitamin_b6',
        'vitamin_b12',
        'vitamin_c',
        'natrium',
    ];

    protected function casts(): array   // Laravel 11 style (bisa juga pakai protected $casts = [])
    {
        return [
            'metode_cocok' => 'array',

            'protein'     => 'decimal:2',
            'lemak'       => 'decimal:2',
            'karbohidrat' => 'decimal:2',
            'kalori'      => 'decimal:0',
            'serat'       => 'decimal:3',
            'gula'        => 'decimal:3',

            'vitamin_a'   => 'decimal:3',
            'beta_karoten' => 'decimal:2',
            'vitamin_b1'  => 'decimal:4',
            'vitamin_b2'  => 'decimal:4',
            'vitamin_b3'  => 'decimal:3',
            'vitamin_b5'  => 'decimal:3',
            'vitamin_b6'  => 'decimal:3',
            'vitamin_b12' => 'decimal:4',
            'vitamin_c'   => 'decimal:3',

            'natrium'     => 'decimal:2',
        ];
    }

    // Relationships
    public function analisisNutrisi()
    {
        return $this->hasMany(AnalisisNutrisi::class);
    }

    // Helper Methods
    public function getNutrisiMentah(): array
    {
        return [
            'protein'      => round((float) $this->protein, 2),
            'lemak'        => round((float) $this->lemak, 2),
            'karbohidrat'  => round((float) $this->karbohidrat, 2),

            // Khusus kalori: tidak boleh ada koma sama sekali
            'kalori'       => (int) round((float) $this->kalori ?? 0),

            'vitamin_a'    => round((float) $this->vitamin_a, 3),
            'beta_karoten' => round((float) $this->beta_karoten, 2),
            'vitamin_c'    => round((float) $this->vitamin_c, 3),
            'vitamin_b1'   => round((float) $this->vitamin_b1, 4),
            'vitamin_b2'   => round((float) $this->vitamin_b2, 4),
            'vitamin_b3'   => round((float) $this->vitamin_b3, 3),
            'vitamin_b5'   => round((float) $this->vitamin_b5, 3),
            'vitamin_b6'   => round((float) $this->vitamin_b6, 3),
            'vitamin_b12'  => round((float) $this->vitamin_b12, 4),
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

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }


    public function isMetodeCocok($metodeId): bool
    {
        $metodeCocok = $this->metode_cocok;

        // Jika null atau array kosong → TIDAK BOLEH dianalisis
        if (empty($metodeCocok)) {
            return false;
        }

        return in_array($metodeId, $metodeCocok);
    }
}
