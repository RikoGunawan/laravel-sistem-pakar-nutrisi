
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
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }

    .food-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: all 0.3s;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    .food-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }

    /* IMAGE CONTAINER */
    .food-image-container {
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: #f8f9fa;
        position: relative;
    }

    .food-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .food-card:hover .food-image {
        transform: scale(1.1);
    }

    .food-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5em;
        background: linear-gradient(135deg, #ffa500 0%, #ff7518 100%);
    }

    /* CARD CONTENT */
    .food-card-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
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
        background: #ff7518;
        color: white;
        border-radius: 15px;
        font-size: 0.85em;
        margin-bottom: 12px;
        align-self: flex-start;
    }

    .food-nutrisi {
        font-size: 0.9em;
        color: #666;
        margin-top: auto;
        padding-top: 10px;
        border-top: 1px solid #e0e0e0;
    }

    .food-nutrisi-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    @media (max-width: 768px) {
        .food-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .food-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
    <div class="card">
        <h1 style="color: #000000; margin-bottom: 20px;"> Daftar Makanan</h1>

        <form action="{{ route('makanan.index') }}" method="GET">
            <div class="search-bar">
                <input type="text" name="search" class="search-input" placeholder="Cari makanan..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
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
                <div class="food-image-container">
                    @if($makanan->image && filter_var($makanan->image, FILTER_VALIDATE_URL))
                        <img src="{{ $makanan->image }}" alt="{{ $makanan->name }}" class="food-image">
                    @elseif($makanan->image && strlen($makanan->image) > 10)
                        {{-- Jika image adalah path lokal --}}
                        <img src="{{ asset('storage/' . $makanan->image) }}" alt="{{ $makanan->name }}" class="food-image">
                    @else
                        {{-- Fallback ke emoji --}}
                        <div class="food-image-placeholder">{{ $makanan->image ?: '🍽️' }}</div>
                    @endif
                </div>

                <div class="food-card-content">
                    <h3 class="food-name">{{ $makanan->name }}</h3>
                    <span class="food-category">{{ $makanan->kategori }}</span>

                    <div class="food-nutrisi">
                        <div class="food-nutrisi-item">
                            <span><strong>Protein</strong></span>
                            <span>{{ $makanan->protein }}g</span>
                        </div>
                        <div class="food-nutrisi-item">
                            <span><strong>Kalori</strong></span>
                            <span>{{ $makanan->kalori }} kkal</span>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="card" style="grid-column: 1/-1;">
                <p style="text-align: center; color: #666;">Tidak ada makanan ditemukan.</p>
            </div>
        @endforelse
    </div>
@endsection
