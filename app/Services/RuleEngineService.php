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

        // Tentukan rule mana yang benar-benar diterapkan (paling spesifik)
        $ruleYangDiterapkan = null;
        foreach ($rules as $rule) {
            if ($rule->matches($metodeId, $makanan)) {
                $ruleYangDiterapkan = $rule;
                break; // ← ambil yang pertama (paling spesifik) saja
            }
        }

        if (!$ruleYangDiterapkan) {
            return [
                'nutrisi_akhir'    => $nutrisi,
                'rules_diterapkan' => [],
            ];
        }

        // Terapkan hanya rule terpilih
        $result = $ruleYangDiterapkan->applyRule($nutrisi);
        $nutrisi = $result['nutrisi_hasil'];

        $appliedRules[] = [
            'rule_id'    => $ruleYangDiterapkan->id,
            'kode_rule'  => $ruleYangDiterapkan->kode_rule,
            'perubahan'  => $result['perubahan_persen'],
            'penjelasan' => $ruleYangDiterapkan->penjelasan,
            'prioritas'  => $ruleYangDiterapkan->prioritas,
            'tipe_rule'  => $this->getRuleType($ruleYangDiterapkan),
            'rule'       => $ruleYangDiterapkan,
        ];

        // Rule lain yang match tapi tidak diterapkan → masuk trace sebagai "dilewati"
        foreach ($rules as $rule) {
            if ($rule->id === $ruleYangDiterapkan->id) continue;
            if ($rule->matches($metodeId, $makanan)) {
                $appliedRules[] = [
                    'rule_id'    => $rule->id,
                    'kode_rule'  => $rule->kode_rule,
                    'perubahan'  => $rule->perubahan_nutrisi,
                    'penjelasan' => $rule->penjelasan . ' [tidak diterapkan - kalah prioritas]',
                    'prioritas'  => $rule->prioritas,
                    'tipe_rule'  => $this->getRuleType($rule),
                    'rule'       => $rule,
                    'dilewati'   => true, // ← flag untuk UI/trace
                ];
            }
        }

        return [
            'nutrisi_akhir'    => $nutrisi,
            'rules_diterapkan' => $appliedRules,
        ];
    }

    private function getRuleType(Rule $rule): string
    {
        if ($rule->makanan_id !== null) return 'spesifik_makanan';
        if ($rule->kategori !== null)   return 'kategori';
        return 'umum';
    }
}
