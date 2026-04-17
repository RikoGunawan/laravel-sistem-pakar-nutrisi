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
            /* padding: 4px 10px;
                border-radius: 12px;
                font-size: 0.85em;
                font-weight: 600; */
            margin-left: 8px;
            border-radius: 9999px;
            padding: 0.35em 0.75em;
            font-weight: 500;
            font-size: 0.825rem;
        }

        .badge-success {
            background: #059669 !important;
            color: white !important;
        }

        .badge-warning {
            background: #f59e0b !important;
            color: white !important;
        }

        .badge-danger {
            background: #dc2626 !important;
            color: white !important;
        }


        /* Grid untuk card summary - responsif */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            /* lebih lebar biar nyaman */
            gap: 1.5rem;
            /* 24px */
            margin-bottom: 2.5rem;
        }

        /* Card utama - base style modern */
        .summary-card {
            position: relative;
            overflow: hidden;
            padding: 1.75rem;
            /* 28px */
            border-radius: 1rem;
            /* rounded-4 */
            text-align: center;
            background: var(--bs-body-bg);
            /* putih di light, gelap di dark */
            border: 1px solid var(--bs-border-color);
            /* border tipis netral */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            /* shadow-sm modern */
            transition: all 0.3s ease, transform 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-6px);
            /* efek lift subtle */
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            /* shadow-lg on hover */
        }

        /* Gradient overlay tipis (pakai pseudo-element biar mudah override) */
        .summary-card::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: 0.4;
            /* sangat tipis */
            transition: opacity 0.3s ease;
        }

        .summary-card.best::before {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.08), rgba(60, 200, 90, 0.05));
        }

        .summary-card.worst::before {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.08), rgba(240, 80, 90, 0.05));
        }

        .summary-card.neutral::before {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.08), rgba(255, 210, 50, 0.05));
        }

        .summary-card:hover::before {
            opacity: 0.6;
            /* sedikit lebih terlihat saat hover */
        }

        /* Icon di card - kecil & di lingkaran */
        .summary-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.5rem;
            height: 3.5rem;
            margin-bottom: 1rem;
            border-radius: 50%;
            font-size: 1.5rem;
            /* lebih kecil dari 3em */
            background: rgba(255, 255, 255, 0.8);
            /* semi-transparan */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Warna icon sesuai variant */
        .summary-card.best .summary-icon {
            background: #d4edda;
            color: #155724;
        }

        .summary-card.worst .summary-icon {
            background: #f8d7da;
            color: #721c24;
        }

        .summary-card.neutral .summary-icon {
            background: #fff3cd;
            color: #856404;
        }

        /* Title & desc lebih clean */
        .summary-title {
            font-size: 0.875rem;
            /* text-sm */
            font-weight: 500;
            color: var(--bs-secondary-color);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-method {
            font-size: 1.75rem;
            /* lebih besar tapi tidak berlebihan */
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--bs-heading-color);
        }

        .summary-desc {
            font-size: 0.95rem;
            color: var(--bs-body-color);
            line-height: 1.5;
        }

        /* Recommendation box - lebih modern */
        .recommendation-box {
            background: var(--bs-success-bg-subtle);
            /* #d1e7dd di Bootstrap 5 */
            border: 1px solid var(--bs-success-border-subtle);
            border-radius: 0.75rem;
            padding: 1.75rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .recommendation-box h3 {
            color: var(--bs-success-text-emphasis);
            font-size: 1.25rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .recommendation-box h3::before {
            content: "\F26F";
            /* Bootstrap Icons: check-circle-fill, atau pakai <i class="bi bi-check-circle-fill"></i> di HTML */
            font-family: "bootstrap-icons";
            font-size: 1.4rem;
        }

        .recommendation-box ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .recommendation-box li {
            margin-bottom: 0.75rem;
            color: var(--bs-success-text-emphasis);
            position: relative;
            padding-left: 1.75rem;
        }

        .recommendation-box li::before {
            content: "\2713";
            /* checkmark sederhana, atau pakai Bootstrap icon */
            position: absolute;
            left: 0;
            color: var(--bs-success);
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

@php
    function cleanDecimal($value)
    {
        return $value !== null ? rtrim(rtrim(number_format($value, 4, '.', ''), '0'), '.') : '-';
    }
@endphp

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
                        <th>Mentah</th>
                        @foreach ($analisis->analisisMetode as $am)
                            <th>{{ $am->metodePengolahan->name }}</th>
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
                            'vitamin_a' => 'Vitamin A (mcg)',
                            'beta_karoten' => 'Beta Karoten (mcg)',
                            'vitamin_b1' => 'Vitamin B1 (mg)',
                            'vitamin_b2' => 'Vitamin B2 (mg)',
                            'vitamin_b3' => 'Vitamin B3 (mg)',
                            'vitamin_c' => 'Vitamin C (mg)',
                        ];
                    @endphp

                    @foreach ($nutrisiLabels as $key => $label)
                        <tr>
                            <td><strong>{{ $label }}</strong></td>
                            <td>{{ cleanDecimal($analisis->nutrisi_mentah[$key]) }}</td>

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
                <div class="summary-icon"><i class="bi bi-award"></i></div>
                <div class="summary-title">Terbaik Mempertahankan Nutrisi</div>
                <div class="summary-method">{{ $metodeTerbaik->name }}</div>
                <div class="summary-desc">Kehilangan vitamin hanya {{ $minVitaminLoss }}%</div>
            </div>

            <div class="summary-card worst">
                <div class="summary-icon"><i class="bi bi-exclamation-lg"></i></div>
                <div class="summary-title">Kalori Tertinggi</div>
                <div class="summary-method">{{ $metodeKaloriTinggi->name }}</div>
                <div class="summary-desc">Kalori: {{ number_format($maxKalori, 0) }} kkal</div>
            </div>

            <div class="summary-card neutral">
                <div class="summary-icon"><i class="bi bi-droplet text-muted me-1"></i></div>
                <div class="summary-title">Rendah Lemak</div>
                <div class="summary-method">{{ $metodeLemakRendah->name }}</div>
                <div class="summary-desc">Lemak: {{ number_format($minLemak, 2) }}g</div>
            </div>
        </div>

        @if ($analisis->rekomendasi->count() > 0)
            <div class="recommendation-box">
                <h3>Rekomendasi Berdasarkan Tujuan</h3>
                <ul>
                    @foreach ($analisis->rekomendasi as $rek)
                        <li><strong>{{ ucwords(str_replace('_', ' ', $rek->jenis)) }}:</strong> {{ $rek->alasan }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="btn-group">
            <a href="{{ route('analisis.index') }}" class="btn btn-secondary">← Analisis Lagi</a>
            <a href="{{ route('analisis.trace', $analisis->id) }}" class="btn btn-primary">Lihat Trace Penalaran</a>
        </div>
    </div>
@endsection
