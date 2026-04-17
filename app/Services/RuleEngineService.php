<?php

namespace App\Services;

use App\Models\Rule;
use App\Models\Makanan;

class RuleEngineService
{
    public function applyRules(Makanan $makanan, int $metodeId): array
    {
        $nutrisi = $makanan->getNutrisiMentah();
        $appliedRules = [];

        $rules = Rule::where('metode_pengolahan_id', $metodeId)
            ->orderByRaw("
                CASE
                    WHEN makanan_id IS NOT NULL THEN 1
                    WHEN kategori IS NOT NULL THEN 2
                    ELSE 3
                END ASC
            ")
            ->orderBy('prioritas', 'DESC')
            ->get();

        foreach ($rules as $rule) {
            if ($rule->matches($metodeId, $makanan)) {
                $result = $rule->applyRule($nutrisi);

                // Update nutrisi untuk chaining rule berikutnya
                $nutrisi = $result['nutrisi_hasil'];

                $appliedRules[] = [
                    'rule_id'       => $rule->id,                    // ← WAJIB ditambahkan
                    'kode_rule'     => $rule->kode_rule,
                    'perubahan'     => $result['perubahan_persen'],
                    'penjelasan'    => $rule->penjelasan,
                    'prioritas'     => $rule->prioritas,
                    'tipe_rule'     => $this->getRuleType($rule),
                ];
            }
        }

        return [
            'nutrisi_akhir'     => $nutrisi,
            'rules_diterapkan'  => $appliedRules,
        ];
    }

    private function getRuleType(Rule $rule): string
    {
        if ($rule->makanan_id !== null) return 'spesifik_makanan';
        if ($rule->kategori !== null)   return 'kategori';
        return 'umum';
    }
}
