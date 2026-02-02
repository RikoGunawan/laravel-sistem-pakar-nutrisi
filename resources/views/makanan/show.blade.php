@extends('layouts.app')

@section('title', $makanan->name . ' - Detail Makanan')

@section('styles')
<style>
    .detail-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .detail-icon {
        font-size: 5em;
        margin-bottom: 15px;
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

    .btn-group {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }
</style>
@endsection

@section('content')
    <div class="card">
        <div class="detail-header">
            <div class="detail-icon">{{ $makanan->image }}</div>
            <h1 class="detail-name">{{ $makanan->name }}</h1>
            <span class="detail-category">{{ $makanan->kategori }}</span>
        </div>

        @if($makanan->description)
            <p style="text-align: center; color: #666; margin: 20px 0;">{{ $makanan->description }}</p>
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

        <div class="btn-group">
            <a href="{{ route('makanan.index') }}" class="btn btn-secondary">← Kembali</a>
            <a href="{{ route('analisis.index') }}?makanan={{ $makanan->id }}" class="btn btn-primary">Analisis Makanan Ini →</a>
        </div>
    </div>
@endsection