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

        /* .comparison-table td:not(:first-child) { text-align: right; } */

        .comparison-table tr:hover {
            background: #f8f9fa;
        }

        .comparison-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
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


        /* SUMMARY */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .summary-card {
            position: relative;
            overflow: hidden;
            padding: 1.75rem;
            border-radius: 1rem;
            text-align: center;
            background: var(--bs-body-bg);
            /* putih di light, gelap di dark */
            border: 1px solid var(--bs-border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease, transform 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .summary-card::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: 0.4;
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
        }

        .summary-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.5rem;
            height: 3.5rem;
            margin-bottom: 1rem;
            border-radius: 50%;
            font-size: 1.5rem;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

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

        .summary-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--bs-secondary-color);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-method {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--bs-heading-color);
        }

        .summary-desc {
            font-size: 0.95rem;
            color: var(--bs-body-color);
            line-height: 1.5;
        }

        /* RECOMMENDATION */
        .recommendation-box {
            background: var(--bs-success-bg-subtle);
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
            position: absolute;
            left: 0;
            color: var(--bs-success);
        }

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

        .btn-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .help-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.help-modal.active {
    display: flex;
}

.help-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
}

.help-content h3 {
    color: #000000;
    margin-bottom: 20px;
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

        <h2 style="color: #000000; margin-bottom: 20px;"> Tabel Komparasi Nutrisi per 100 g
            <a href="#" onclick="document.getElementById('badgeModal').classList.add('active'); return false;"
        style="font-size: 0.75em; color: #ff7518; font-weight: 500; margin-left: 10px;">
        <i class="bi bi-info-circle"></i> Cara membaca badge?
    </a>
        </h2>

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
                            'vitamin_a' => 'Vitamin A (Retinol)(µg)',
                            'beta_karoten' => 'Beta-Karoten (µg)',
                            'vitamin_b1' => 'Vitamin B1 (Thiamin)(mg)',
                            'vitamin_b2' => 'Vitamin B2 (Riboflavin)(mg)',
                            'vitamin_b3' => 'Vitamin B3 (Niacin)(mg)',
                            'vitamin_c' => 'Vitamin C (mg)',
                        ];

                        $nutrisiLowerIsBetter = ['lemak', 'kalori', 'karbohidrat'];

                        //BADGE THRESHOLD
                        $thresholdSignifikan = 20;

                    @endphp

                    @foreach ($nutrisiLabels as $key => $label)
                        <tr>
                            <td><strong>{{ $label }}</strong></td>
                            <td>{{ cleanDecimal($analisis->nutrisi_mentah[$key]) }}</td>

                            @foreach ($analisis->analisisMetode as $am)
                                @php
                                    $nilai = $am->nutrisi_hasil[$key];
                                    $perubahan = $am->perubahan_persen[$key] ?? null;
                                    $isLowerBetter = in_array($key, $nutrisiLowerIsBetter);

                                    if ($perubahan === null) {
                                        $badgeClass = 'badge-warning';
                                        $icon = '!';
                                        $labelPersen = '';
                                    } elseif ($perubahan == 0) {
                                        $badgeClass = 'badge-success';
                                        $icon = '→';
                                        $labelPersen = '0%';
                                    } elseif ($perubahan > 0) {
                                        $icon = '↑';
                                        $labelPersen = '+' . round($perubahan) . '%';
                                        if ($isLowerBetter) {
                                            $badgeClass =
                                                abs($perubahan) <= $thresholdSignifikan
                                                    ? 'badge-warning'
                                                    : 'badge-danger';
                                        } else {
                                            $badgeClass = 'badge-success';
                                        }
                                    } else {
                                        $icon = '↓';
                                        $labelPersen = round($perubahan) . '%';
                                        if ($isLowerBetter) {
                                            $badgeClass = 'badge-success';
                                        } else {
                                            $badgeClass =
                                                abs($perubahan) <= $thresholdSignifikan
                                                    ? 'badge-warning'
                                                    : 'badge-danger';
                                        }
                                    }
                                @endphp

                                <td>
                                    {{ cleanDecimal($nilai, $key) }}
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $icon }} {{ abs($perubahan ?? 0) < 10000 ? $labelPersen : '' }}
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
            <div class="summary-card best">
                <div class="summary-icon"><i class="bi bi-award"></i></div>
                <div class="summary-title">Terbaik Mempertahankan Vitamin</div>
                <div class="summary-method">{{ implode(', ', $ringkasan['metodeTerbaik']) ?: '-' }}</div>
                <div class="summary-desc">Total vitamin: {{ number_format($ringkasan['maxVitamin'], 2) }}</div>
            </div>

            <div class="summary-card worst">
                <div class="summary-icon"><i class="bi bi-exclamation-lg"></i></div>
                <div class="summary-title">Kalori Tertinggi</div>
                <div class="summary-method">{{ implode(', ', $ringkasan['metodeKaloriTinggi']) ?: '-' }}</div>
                <div class="summary-desc">Kalori: {{ number_format($ringkasan['maxKalori'], 0) }} kkal</div>
            </div>

            <div class="summary-card neutral">
                <div class="summary-icon"><i class="bi bi-droplet text-muted me-1"></i></div>
                <div class="summary-title">Rendah Lemak</div>
                <div class="summary-method">{{ implode(', ', $ringkasan['metodeLemakRendah']) ?: '-' }}</div>
                <div class="summary-desc">Lemak: {{ number_format($ringkasan['minLemak'], 2) }}g</div>
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

        {{-- ==================== SUMBER REFERENSI PER METODE ==================== --}}
        <div class="footnote-section mt-8">
            <div class="footnote-title">
                <span>Sumber Referensi</span>
            </div>
            <div class="footnote-content space-y-8">

                @foreach ($analisis->analisisMetode as $am)
                    @php
                        $metodeName = $am->metodePengolahan->name ?? 'Metode';
                        $rule = $am->rule;
                        $sources = collect();

                        if ($rule && $rule->sumber_referensi) {
                            // UPDATE: Mengganti koma (,) menjadi titik koma (;)
                            $list = preg_split('/[\r\n;]+/', $rule->sumber_referensi);
                            foreach ($list as $item) {
                                $trimmed = trim($item);
                                if ($trimmed !== '') {
                                    $sources->push($trimmed);
                                }
                            }
                        }
                    @endphp

                    <div class="border-l-4 border-orange-400 pl-5">
                        <h4 class="font-semibold text-lg text-gray-800 mb-3">
                            {{ $metodeName }}
                        </h4>

                        <div class="space-y-3 pl-2">
                            {{-- [1] Makronutrien & Kalori --}}
                            <p>
                                [1] Perubahan Nilai Makronutrien & Kalori →
                                @if ($sources->isNotEmpty())
                                    @if (filter_var($sources[0], FILTER_VALIDATE_URL))
                                        <a href="{{ $sources[0] }}" target="_blank" rel="noopener noreferrer"
                                            class="text-blue-600 hover:underline">
                                            {{ $sources[0] }}
                                        </a>
                                    @else
                                        {{ $sources[0] }}
                                    @endif
                                @else
                                @endif
                            </p>

                            {{-- [2] Semua Vitamin --}}
                            <p>
                                [2] Perubahan Nilai Semua Vitamin →
                                @if ($sources->count() >= 2)
                                    @if (filter_var($sources[1], FILTER_VALIDATE_URL))
                                        <a href="{{ $sources[1] }}" target="_blank" rel="noopener noreferrer"
                                            class="text-blue-600 hover:underline">
                                            {{ $sources[1] }}
                                        </a>
                                    @else
                                        {{ $sources[1] }}
                                    @endif
                                @else
                                @endif
                            </p>

                            {{-- [3] Penjelasan --}}
                            <p>
                                [3] Penjelasan (Kok Bisa?) →
                                @if ($sources->count() >= 3)
                                    @if (filter_var($sources[2], FILTER_VALIDATE_URL))
                                        <a href="{{ $sources[2] }}" target="_blank" rel="noopener noreferrer"
                                            class="text-blue-600 hover:underline">
                                            {{ $sources[2] }}
                                        </a>
                                    @else
                                        {{ $sources[2] }}
                                    @endif
                                @else
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach

                @if ($analisis->analisisMetode->isEmpty())
                    <p class="text-gray-500 italic">Tidak ada sumber referensi yang tersedia untuk analisis ini.</p>
                @endif
            <br>
                <span>Note: Sumber referensi dari Bognar digunakan untuk metode masak yang spesial 
                    terutama tidak berkaitan dengan penambahan bahan lain seperti tepung, minyak, dll.
                     Untuk metode masak yang berkaitan dengan minyak seperti fry, deep-fry, 
                     goreng tepung, dan sejenisnya menggunakan sumber dari data asli makanan sesudah 
                     diolah agar lebih realistis.</span>
            </div>
        </div>
    </div>
    <!-- Modal Penjelasan Badge -->
