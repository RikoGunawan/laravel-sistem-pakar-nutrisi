<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Makanan;

class MakananController extends Controller
{
    /**
     * Display a listing of makanan
     */
    public function index(Request $request)
    {
        $query = Makanan::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->byKategori($request->kategori);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'protein_high':
                    $query->orderBy('protein', 'desc');
                    break;
                case 'kalori_low':
                    $query->orderBy('kalori', 'asc');
                    break;
                case 'kalori_high':
                    $query->orderBy('kalori', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            $query->orderBy('name', 'asc');
        }

        $makananList = $query->get();

        // Get unique categories for filter
        $kategoris = Makanan::distinct()->pluck('kategori');

        return view('makanan.index', compact('makananList', 'kategoris'));
    }

    /**
     * Display the specified makanan
     */
    public function show($id)
    {
        $makanan = Makanan::findOrFail($id);
        
        return view('makanan.show', compact('makanan'));
    }
}
