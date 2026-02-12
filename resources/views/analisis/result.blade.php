@extends('layouts.app')

@section('title', 'Hasil Analisis')

@section('styles')
    <style>
        .result-header {
            background: linear-gradient(135deg, #ff7518 0%, #ffca28 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
        }

        .result-header h1 {
            margin-bottom: 10px;
        }

        .comparison-table-wrapper {
            overflow-x: auto;
            margin-bottom: 30px;
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            min-width: 600px;
        }

        .comparison-table th {
            background: #2a2c41;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .comparison-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .comparison-table tr:hover {
            background: #f8f9fa;
        }

        .comparison-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 600;
            margin-left: 8px;
        }

        .badge-danger {
            background: #dc3545;
            color: white;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-warning {
            background: #ffc107;
            color: #333;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            padding: 25px;
            border-radius: 12px;
            text-align: center;
        }

        .summary-card.best {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 3px solid #28a745;
        }

        .summary-card.worst {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: 3px solid #dc3545;
        }

        .summary-card.neutral {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 3px solid #ffc107;
        }

        .summary-icon {
            font-size: 3em;
            margin-bottom: 10px;
        }

        .summary-title {
            font-size: 0.95em;
            color: #666;
            margin-bottom: 8px;
        }

        .summary-method {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .summary-desc {
            font-size: 0.9em;
            color: #666;
        }

        .recommendation-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .recommendation-box h3 {
            color: #155724;
            margin-bottom: 15px;
        }

        .recommendation-box ul {
            margin-left: 20px;
            line-height: 1.8;
            color: #155724;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .result-header {
                padding: 20px;
            }

            .result-header h1 {
                font-size: 1.5em;
            }

            .comparison-table th,
            .comparison-table td {
                padding: 10px;
                font-size: 0.9em;
            }

            .summary-grid {
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
        <div class="result-header">
            <h1>Hasil Analisis: {{ $analisis->makanan->name }}</h1>
            <p>Komparasi {{ count($analisis->analisisMetode) }} Metode Pengolahan</p>
        </div>

        <h2 style="color: #000000; margin-bottom: 20px;"> Tabel Komparasi Nutrisi</h2>

        <div class="comparison-table-wrapper">
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th>Nutrisi</th>
                        <th>🥩 Mentah</th>
                        @foreach ($analisis->analisisMetode as $am)
                            <th>{{ $am->metodePengolahan->icon }} {{ $am->metodePengolahan->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nutrisiLabels = [
                            'protein' => 'Protein (g)',
                            'lemak' => 'Lemak (g)',
                            'karbohidrat' => 'Karbohidrat (g)',
                            'kalori' => 'Kalori (kkal)',
                            'vitamin_c' => 'Vitamin C (mg)',
                            'vitamin_b_complex' => 'Vitamin B (avg)',
                        ];
                    @endphp

                    @foreach ($nutrisiLabels as $key => $label)
                        <tr>
                            <td><strong>{{ $label }}</strong></td>
                            <td>{{ number_format($analisis->nutrisi_mentah[$key], 2) }}</td>
                            @foreach ($analisis->analisisMetode as $am)
                                @php
                                    $nilai = $am->nutrisi_hasil[$key];
                                    $perubahan = $am->perubahan_persen[$key] ?? 0;
                                    $badgeClass =
                                        $perubahan > 0
                                            ? 'badge-danger'
                                            : ($perubahan < 0
                                                ? 'badge-warning'
                                                : 'badge-success');
                                    $icon = $perubahan > 0 ? '↑' : ($perubahan < 0 ? '↓' : '→');
                                @endphp
                                <td>
                                    {{ number_format($nilai, 2) }}
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $icon }} {{ $perubahan > 0 ? '+' : '' }}{{ $perubahan }}%
                                    </span>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h2 style="color: #000000; margin-bottom: 20px;"> Ringkasan Komparasi</h2>

        <div class="summary-grid">
            @php
                $metodeTerbaik = null;
                $metodeKaloriTinggi = null;
                $metodeLemakRendah = null;

                $minVitaminLoss = 100;
                $maxKalori = 0;
                $minLemak = PHP_INT_MAX;

                foreach ($analisis->analisisMetode as $am) {
                    $vitaminLoss = abs($am->perubahan_persen['vitamin_c'] ?? 0);
                    if ($vitaminLoss < $minVitaminLoss) {
                        $minVitaminLoss = $vitaminLoss;
                        $metodeTerbaik = $am->metodePengolahan;
                    }

                    if ($am->nutrisi_hasil['kalori'] > $maxKalori) {
                        $maxKalori = $am->nutrisi_hasil['kalori'];
                        $metodeKaloriTinggi = $am->metodePengolahan;
                    }

                    if ($am->nutrisi_hasil['lemak'] < $minLemak) {
                        $minLemak = $am->nutrisi_hasil['lemak'];
                        $metodeLemakRendah = $am->metodePengolahan;
                    }
                }
            @endphp

            <div class="summary-card best">
                <div class="summary-icon">🏆</div>
                <div class="summary-title">Terbaik untuk Nutrisi</div>
                <div class="summary-method">{{ $metodeTerbaik->icon }} {{ $metodeTerbaik->name }}</div>
                <div class="summary-desc">Kehilangan vitamin hanya {{ $minVitaminLoss }}%</div>
            </div>

            <div class="summary-card worst">
                <div class="summary-icon">⚠️</div>
                <div class="summary-title">Paling Tinggi Kalori</div>
                <div class="summary-method">{{ $metodeKaloriTinggi->icon }} {{ $metodeKaloriTinggi->name }}</div>
                <div class="summary-desc">Kalori: {{ number_format($maxKalori, 0) }} kkal</div>
            </div>

            <div class="summary-card neutral">
                <div class="summary-icon">💪</div>
                <div class="summary-title">Rendah Lemak</div>
                <div class="summary-method">{{ $metodeLemakRendah->icon }} {{ $metodeLemakRendah->name }}</div>
                <div class="summary-desc">Lemak: {{ number_format($minLemak, 2) }}g</div>
            </div>
        </div>

        @if ($analisis->rekomendasi->count() > 0)
            <div class="recommendation-box">
                <h3>✅ Rekomendasi Berdasarkan Tujuan</h3>
                <ul>
                    @foreach ($analisis->rekomendasi as $rek)
                        <li><strong>{{ ucwords(str_replace('_', ' ', $rek->jenis)) }}:</strong> {{ $rek->alasan }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="btn-group">
            <a href="{{ route('analisis.index') }}" class="btn btn-secondary">← Analisis Lagi</a>
            <a href="{{ route('analisis.trace', $analisis->id) }}" class="btn btn-primary">📋 Lihat Trace Penalaran</a>
        </div>
    </div>
@endsection
