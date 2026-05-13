@extends('layouts.app')

@section('title', 'Home - Sistem Pakar Nutrisi')

@section('styles')
    <style>
        .hero-section {
            position: relative;
            overflow: hidden;
            height: 100vh;
            margin-top: -70px;
            margin-left: calc(-50vw + 50%);
            margin-right: calc(-50vw + 50%);
            margin-bottom: 60px;
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('{{ asset('images/ella-olsson-oPBjWBCcAEo-unsplash.jpg') }}') no-repeat center center;
            background-size: cover;
            transform: scale(2.2);
            transform-origin: 30% 40%;
        }

        .home-content {
            padding: 40px 0 90px 0;
        }

        .main-heading {
            text-align: center;
            margin-bottom: 70px;
            z-index: 1;
        }

        .main-heading h1 {
            font-size: 2.75rem;
            line-height: 1.15;
            color: #1f2937;
            margin-bottom: 18px;
        }

        .main-heading p {
            font-size: 1.25rem;
            color: #4b5563;
            max-width: 720px;
            margin: 0 auto;
        }

        .btn-start {
            background: linear-gradient(135deg, #ffa500 0%, #ff724c 100%);
            color: white;
            padding: 15px 42px;
            border-radius: 9999px;
            font-size: 1.15rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-top: 25px;
            box-shadow: 0 8px 20px rgba(255, 114, 76, 0.3);
            transition: all 0.3s;
        }

        .btn-start:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(255, 114, 76, 0.4);
        }

        .info-title {
            text-align: center;
            font-size: 2.2rem;
            color: #1f2937;
            margin-bottom: 40px;
            position: relative;
        }

        .info-title:after {
            content: '';
            width: 70px;
            height: 4px;
            background: linear-gradient(135deg, #ffa500, #ff724c);
            display: block;
            margin: 12px auto 0;
            border-radius: 2px;
        }

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .info-item {
            background: white;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
            border-left: 6px solid #ff724c;
            transition: all 0.3s;
        }

        .info-item:hover {
            border-left-color: #ffa500;
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .info-item h3 {
            font-size: 1.4rem;
            margin-bottom: 14px;
            color: #1f2937;
        }

        .info-item p {
            color: #4b5563;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .read-more {
            color: #ff724c;
            font-weight: 600;
            text-decoration: none;
        }

        .read-more:hover {
            color: #e65c2b;
        }
    </style>

@endsection

@section('content')

    <div class="home-content">

        <div class="hero-section">
            <div class="main-heading">
                <h1>Masak Apa yang Paling Sehat?</h1>
                <p>Cari tahu bagaimana cara pengolahan mengubah nutrisi makanan Indonesia</p>
                <a href="{{ route('analisis.index') }}" class="btn-start">
                    Cek Nutrisi Makananmu
                </a>
            </div>
        </div>

        <!-- Informasi Gizi -->
        <h2 class="info-title">Informasi Gizi Terkini</h2>

        <div class="info-list">
            @forelse($informasiGizi as $info)
                <div class="info-item">
                    <h3>{{ $info->judul }}</h3>
                    <p>{{ Str::limit(strip_tags($info->konten ?? ''), 190) }}</p>
                    <a href="{{ route('informasi-gizi.show', $info->id) }}" class="read-more">
                        Baca Selengkapnya →
                    </a>
                </div>
            @empty
                <p style="text-align:center; padding:40px; background:white; border-radius:12px;">
                    Belum ada artikel informasi gizi.
                </p>
            @endforelse
        </div>

    </div>
@endsection