<div class="help-modal" id="badgeModal">
    <div class="help-content">
        <h3>Panduan Membaca Badge Perubahan Nutrisi</h3>

        <div style="display: flex; flex-direction: column; gap: 16px; margin-top: 10px;">

            {{-- WARNA --}}
            <div style="border-left: 4px solid #059669; padding: 12px 16px; background: #f8f9fa; border-radius: 0 8px 8px 0;">
                <div style="font-weight: 700; font-size: 1em; margin-bottom: 6px;">
                    <span class="badge badge-success">↑↓ 10%</span> Hijau (Perubahan Baik)
                </div>
                <div style="color: #555; font-size: 0.95em; line-height: 1.6;">
                    Nutrisi yang <strong>lebih tinggi = lebih baik</strong> (protein, vitamin) mengalami kenaikan.<br>
                    Atau nutrisi yang <strong>lebih rendah = lebih baik</strong> (lemak, kalori, karbohidrat) mengalami penurunan.
                </div>
            </div>

            <div style="border-left: 4px solid #f59e0b; padding: 12px 16px; background: #f8f9fa; border-radius: 0 8px 8px 0;">
                <div style="font-weight: 700; font-size: 1em; margin-bottom: 6px;">
                    <span class="badge badge-warning">↑↓ 15%</span> Kuning (Perubahan Ringan) 
                </div>
                <div style="color: #555; font-size: 0.95em; line-height: 1.6;">
                    Perubahan ke arah yang kurang baik namun masih 20% ke bawah. Perlu diperhatikan tapi tidak kritis.
                </div>
            </div>

            <div style="border-left: 4px solid #dc2626; padding: 12px 16px; background: #f8f9fa; border-radius: 0 8px 8px 0;">
                <div style="font-weight: 700; font-size: 1em; margin-bottom: 6px;">
                    <span class="badge badge-danger">↑↓ 30%</span> Merah (Perubahan Signifikan)
                </div>
                <div style="color: #555; font-size: 0.95em; line-height: 1.6;">
                    Perubahan ke arah yang kurang baik dan melebihi 20%. Misalnya lemak atau kalori naik drastis, atau vitamin turun drastis.
                </div>
            </div>

            {{-- ARAH --}}
            <div style="border-left: 4px solid #2a2c41; padding: 12px 16px; background: #f8f9fa; border-radius: 0 8px 8px 0;">
                <div style="font-weight: 700; font-size: 1em; margin-bottom: 6px;">Arti Simbol Arah</div>
                <div style="color: #555; font-size: 0.95em; line-height: 1.8;">
                    <strong>↑</strong> — Nilai naik dibanding mentah<br>
                    <strong>↓</strong> — Nilai turun dibanding mentah<br>
                    <strong>→</strong> — Tidak ada perubahan (0%)<br>
                    <strong>!</strong> — Data perubahan tidak tersedia
                </div>
            </div>

            <div style="padding: 12px 16px; background: #f8f9fa; border-radius: 0 8px 8px 0;">
                <div style="font-weight: 700; font-size: 1em; margin-bottom: 6px;">
                    Contoh
                    <span class="badge badge-danger">↑ +30%</span>
                </div>
                <div style="color: #555; font-size: 0.95em; line-height: 1.6;">
                    artinya nilai nutrisi naik 30% dari nilai mentah, kurang baik karena nutisi lemak yang naik.
                </div>
            </div>
            {{-- NUTRISI LOWER IS BETTER --}}
            <div style="border-left: 4px solid #ff7518; padding: 12px 16px; background: #f8f9fa; border-radius: 0 8px 8px 0;">
                <div style="font-weight: 700; font-size: 1em; margin-bottom: 6px;">Catatan Penting</div>
                <div style="color: #555; font-size: 0.95em; line-height: 1.6;">
                    Tidak semua kenaikan itu buruk. <strong>Protein dan vitamin</strong> yang naik itu bagus (hijau). 
                    Sebaliknya, <strong>lemak, kalori, dan karbohidrat</strong> yang turun itu bagus (hijau).
                </div>
            </div>

        </div>

        <button onclick="document.getElementById('badgeModal').classList.remove('active')" 
            class="btn btn-primary" style="margin-top: 20px;">Mengerti</button>
    </div>
</div>
@endsection
