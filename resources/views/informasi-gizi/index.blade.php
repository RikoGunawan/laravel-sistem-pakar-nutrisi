@extends('layouts.app')

@section('title', 'Informasi Gizi')

@section('styles')
    <style>
        /* Hero/Header tanpa kotak card */
        .hero-section {
            text-align: center;
            padding: 60px 20px 40px;
            background: linear-gradient(rgba(0,0,0,0.2), rgba(0, 0, 0, 0.2)), url('{{ asset('images/Shrimp.jpg') }}') no-repeat center/cover;
            margin-bottom: 40px;
            border-radius: 24px;
            background-blend-mode: multiply;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 12px;
        }

        .hero-subtitle {
            font-size: 1.15rem;
            font-weight: 400;
            color: #ffffffe8;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Tabs */
        .filter-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 40px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-tab {
            padding: 10px 24px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
            font-weight: 600;
        }

        .filter-tab:hover,
        .filter-tab.active {
            background: #ff7518;
            color: white;
            border-color: #ff7518;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(255, 117, 24, 0.2);
        }

        /* Grid & Card */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
        }

        .info-card-link {
            text-decoration: none !important;
            color: inherit !important;
            display: block;
            outline: none;
        }

        .info-card-link:hover,
        .info-card-link:focus,
        .info-card-link:visited,
        .info-card-link:active {
            text-decoration: none !important;
            color: inherit !important;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        /* Space untuk gambar/thumbnail */
        .info-thumbnail {
            height: 180px;
            background: #fff3cd;
            /* kuning soft fallback */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #ffca28;
            overflow: hidden;
        }

        .info-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-body {
            padding: 20px 24px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .info-header {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
        }

        .info-icon {
            font-size: 2.8em;
            flex-shrink: 0;
        }

        .info-title {
            color: #ff7518;
            font-size: 1.4rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .info-category {
            display: inline-block;
            padding: 6px 14px;
            background: #fff3cd;
            color: #856404;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .info-content {
            color: #555;
            line-height: 1.65;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .info-footer {
            margin-top: auto;
        }

        .btn-read-more {
            width: 100%;
            background: #ff7518;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            text-align: center;
            transition: background 0.3s;
        }

        .btn-read-more:hover {
            background: #e66a00;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .filter-tabs {
                flex-direction: row;
                overflow-x: auto;
                padding-bottom: 10px;
            }

            .hero-section {
                padding: 40px 15px 30px;
            }

            .hero-title {
                font-size: 2rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Hero tanpa kotak card -->
    <div class="hero-section">
        <h1 class="hero-title">Informasi Gizi</h1>
        <p class="hero-subtitle">Tips dan fakta seputar gizi serta cara menjaga kesehatan melalui pola makan sehari-hari</p>
    </div>

    <div class="container">
        <div class="filter-tabs">
            <a href="{{ route('informasi-gizi.index') }}" class="filter-tab {{ !request('kategori') ? 'active' : '' }}">
                Semua
            </a>
            @foreach ($kategoris as $kat)
                <a href="{{ route('informasi-gizi.index', ['kategori' => $kat]) }}"
                    class="filter-tab {{ request('kategori') == $kat ? 'active' : '' }}">
                    {{ ucfirst($kat) }}
                </a>
            @endforeach
        </div>

        <div class="info-grid">
            @forelse($informasiList as $info)
                <a href="{{ route('informasi-gizi.show', $info->id) }}" class="info-card-link">
                    <div class="info-card">
                        <div class="info-thumbnail">
                            <img src="{{ $info->image ?? 'https://placehold.co/320x180?text=Knowfood' }}" alt="{{ $info->icon }}">
                        </div>

                        <div class="info-body">
                            <div class="info-header">
                                {{-- <span class="info-icon">{{ $info->icon }}</span> --}}
                                <h3 class="info-title">{{ $info->judul }}</h3>
                            </div>

                            <span class="info-category">{{ ucfirst($info->kategori) }}</span>

                            {{-- <p class="info-content">{{ Str::limit($info->konten, 100) }}</p> --}}
                            <p class="info-content">{{ Str::limit(strip_tags($info->konten), 100, '...') }}</p>
                            <div class="info-footer">
                                <div class="btn-read-more">
                                    Selengkapnya →
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-5 col-12">
                    <p class="text-muted fs-5">Tidak ada informasi tersedia untuk kategori ini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
