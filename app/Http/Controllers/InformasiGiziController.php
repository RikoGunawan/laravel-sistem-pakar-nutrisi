<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InformasiGizi;

class InformasiGiziController extends Controller
{
    /**
     * Display a listing of informasi gizi
     */
    public function index(Request $request)
    {
        $query = InformasiGizi::published();

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->byKategori($request->kategori);
        }

        $informasiList = $query->get();

        // Get unique categories
        $kategoris = InformasiGizi::published()
            ->distinct()
            ->pluck('kategori');

        return view('informasi-gizi.index', compact('informasiList', 'kategoris'));
    }

    /**
     * Display the specified informasi gizi
     */
    public function show($id)
    {
        $informasi = InformasiGizi::published()->findOrFail($id);

        return view('informasi-gizi.show', compact('informasi'));
    }
}
