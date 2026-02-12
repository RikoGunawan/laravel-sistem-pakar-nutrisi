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

    /* FOOTNOTE SECTION */
    .footnote-section {
        background: #fff9e6;
        border: 2px solid #ffc107;
        border-radius: 12px;
        padding: 25px;
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .footnote-title {
        font-size: 1.1em;
        font-weight: 600;
        color: #856404;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .footnote-content {
        color: #856404;
        line-height: 1.6;
    }

    .footnote-content a {
        color: #667eea;
        text-decoration: underline;
        word-break: break-all;
    }

    .footnote-content a:hover {
        color: #764ba2;
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

        .footnote-section {
            padding: 15px;
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
                <span class="meta-item">
                    <span>📅</span>
                    <span>{{ $informasi->created_at->format('d M Y') }}</span>
                </span>
            </div>
        </div>

        <div class="content-box">
            {!! nl2br(e($informasi->konten)) !!}
        </div>

        @if($informasi->sumber)
            <div class="footnote-section">
                <div class="footnote-title">
                    <span>📚</span>
                    <span>Sumber Referensi</span>
                </div>
                <div class="footnote-content">
                    @php
                        // Check if sumber contains URL
                        $sources = explode(',', $informasi->sumber);
                    @endphp

                    @foreach($sources as $index => $source)
                        <p style="margin-bottom: 8px;">
                            [{{ $index + 1 }}]
                            @if(filter_var(trim($source), FILTER_VALIDATE_URL))
                                <a href="{{ trim($source) }}" target="_blank" rel="noopener">{{ trim($source) }}</a>
                            @else
                                {{ trim($source) }}
                            @endif
                        </p>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="back-btn-container">
            <a href="{{ route('informasi-gizi.index') }}" class="btn btn-secondary">← Kembali ke Daftar</a>
        </div>
    </div>
@endsection
