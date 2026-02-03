@extends('layouts.app')

@section('title', 'Trace Penalaran')

@section('styles')
<style>
    .trace-header {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        text-align: center;
    }

    .trace-step {
        background: white;
        padding: 25px;
        border-radius: 12px;
        border-left: 4px solid #667eea;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .trace-step-number {
        display: inline-block;
        background: #667eea;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        text-align: center;
        line-height: 35px;
        font-weight: bold;
        margin-right: 10px;
    }

    .trace-step h3 {
        color: #667eea;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .trace-content {
        margin-left: 45px;
        line-height: 1.8;
        color: #666;
    }

    .trace-label {
        font-weight: 600;
        color: #333;
        margin-top: 10px;
    }

    .trace-code {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        font-family: monospace;
        margin-top: 10px;
        overflow-x: auto;
    }

    @media (max-width: 768px) {
        .trace-header {
            padding: 20px;
        }

        .trace-step {
            padding: 15px;
        }

        .trace-content {
            margin-left: 0;
        }
    }
</style>
@endsection

@section('content')
    <div class="card">
        <div class="trace-header">
            <h1>📋 Trace Penalaran Forward Chaining</h1>
            <p>Makanan: {{ $analisis->makanan->name }}</p>
        </div>

        <p style="color: #666; margin-bottom: 30px; line-height: 1.6;">
            <strong>Forward Chaining</strong> adalah metode inferensi yang dimulai dari fakta yang diketahui
            (nutrisi mentah) dan menerapkan aturan (rules) untuk menghasilkan kesimpulan baru (nutrisi setelah diolah).
        </p>

        @foreach($analisis->tracePenalaran as $trace)
            <div class="trace-step">
                <h3>
                    <span class="trace-step-number">{{ $trace->step_order }}</span>
                    Step {{ $trace->step_order }}
                </h3>
                <div class="trace-content">
                    <div class="trace-label">📍 Fakta Awal:</div>
                    <div class="trace-code">{{ $trace->fakta_awal }}</div>

                    <div class="trace-label">📜 Rule Digunakan:</div>
                    <div class="trace-code">{{ $trace->rule_used }}</div>

                    <div class="trace-label">⚙️ Proses:</div>
                    <div class="trace-code">{{ $trace->proses }}</div>

                    <div class="trace-label">✨ Fakta Baru (Hasil):</div>
                    <div class="trace-code">{{ $trace->fakta_baru }}</div>
                </div>
            </div>
        @endforeach

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('analisis.result', $analisis->id) }}" class="btn btn-primary">← Kembali ke Hasil</a>
        </div>
    </div>
@endsection
