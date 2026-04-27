<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23f97316' viewBox='0 0 16 16'%3E%3Cpath d='M13 .5c0-.276-.226-.506-.498-.465-1.703.257-2.94 2.012-3 8.462a.5.5 0 0 0 .498.5c.56.01 1 .13 1 1.003v5.5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5zM4.25 0a.25.25 0 0 1 .25.25v5.122a.128.128 0 0 0 .256.006l.233-5.14A.25.25 0 0 1 5.24 0h.522a.25.25 0 0 1 .25.238l.233 5.14a.128.128 0 0 0 .256-.006V.25A.25.25 0 0 1 6.75 0h.29a.5.5 0 0 1 .498.458l.423 5.07a1.69 1.69 0 0 1-1.059 1.711l-.053.022a.92.92 0 0 0-.58.884L6.47 15a.971.971 0 1 1-1.942 0l.202-6.855a.92.92 0 0 0-.58-.884l-.053-.022a1.69 1.69 0 0 1-1.059-1.712L3.462.458A.5.5 0 0 1 3.96 0z'/%3E%3C/svg%3E">
    <title>@yield('title', 'Sistem Pakar Nutrisi')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #f8f9fa 100%);
            min-height: 100vh;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5em;
            font-weight: bold;
            color: #000000;
            text-decoration: none;
        }

        .navbar-menu {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .navbar-menu a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar-menu a:hover,
        .navbar-menu a.active {
            color: #ff724c;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ffa500 0%, #ff7518 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .footer {
            background: white;
            margin-top: 50px;
            padding: 0px 0 20px 0;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .disclaimer-modal {
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

        .disclaimer-modal.active {
            display: flex;
        }

        .disclaimer-content {
            /* fff3cd */
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 75%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .disclaimer-content h3 {
            color: #000000;
            margin-bottom: 20px;
        }

        .disclaimer-content p {
            margin-bottom: 12px;
        }

        .disclaimer-content ol {
            margin-left: 25px;
            margin-top: 10px;
        }

        .disclaimer-content li {
            margin-bottom: 8px;
        }

        .footer-info {
            text-align: center;
            color: #666;
            font-size: 0.95em;
            padding-top: 25px;
        }

        .footer-info p {
            margin-bottom: 10px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: #ff724c;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .footer-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                gap: 15px;
            }

            .navbar-menu {
                flex-direction: column;
                gap: 10px;
                text-align: center;
                width: 100%;
            }

            .footer-links {
                flex-direction: column;
                gap: 12px;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="navbar-brand"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                    height="16" fill="currentColor" class="bi bi-fork-knife" viewBox="0 0 16 16">
                    <path
                        d="M13 .5c0-.276-.226-.506-.498-.465-1.703.257-2.94 2.012-3 8.462a.5.5 0 0 0 .498.5c.56.01 1 .13 1 1.003v5.5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5zM4.25 0a.25.25 0 0 1 .25.25v5.122a.128.128 0 0 0 .256.006l.233-5.14A.25.25 0 0 1 5.24 0h.522a.25.25 0 0 1 .25.238l.233 5.14a.128.128 0 0 0 .256-.006V.25A.25.25 0 0 1 6.75 0h.29a.5.5 0 0 1 .498.458l.423 5.07a1.69 1.69 0 0 1-1.059 1.711l-.053.022a.92.92 0 0 0-.58.884L6.47 15a.971.971 0 1 1-1.942 0l.202-6.855a.92.92 0 0 0-.58-.884l-.053-.022a1.69 1.69 0 0 1-1.059-1.712L3.462.458A.5.5 0 0 1 3.96 0z" />
                </svg> Knowfood</a>
            <ul class="navbar-menu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('makanan.index') }}"
                        class="{{ request()->routeIs('makanan.*') ? 'active' : '' }}">Makanan</a></li>
                <li><a href="{{ route('analisis.index') }}"
                        class="{{ request()->routeIs('analisis.*') ? 'active' : '' }}">Analisis</a></li>
                <li><a href="{{ route('informasi-gizi.index') }}"
                        class="{{ request()->routeIs('informasi-gizi.*') ? 'active' : '' }}">Info Gizi</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
    <!-- Footer with Disclaimer -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Disclaimer Section -->
            <div class="disclaimer-modal" id ="disclaimerModal">
                <div class="disclaimer-content">
                    <h3>Disclaimer</h3>
                    <p>
                        Sistem Pakar Analisis Nutrisi ini dibuat untuk tujuan <strong>informasi</strong>
                        semata.
                        Informasi yang disajikan bersumber dari data TKPI 2020, USDA FoodData Central, dan penelitian
                        ilmiah terpublikasi.
                    </p>
                    <p>
                        <strong>Catatan Penting:</strong>
                    </p>
                    <ol>
                        <li>Nilai nutrisi dapat bervariasi tergantung varietas, musim, dan metode pengolahan spesifik.
                        </li>
                        <li>Sistem ini bukan pengganti konsultasi dengan ahli gizi atau dokter
                            profesional.</li>
                        <li>Untuk kebutuhan diet khusus atau kondisi medis tertentu, harap konsultasikan dengan tenaga
                            kesehatan.</li>
                        <li>Pengembang tidak bertanggung jawab atas keputusan yang diambil berdasarkan informasi dari
                            sistem ini.</li>
                        <li>Hasil analisis bersifat estimasi berdasarkan data rata-rata dan aturan umum.</li>
                    </ol>
                    <p style="margin-top: 12px;">
                        Gunakan informasi ini sebagai panduan awal untuk memahami dampak metode pengolahan terhadap
                        nutrisi makanan.
                    </p>
                    <button onclick="closeDisclaimer()" class="btn btn-primary"
                        style="margin-top: 20px;">Mengerti</button>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="footer-info">
                <p><strong>Sistem Pakar Analisis Nutrisi Makanan Indonesia</strong></p>
                <p>Tugas Akhir - Jurusan Informatika</p>
                <p>2022130016 - Riko Gunawan</p>

                <div class="footer-links">
                    <a href="{{ route('home') }}">Beranda</a>
                    <a href="{{ route('makanan.index') }}">Daftar Makanan</a>
                    <a href="{{ route('analisis.index') }}">Analisis Nutrisi</a>
                    <a href="{{ route('informasi-gizi.index') }}">Informasi Gizi</a>
                    <a href="#" onclick="showDisclaimer(); return false;">Disclaimer</a>
                </div>
            </div>
        </div>
    </footer>
    @yield('scripts')
    <script>
        function showDisclaimer() {
            document.getElementById('disclaimerModal').classList.add('active');
        }

        function closeDisclaimer() {
            document.getElementById('disclaimerModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('disclaimerModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDisclaimer();
            }
        });
    </script>
</body>

</html>
