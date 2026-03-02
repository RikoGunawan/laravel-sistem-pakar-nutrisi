@extends('layouts.app')

@section('title', $informasi->judul)

@section('styles')
    <style>
        .detail-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
        }

        .detail-icon {
            font-size: 5em;
            margin-bottom: 15px;
        }

        .detail-title {
            font-size: 2.5em;
            color: #000;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .detail-meta {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 20px;
            font-size: 0.95em;
            color: #666;
            font-weight: 500;
        }

        .content-box {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 12px;
            border-left: 4px solid #ffca28;
            line-height: 1.8;
            font-size: 1.1em;
            color: #333;
            margin-bottom: 30px;
        }

        /* FOOTNOTE SECTION */
        .footnote-section {
            padding: 25px;
            margin-top: 30px;
            border-top: 2px solid #e0e0e0;
        }

        .footnote-title {
            font-size: 1.1em;
            font-weight: 600;
            color: #000;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footnote-content {
            color: #000;
            line-height: 1.6;
        }

        .footnote-content a {
            color: #000;
            text-decoration: none;
            word-break: break-all;
        }

        .footnote-content a:hover {
            color: #ffca28;
        }

        .back-btn-container {
            text-align: center;
            margin-top: 30px;
        }

        /* ── Perbaikan gambar kiri + border putih aman ── */
        .main-card-content {
            display: flex;
            gap: 30px;
            align-items: stretch;
            min-height: 500px;
            /* sesuaikan kalau konten pendek */
        }

        .left-image-wrapper {
            flex: 0 0 40%;
            position: relative;
            /* Hapus overflow: hidden di sini biar border kelihatan */
        }

        .side-image-frame {
            height: 100%;
            /* ikut tinggi full konten */
            border: 12px solid white;
            /* tebalin sedikit biar lebih kelihatan */
            border-radius: 16px;
            /* sedikit lebih rounded biar bagus */
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            background: white;
        }

        .side-image-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .right-main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Mobile tetap sama */
        @media (max-width: 992px) {
            .main-card-content {
                flex-direction: column;
                gap: 25px;
                align-items: center;
                min-height: auto;
            }

            .left-image-wrapper {
                flex: 0 0 auto;
                width: 100%;
                max-width: 500px;
                height: 380px;
                /* atau sesuaikan */
            }

            .side-image-frame {
                height: 100%;
                border: 10px solid white;
                /* sedikit lebih tipis di mobile */
                border-radius: 14px;
            }
        }

        @media (max-width: 992px) {
            .main-card-content {
                flex-direction: column;
                gap: 25px;
            }

            .left-image-wrapper {
                flex: 0 0 auto;
                position: static;
                max-width: 380px;
                margin: 0 auto;
            }

            .side-image-frame {
                max-width: 360px;
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
            .detail-icon {
                font-size: 3.5em;
            }

            .detail-title {
                font-size: 1.8em;
            }

            .content-box {
                padding: 20px;
                font-size: 1em;
            }

            .detail-meta {
                flex-direction: column;
                align-items: center;
            }

            .meta-item {
                width: 100%;
                justify-content: center;
            }

            .footnote-section {
                padding: 15px;
            }

            .left-image-wrapper {
                max-width: 320px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="main-card-content">
            <div class="left-image-wrapper">
                <div class="side-image-frame">
                    <img src="{{ $informasi->image ?? 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=600&auto=format&fit=crop' }}"
                        loading="lazy">
                </div>
            </div>

            <div class="right-main-content">
                <div class="detail-header">
                    <div class="detail-icon">{{ $informasi->icon }}</div>
                    <h1 class="detail-title">{{ $informasi->judul }}</h1>

                    <div class="detail-meta">
                        <span class="meta-item">
                            <span>{{ ucfirst($informasi->kategori) }}</span>
                        </span>
                        <span class="meta-item">
                            <span>📅</span>
                            <span>{{ $informasi->created_at->format('d M Y') }}</span>
                        </span>
                    </div>
                </div>

                <div class="content-box">
                    {!! nl2br($informasi->konten) !!}
                </div>
            </div>
        </div>

        <!-- Bagian bawah tetap full width seperti semula -->
        <div class="back-btn-container">
            <a href="{{ route('informasi-gizi.index') }}" class="btn btn-secondary">← Kembali ke Daftar</a>
        </div>

        @if ($informasi->sumber)
            <div class="footnote-section">
                <div class="footnote-title">
                    <span>Sumber Referensi</span>
                </div>
                <div class="footnote-content">
                    @php
                        $sources = explode(',', $informasi->sumber);
                    @endphp

                    @foreach ($sources as $index => $source)
                        <p style="margin-bottom: 8px;">
                            [{{ $index + 1 }}]
                            @if (filter_var(trim($source), FILTER_VALIDATE_URL))
                                <a href="{{ trim($source) }}" target="_blank" rel="noopener">{{ trim($source) }}</a>
                            @else
                                {{ trim($source) }}
                            @endif
                        </p>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
