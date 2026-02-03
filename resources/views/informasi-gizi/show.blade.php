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
        color: #667eea;
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
        border-left: 4px solid #667eea;
        line-height: 1.8;
        font-size: 1.1em;
        color: #333;
        margin-bottom: 30px;
    }

    .back-btn-container {
        text-align: center;
        margin-top: 30px;
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
    }
</style>
@endsection

@section('content')
    <div class="card">
        <div class="detail-header">
            <div class="detail-icon">{{ $informasi->icon }}</div>
            <h1 class="detail-title">{{ $informasi->judul }}</h1>

            <div class="detail-meta">
                <span class="meta-item">
                    <span>📂</span>
                    <span>{{ ucfirst($informasi->kategori) }}</span>
                </span>
                @if($informasi->sumber)
                    <span class="meta-item">
                        <span>📖</span>
                        <span>{{ $informasi->sumber }}</span>
                    </span>
                @endif
                <span class="meta-item">
                    <span>📅</span>
                    <span>{{ $informasi->created_at->format('d M Y') }}</span>
                </span>
            </div>
        </div>

        <div class="content-box">
            {!! nl2br(e($informasi->konten)) !!}
        </div>

        <div class="back-btn-container">
            <a href="{{ route('informasi-gizi.index') }}" class="btn btn-secondary">← Kembali ke Daftar</a>
        </div>
    </div>
@endsection
