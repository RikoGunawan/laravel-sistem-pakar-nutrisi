<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TracePenalaran extends Model
{
    use HasFactory;

    protected $table = 'trace_penalaran';

    protected $fillable = [
        'analisis_nutrisi_id',
        'fakta_awal',
        'rule_used',
        'proses',
        'fakta_baru',
        'step_order',
    ];

    // Relationships
    public function analisisNutrisi()
    {
        return $this->belongsTo(AnalisisNutrisi::class);
    }

    // Scope untuk ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('step_order', 'asc');
    }
}
