@extends('layouts.app')

@section('title', 'Informasi Gizi')

@section('styles')
    <style>
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-tab {
            padding: 10px 20px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: #333;
            font-weight: 600;
        }

        .filter-tab:hover,
        .filter-tab.active {
            background: #ff7518;
            color: white;
            border-color: #ff7518;
            transform: translateY(-2px);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .info-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .info-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .info-header {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 15px;
        }

        .info-icon {
            font-size: 2.5em;
            flex-shrink: 0;
        }

        .info-title {
            color: #ff7518;
            font-size: 1.3em;
            font-weight: 600;
            line-height: 1.3;
        }

        .info-category {
            display: inline-block;
            padding: 5px 12px;
            background: #ffc107;
            color: #333;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .info-content {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .info-source {
            font-size: 0.85em;
            color: #999;
            font-style: italic;
            margin-bottom: 15px;
        }

        .info-footer {
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .filter-tabs {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-tab {
                text-align: center;
            }

            .info-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <h1 style="color: #000000; margin-bottom: 15px;">Informasi Gizi</h1>
        <p style="color: #666; margin-bottom: 25px;">Tips dan fakta seputar gizi dan kesehatan</p>

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
    </div>

    <div class="info-grid">
        @forelse($informasiList as $info)
            <a href="{{ route('informasi-gizi.show', $info->id) }}" class="info-card-link">
                <div class="info-card">
                    <div class="info-header">
                        <span class="info-icon">{{ $info->icon }}</span>
                        <h3 class="info-title">{{ $info->judul }}</h3>
                    </div>

                    <span class="info-category">{{ ucfirst($info->kategori) }}</span>

                    <p class="info-content">{{ Str::limit($info->konten, 150) }}</p>

                    @if ($info->sumber)
                        <p class="info-source">📖 Sumber: {{ $info->sumber }}</p>
                    @endif

                    <div class="info-footer">
                        <span class="btn btn-primary" style="width: 100%;">
                            Baca Selengkapnya →
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="card" style="grid-column: 1/-1;">
                <p style="text-align: center; color: #666; padding: 40px;">
                    Tidak ada informasi tersedia untuk kategori ini.
                </p>
            </div>
        @endforelse
    </div>
@endsection
