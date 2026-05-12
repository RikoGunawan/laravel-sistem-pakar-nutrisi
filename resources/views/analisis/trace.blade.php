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
            border-left: 4px solid #ff7518;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .trace-step h3 {
            color: #ff7518;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ff7518;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            font-weight: bold;
            flex-shrink: 0;
            font-size: 15px;
        }

        .trace-content {
            margin-left: 45px;
            line-height: 1.8;
            color: #555;
        }

        .trace-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }

        .rule-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
            border-bottom: 1px solid #f0f0f0;
            font-family: monospace;
            font-size: 13px;
        }

        .rule-row:last-child {
            border-bottom: none;
        }

        .badge {
            font-size: 11px;
            padding: 2px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .badge-match {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-skip {
            background: #f3f4f6;
            color: #6b7280;
        }

        .badge-active {
            background: #dbeafe;
            color: #1e40af;
        }

        .nutrisi-baru {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 6px;
        }

        .nutrisi-chip {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 13px;
            font-family: monospace;
        }

        .metode-divider {
            text-align: center;
            font-weight: 600;
            color: #aaa;
            font-size: 13px;
            margin: 30px 0 20px;
            letter-spacing: .05em;
            text-transform: uppercase;
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
            <h1>Trace Penalaran Forward Chaining</h1>
            <p>Makanan: {{ $analisis->makanan->name }}</p>
        </div>

        <p style="color:#666; margin-bottom:30px; line-height:1.6;">
            <strong>Forward Chaining</strong> adalah metode inferensi yang dimulai dari fakta yang diketahui
            (nutrisi mentah) dan menerapkan aturan (rules) untuk menghasilkan kesimpulan baru (nutrisi setelah diolah).
        </p>

        @php
            // Kelompokkan analisisMetode per metode, sudah include rule
            $metodeList = $analisis->analisisMetode;
            $nutrisiMentah = $analisis->nutrisi_mentah;
        @endphp

        @foreach ($metodeList as $index => $am)
            @php
                $metode = $am->metodePengolahan;
                $rule = $am->rule;
                $nutrisiHasil = $am->nutrisi_hasil;
                $perubahan = $am->perubahan_persen ?? [];

                // Semua rule yang dievaluasi untuk metode ini

                $rulesEvaluasi = collect($am->rules_dievaluasi ?? [])->map(
                    fn($r) => [
                        'kode' => $r['kode_rule'],
                        'applied' => !isset($r['dilewati']),
                    ],
                );

                if ($rulesEvaluasi->isEmpty() && $rule) {
                    $rulesEvaluasi->push(['kode' => $rule->kode_rule, 'applied' => true]);
                }

                $nutrisiTampil = collect($nutrisiHasil)->filter(fn($v) => $v !== null && $v > 0)->take(6);
            @endphp

            @if ($index > 0)
                <div class="metode-divider">— metode berikutnya —</div>
            @endif

            {{-- LANGKAH 1: Fakta Awal --}}
            <div class="trace-step">
                <h3>
                    <span class="step-number">1</span>
                    Fakta awal — {{ $metode->name }}
                </h3>
                <div class="trace-content">
                    <div class="trace-label">Makanan</div>
                    <div>{{ $analisis->makanan->name }}
                        @if ($analisis->makanan->kategori)
                            &nbsp;·&nbsp; {{ $analisis->makanan->kategori }} &nbsp;·&nbsp;
                            {{ $analisis->makanan->sub_kategori }}
                        @endif
                    </div>
                    <div class="trace-label" style="margin-top:10px;">Metode pengolahan</div>
                    <div>{{ $metode->name }}</div>
                    <div class="trace-label" style="margin-top:10px;">Nutrisi mentah (kondisi awal)</div>
                    <div class="nutrisi-baru">
                        @foreach (collect($nutrisiMentah)->filter(fn($v) => $v > 0) as $key => $val)
                            <div class="nutrisi-chip">{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $val }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- LANGKAH 2: Pencocokan Rule --}}
            <div class="trace-step">
                <h3>
                    <span class="step-number">2</span>
                    Pencocokan rule — {{ $metode->name }}
                </h3>
                <div class="trace-content">
                    <div class="trace-label">Rule yang dievaluasi</div>
                    @foreach ($rulesEvaluasi as $r)
                        <div class="rule-row">
                            <span>{{ $r['kode'] }}</span>
                            @if ($r['applied'])
                                <span class="badge badge-match">MATCH — diterapkan</span>
                            @else
                                <span class="badge badge-skip">MATCH — kalah prioritas, diabaikan</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- LANGKAH 3: Rule Diaktifkan --}}
            <div class="trace-step">
                <h3>
                    <span class="step-number">3</span>
                    Rule diaktifkan — {{ $rule?->kode_rule ?? '-' }}
                </h3>
                <div class="trace-content">
                    @if ($rule)
                        <div class="trace-label">Kode rule</div>
                        <div><span class="badge badge-match">{{ $rule->kode_rule }}</span></div>

                        @if ($rule->penjelasan)
                            <div class="trace-label" style="margin-top:10px;">Penjelasan</div>
                            <div>{{ $rule->penjelasan }}</div>
                        @endif

                        @if (!empty($perubahan))
                            <div class="trace-label" style="margin-top:10px;">Perubahan nutrisi</div>
                            <div class="nutrisi-baru">
                                @foreach ($perubahan as $key => $pct)
                                    <div class="nutrisi-chip">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}:
                                        <strong style="color: #16a34a;">
                                            {{ $pct > 0 ? '+' : '' }}{{ $pct }}%
                                        </strong>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- LANGKAH 4: Fakta Baru --}}
            <div class="trace-step">
                <h3>
                    <span class="step-number">4</span>
                    Fakta baru — hasil setelah {{ $metode->name }}
                </h3>
                <div class="trace-content">
                    <div class="trace-label">Nilai nutrisi setelah diolah</div>
                    <div class="nutrisi-baru">
                        @foreach ($nutrisiTampil as $key => $val)
                            <div class="nutrisi-chip">
                                {{ ucfirst(str_replace('_', ' ', $key)) }}:
                                <strong>{{ round($val, 3) }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        @endforeach

        <div style="text-align:center; margin-top:30px;">
            <a href="{{ route('analisis.result', $analisis->id) }}" class="btn btn-primary">← Kembali ke Hasil</a>
        </div>
    </div>
@endsection
