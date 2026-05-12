@extends('layouts.app')

@section('title', $makanan->name . ' - Detail Makanan')

@section('styles')
    <style>
        .detail-page {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .detail-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .detail-name {
            font-size: 2.2rem;
            font-weight: 700;
            color: #ff724c;
            margin-bottom: 10px;
        }

        .detail-category {
            display: inline-block;
            padding: 6px 18px;
            background: #ff724c;
            color: white;
            border-radius: 50px;
            font-size: 0.9rem;
        }

        .detail-description {
            text-align: center;
            color: #666;
            font-size: 1.05rem;
            line-height: 1.7;
            max-width: 700px;
            margin: 20px auto;
        }

        .detail-container {
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            gap: 45px;
            align-items: start;
        }

        /* Gambar Section */
        .detail-image-container {
            width: 100%;
            max-height: 380px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .detail-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Tabel Nutrisi */
        .nutrisi-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .nutrisi-table th {
            background: #ff724c;
            color: white;
            padding: 16px 20px;
            text-align: left;
        }

        .nutrisi-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }

        .nutrisi-table tr:last-child td {
            border-bottom: none;
        }

        .nutrisi-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .footnote-section {
            background: #fff4e7;
            border: 2px solid #ff724c;
            border-radius: 12px;
            padding: 22px;
            margin-top: 35px;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .detail-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .detail-image-container {
                max-height: 320px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="detail-page">

        <!-- Header: Judul, Kategori, Deskripsi -->
        <div class="detail-header">
            <h1 class="detail-name">{{ $makanan->name }}</h1>
            <span class="detail-category">{{ $makanan->kategori }}</span>

            @if ($makanan->description)
                <p class="detail-description">{{ $makanan->description }}</p>
            @endif
        </div>

        <!-- Layout Dua Kolom -->
        <div class="detail-container">

            <!-- KIRI: Gambar -->
            <div>
                <div class="detail-image-container">
                    @if ($makanan->image && filter_var($makanan->image, FILTER_VALIDATE_URL))
                        <img src="{{ $makanan->image }}" alt="{{ $makanan->name }}" class="detail-image">
                    @elseif($makanan->image)
                        <img src="{{ asset('storage/' . $makanan->image) }}" alt="{{ $makanan->name }}"
                            class="detail-image">
                    @else
                        <div
                            style="width:100%; height:380px; background: linear-gradient(135deg, #ff724c, #ff9f6b);
                                display:flex; align-items:center; justify-content:center; font-size:8rem; color:white;">
                            🍽️
                        </div>
                    @endif
                </div>

                <!-- Tombol -->
                <div class="btn-group mt-4">
                    <a href="{{ route('makanan.index') }}" class="btn btn-secondary">← Kembali</a>
                    <a href="{{ route('analisis.index') }}?makanan={{ $makanan->id }}" class="btn btn-primary">Analisis
                        Makanan Ini →</a>
                </div>
            </div>

            <!-- KANAN: Tabel Nutrisi Lengkap -->
            <div>
                <h3 style="color: #ff724c; margin-bottom: 20px;">Nutrisi per 100 gram</h3>

                <table class="nutrisi-table">
                    <thead>
                        <tr>
                            <th>Komponen Nutrisi</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Protein</strong></td>
                            <td>{{ cleanDecimal($makanan->protein) }} g</td>
                        </tr>
                        <tr>
                            <td><strong>Lemak</strong></td>
                            <td>{{ cleanDecimal($makanan->lemak) }} g</td>
                        </tr>
                        <tr>
                            <td><strong>Karbohidrat</strong></td>
                            <td>{{ cleanDecimal($makanan->karbohidrat) }} g</td>
                        </tr>
                        <tr>
                            <td><strong>Kalori</strong></td>
                            <td>{{ cleanDecimal($makanan->kalori) }} kkal</td>
                        </tr>
                        <tr>
                            <td>Vitamin A (Retinol)</td>
                            <td>{{ cleanDecimal($makanan->vitamin_a) }} mcg</td>
                        </tr>
                        <tr>
                            <td>Beta Karoten</td>
                            <td>{{ cleanDecimal($makanan->beta_karoten) }} mcg</td>
                        </tr>
                        <tr>
                            <td>Vitamin B1</td>
                            <td>{{ cleanDecimal($makanan->vitamin_b1) }} mg</td>
                        </tr>
                        <tr>
                            <td>Vitamin B2</td>
                            <td>{{ cleanDecimal($makanan->vitamin_b2) }} mg</td>
                        </tr>
                        <tr>
                            <td>Vitamin B3</td>
                            <td>{{ cleanDecimal($makanan->vitamin_b3) }} mg</td>
                        </tr>
                        <tr>
                            <td>Vitamin C</td>
                            <td>{{ cleanDecimal($makanan->vitamin_c) }} mg</td>
                        </tr>
                        {{-- <tr><td>Vitamin B6</td><td>{{ $makanan->vitamin_b6 ?? '-' }} mg</td></tr> --}}
                        {{-- <tr><td>Vitamin B12</td><td>{{ $makanan->vitamin_b12 ?? '-' }} mcg</td></tr> --}}
                        {{-- <tr><td>Natrium</td><td>{{ $makanan->natrium ?? '-' }} mg</td></tr> --}}
                    </tbody>
                </table>

                <!-- Sumber Data -->
                <div class="footnote-section">
                    <h5 style="color:#ff724c; margin-bottom:12px;">Sumber Data</h5>
                    <p><strong>{{ $makanan->sumber_data ?? 'Database Nutrisi' }}</strong></p>
                    <p style="margin-top:10px; color:#555;">
                        Nilai di atas merupakan rata-rata per 100 gram bahan makanan mentah/segar.
                        Nilai aktual dapat bervariasi tergantung varietas, musim, dan kondisi penyimpanan.
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection
