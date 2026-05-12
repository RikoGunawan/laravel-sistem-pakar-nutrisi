<?php

namespace App\Observers;

use App\Models\Rule;
use App\Models\Makanan;

class RuleObserver
{
    public function saved(Rule $rule): void
    {
        $this->sync($rule);
    }

    public function created(Rule $rule): void
    {
        //
    }

    /**
     * Handle the Rule "updated" event.
     */
    public function updated(Rule $rule): void
    {
        //
    }

    /**
     * Handle the Rule "deleted" event.
     */
    public function deleted(Rule $rule): void
    {
        $this->sync($rule);
    }

    private function sync(Rule $rule): void
    {
        // Kumpulkan makanan yang terpengaruh
        $makananList = collect();

        if ($rule->makanan_id) {
            $makananList->push(Makanan::find($rule->makanan_id));
        } elseif ($rule->kategori) {
            Makanan::where('kategori', $rule->kategori)
                ->orWhere('sub_kategori', $rule->kategori)
                ->get()
                ->each(fn($makanan) => $makananList->push($makanan));
        }

        // Update metode_cocok per makanan
        $makananList->filter()->each(function ($makanan) {
            $metodeIds = Rule::where(function($q) use ($makanan) {
                $q->where('makanan_id', $makanan->id)
                  ->orWhere('kategori', $makanan->kategori)
                  ->orWhere('kategori', $makanan->sub_kategori);
            })
            ->pluck('metode_pengolahan_id')
            ->unique()
            ->values()
            ->toArray();

            $makanan->update(['metode_cocok' => $metodeIds]);
        });
    }
    /**
     * Handle the Rule "restored" event.
     */
    public function restored(Rule $rule): void
    {
        //
    }

    /**
     * Handle the Rule "force deleted" event.
     */
    public function forceDeleted(Rule $rule): void
    {
        //
    }
}
