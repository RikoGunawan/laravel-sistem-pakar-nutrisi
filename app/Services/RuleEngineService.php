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

        $rules = Rule::where('metode_pengolahan_id', $metodeId)->get();

        // Sort prioritas makanan_id > sub_kategori > kategori > umum
        $rules = $rules->sortBy(function (Rule $rule) use ($makanan) {
            if ($rule->makanan_id !== null)  return 1;
            if ($rule->kategori !== null) {
                if (!empty($makanan->sub_kategori) && $rule->kategori === $makanan->sub_kategori) {
                    return 2;
                }
                return 3;
            }
            return 4;
        })->values();

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
            'tipe_rule'  => $this->getRuleType($ruleYangDiterapkan),
        ];

        // Rule lain yang match tapi tidak diterapkan → masuk trace sebagai "dilewati"
        foreach ($rules as $rule) {
            if ($rule->id === $ruleYangDiterapkan->id) continue;
            if ($rule->matches($metodeId, $makanan)) {
                $appliedRules[] = [
                    'rule_id'    => $rule->id,
                    'kode_rule'  => $rule->kode_rule,
                    'tipe_rule'  => $this->getRuleType($rule),
                    'dilewati'   => true, // ← flag untuk UI/trace
                ];
            }
        }

        return [
            'nutrisi_akhir'    => $nutrisi,
            'rules_diterapkan' => $appliedRules,
        ];
    }

    private function getRuleType(Rule $rule, ?Makanan $makanan = null): string
    {
        if ($rule->makanan_id !== null) return 'spesifik_makanan';
        if ($rule->kategori !== null) {
            if ($makanan && !empty($makanan->sub_kategori) && $rule->kategori === $makanan->sub_kategori) {
                return 'sub_kategori';
            }
            return 'kategori';
        }
        return 'umum';
    }
}
