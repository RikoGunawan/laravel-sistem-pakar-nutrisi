@extends('layouts.app')

@section('title', 'Home - Sistem Pakar Nutrisi')

@section('styles')
<style>
    .hero {
        background: white;
        border-radius: 12px;
        padding: 60px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        margin-bottom: 40px;
    }

    .hero h1 {
        font-size: 2.5em;
        color: #000000;
        margin-bottom: 20px;
    }

    .hero p {
        font-size: 1.2em;
        color: #666;
        margin-bottom: 30px;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .stat-icon {
        font-size: 3em;
        margin-bottom: 15px;
    }

    .stat-number {
        font-size: 2em;
        font-weight: bold;
        color: #ff724c;
        margin-bottom: 10px;
    }

    .stat-label {
        color: #666;
        font-size: 1.1em;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .info-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .info-card h3 {
        color: #ff724c;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-card p {
        color: #666;
        line-height: 1.6;
    }
</style>
@endsection

@section('content')
    <div class="hero">
        <h1>Sistem Pakar Analisis Nutrisi Makanan</h1>
        <p>Ketahui bagaimana metode pengolahan mengubah kandungan nutrisi makanan Indonesia</p>
        <a href="{{ route('analisis.index') }}" class="btn btn-primary">Mulai Analisis</a>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_makanan'] }}</div>
            <div class="stat-label">Jenis Makanan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_kategori'] }}</div>
            <div class="stat-label">Kategori</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">5</div>
            <div class="stat-label">Metode Pengolahan</div>
        </div>
    </div>

    <h2 style="text-align: center; margin-bottom: 30px;">Informasi Gizi Terkini</h2>

    <div class="info-grid">
        @foreach($informasiGizi as $info)
        <div class="info-card">
            <h3>{{ $info->icon }} {{ $info->judul }}</h3>
            <p>{{ Str::limit($info->konten, 150) }}</p>
            <a href="{{ route('informasi-gizi.show', $info->id) }}" class="btn btn-primary" style="margin-top: 15px;">Baca Selengkapnya</a>
        </div>
        @endforeach
    </div>
@endsection
