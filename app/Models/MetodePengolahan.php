<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MetodePengolahan extends Model
{
    use HasFactory;

    protected $table = 'metode_pengolahan';

    protected $fillable = [
        'name',
        'icon',
        'description',
    ];

    // Relationships
    public function rules()
    {
        return $this->hasMany(Rule::class);
    }

    public function analisisMetode()
    {
        return $this->hasMany(AnalisisMetode::class);
    }

    // Helper method untuk get rule
    public function getRule()
    {
        return $this->rules()->first();
    }
}
