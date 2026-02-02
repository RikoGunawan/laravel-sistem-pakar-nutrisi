@extends('layouts.app')

@section('title', 'Daftar Makanan')

@section('styles')
<style>
    .search-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .search-input {
        flex: 1;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1em;
    }

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .filter-grid {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-grid label {
        font-weight: 600;
        color: #333;
    }

    .filter-grid select {
        padding: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 1em;
    }

    .food-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .food-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: all 0.3s;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .food-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .food-icon {
        font-size: 3em;
        margin-bottom: 15px;
    }

    .food-name {
        font-weight: 600;
        font-size: 1.2em;
        color: #333;
        margin-bottom: 8px;
    }

    .food-category {
        display: inline-block;
        padding: 5px 15px;
        background: #667eea;
        color: white;
        border-radius: 15px;
        font-size: 0.9em;
        margin-bottom: 10px;
    }

    .food-nutrisi {
        font-size: 0.9em;
        color: #666;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
    <div class="card">
        <h1 style="color: #667eea; margin-bottom: 20px;">🍽️ Daftar Makanan</h1>

        <form action="{{ route('makanan.index') }}" method="GET">
            <div class="search-bar">
                <input type="text" name="search" class="search-input" placeholder="Cari makanan..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">🔍 Cari</button>
            </div>

            <div class="filter-section">
                <div class="filter-grid">
                    <label>Filter Kategori:</label>
                    <select name="kategori" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                        @endforeach
                    </select>

                    <label>Urutkan:</label>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                        <option value="protein_high" {{ request('sort') == 'protein_high' ? 'selected' : '' }}>Protein Tertinggi</option>
                        <option value="kalori_low" {{ request('sort') == 'kalori_low' ? 'selected' : '' }}>Kalori Terendah</option>
                        <option value="kalori_high" {{ request('sort') == 'kalori_high' ? 'selected' : '' }}>Kalori Tertinggi</option>
                    </select>

                    @if(request('search') || request('kategori') || request('sort'))
                        <a href="{{ route('makanan.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="food-grid">
        @forelse($makananList as $makanan)
            <a href="{{ route('makanan.show', $makanan->id) }}" class="food-card">
                <div class="food-icon">{{ $makanan->image }}</div>
                <div class="food-name">{{ $makanan->name }}</div>
                <div class="food-category">{{ $makanan->kategori }}</div>
                <div class="food-nutrisi">
                    <strong>Protein:</strong> {{ $makanan->protein }}g<br>
                    <strong>Kalori:</strong> {{ $makanan->kalori }} kkal
                </div>
            </a>
        @empty
            <div class="card" style="grid-column: 1/-1;">
                <p style="text-align: center; color: #666;">Tidak ada makanan ditemukan.</p>
            </div>
        @endforelse
    </div>
@endsection