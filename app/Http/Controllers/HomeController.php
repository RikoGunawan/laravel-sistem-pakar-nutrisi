<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InformasiGizi;
use App\Models\Makanan;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured informasi gizi
        $informasiGizi = InformasiGizi::published()
            ->take(3)
            ->get();

        // Get statistics
        $stats = [
            'total_makanan' => Makanan::count(),
            'total_kategori' => Makanan::distinct('kategori')->count('kategori'),
        ];

        return view('home', compact('informasiGizi', 'stats'));
    }
}
