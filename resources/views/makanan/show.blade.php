@extends('layouts.app')

@section('title', $makanan->name . ' - Detail Makanan')

@section('styles')
<style>
    .detail-header {
        text-align: center;
        margin-bottom: 30px;
    }

    /* IMAGE SECTION */
    .detail-image-container {
        width: 300px;
        height: 300px;
        margin: 0 auto 20px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .detail-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .detail-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8em;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .detail-name {
        font-size: 2.5em;
        color: #667eea;
        margin-bottom: 10px;
    }

    .detail-category {
        display: inline-block;
        padding: 8px 20px;
        background: #667eea;
        color: white;
        border-radius: 20px;
        font-size: 1.1em;
    }

    .detail-description {
        text-align: center;
        color: #666;
        margin: 20px 0;
        font-style: italic;
    }

    .nutrisi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .nutrisi-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .nutrisi-label {
        color: #666;
        font-size: 0.9em;
        margin-bottom: 5px;
    }

    .nutrisi-value {
        font-size: 1.8em;
        font-weight: bold;
        color: #333;
    }

    .nutrisi-unit {
        font-size: 0.8em;
        color: #666;
    }

    /* FOOTNOTE SECTION */
    .footnote-section {
        background: #e7f3ff;
        border: 2px solid #667eea;
        border-radius: 12px;
        padding: 20px;
        margin-top: 30px;
        margin-bottom: 20px;
    }

    .footnote-title {
        font-size: 1em;
        font-weight: 600;
        color: #667eea;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footnote-content {
        color: #555;
        font-size: 0.9em;
        line-height: 1.6;
    }

    .btn-group {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .detail-image-container {
            width: 200px;
            height: 200px;
        }

        .detail-name {
            font-size: 1.8em;
        }

        .nutrisi-grid {
            grid-template-columns: 1fr;
        }

        .btn-group {
            flex-direction: column;
        }

        .btn-group .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
    <div class="card">
        <div class="detail-header">
            <div class="detail-image-container">
                @if($makanan->image && filter_var($makanan->image, FILTER_VALIDATE_URL))
                    <img src="{{ $makanan->image }}" alt="{{ $makanan->name }}" class="detail-image">
                @elseif($makanan->image && strlen($makanan->image) > 10)
                    <img src="{{ asset('storage/' . $makanan->image) }}" alt="{{ $makanan->name }}" class="detail-image">
                @else
                    <div class="detail-image-placeholder">{{ $makanan->image ?: '🍽️' }}</div>
                @endif
            </div>

            <h1 class="detail-name">{{ $makanan->name }}</h1>
            <span class="detail-category">{{ $makanan->kategori }}</span>
        </div>

        @if($makanan->description)
            <p class="detail-description">{{ $makanan->description }}</p>
        @endif

        <h2 style="color: #667eea; margin: 30px 0 20px 0;">📊 Nutrisi per 100 gram (Mentah)</h2>

        <div class="nutrisi-grid">
            <div class="nutrisi-card">
                <div class="nutrisi-label">Protein</div>
                <div class="nutrisi-value">{{ $makanan->protein }} <span class="nutrisi-unit">g</span></div>
            </div>
            <div class="nutrisi-card">
                <div class="nutrisi-label">Lemak</div>
                <div class="nutrisi-value">{{ $makanan->lemak }} <span class="nutrisi-unit">g</span></div>
            </div>
            <div class="nutrisi-card">
                <div class="nutrisi-label">Karbohidrat</div>
                <div class="nutrisi-value">{{ $makanan->karbohidrat }} <span class="nutrisi-unit">g</span></div>
            </div>
            <div class="nutrisi-card">
                <div class="nutrisi-label">Kalori</div>
                <div class="nutrisi-value">{{ $makanan->kalori }} <span class="nutrisi-unit">kkal</span></div>
            </div>
            <div class="nutrisi-card">
                <div class="nutrisi-label">Vitamin C</div>
                <div class="nutrisi-value">{{ $makanan->vitamin_c }} <span class="nutrisi-unit">mg</span></div>
            </div>
            <div class="nutrisi-card">
                <div class="nutrisi-label">Serat</div>
                <div class="nutrisi-value">{{ $makanan->serat }} <span class="nutrisi-unit">g</span></div>
            </div>
        </div>

        <div class="footnote-section">
            <div class="footnote-title">
                <span>ℹ️</span>
                <span>Sumber Data</span>
            </div>
            <div class="footnote-content">
                <p><strong>{{ $makanan->sumber_data }}</strong></p>
                <p style="margin-top: 8px;">
                    Data nutrisi bersumber dari {{ $makanan->sumber_data }} dan merupakan nilai rata-rata per 100 gram bahan makanan dalam keadaan mentah/segar.
                    Nilai aktual dapat bervariasi tergantung varietas, musim, dan kondisi penyimpanan.
                </p>
            </div>
        </div>

        <div class="btn-group">
            <a href="{{ route('makanan.index') }}" class="btn btn-secondary">← Kembali</a>
            <a href="{{ route('analisis.index') }}?makanan={{ $makanan->id }}" class="btn btn-primary">Analisis Makanan Ini →</a>
        </div>
    </div>
@endsection
