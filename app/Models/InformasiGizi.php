<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InformasiGizi extends Model
{
    use HasFactory;

    protected $table = 'informasi_gizi';

    protected $fillable = [
        'judul',
        'kategori',
        'konten',
        'icon',
        'sumber',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Scope
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
}
