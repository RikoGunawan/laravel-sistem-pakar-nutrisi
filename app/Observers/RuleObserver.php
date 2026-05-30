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

    public function sync(Rule $rule): void
{
    $makananList = collect();

    if ($rule->makanan_id) {
        $makananList->push(Makanan::find($rule->makanan_id));
    } elseif ($rule->kategori) {
        Makanan::where('kategori', $rule->kategori)
            ->orWhere('sub_kategori', $rule->kategori)
            ->get()
            ->each(fn($m) => $makananList->push($m));
    }

    $makananList->filter()->each(fn($makanan) => $this->updateMetodeCocok($makanan));
}

/**
 * Update metode_cocok untuk 1 makanan (logic yang sudah diperbaiki)
 */
private function updateMetodeCocok(Makanan $makanan): void
{
    $query = Rule::query();

    // Selalu tambahkan kondisi makanan_id jika ada
    if ($makanan->id) {
        $query->where('makanan_id', $makanan->id);
    }

    // Hanya tambahkan kondisi kategori jika nilainya TIDAK NULL
    $hasCondition = false;

    if (!empty($makanan->kategori)) {
        $query->orWhere('kategori', $makanan->kategori);
        $hasCondition = true;
    }

    if (!empty($makanan->sub_kategori)) {
        $query->orWhere('kategori', $makanan->sub_kategori);
        $hasCondition = true;
    }

    // Jika tidak ada kondisi sama sekali → kasih null
    if (!$hasCondition) {
        $makanan->update(['metode_cocok' => null]);
        return;
    }

    $rules = $query->get();

    if ($rules->isEmpty()) {
        $makanan->update(['metode_cocok' => null]);
    } else {
        $metodeIds = $rules->pluck('metode_pengolahan_id')
                           ->unique()
                           ->sort()
                           ->values()
                           ->toArray();

        $makanan->update(['metode_cocok' => $metodeIds]);
    }
}

public function syncAll(): void
{
    echo "🔄 Mulai re-sync semua makanan (versi aman NULL)...\n";

    $makananList = Makanan::all();
    $total = $makananList->count();
    $processed = 0;

    foreach ($makananList as $makanan) {
        $this->updateMetodeCocok($makanan);

        $processed++;
        if ($processed % 15 === 0 || $processed === $total) {
            echo "  Progress: {$processed}/{$total} - {$makanan->name}\n";
        }
    }

    echo "✅ Re-sync selesai!\n";
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
