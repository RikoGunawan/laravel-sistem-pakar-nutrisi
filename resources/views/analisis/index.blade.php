@extends('layouts.app')

@section('title', 'Analisis Nutrisi Makanan')

@section('styles')
    <style>
        .help-btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #ffa500 0%, #ff7518 100%);
            /* ffc107 */
            color: #ffffff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .help-btn:hover {
            /* background: #e0a800; */
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 117, 24, 0.4);
        }

        .form-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
            font-size: 1.1em;
        }

        .form-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            background: white;
        }

        .method-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .method-card {
            background: white;
            border: 3px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .method-card:hover {
            border-color: #ff7518;
            transform: scale(1.05);
        }

        .method-card.selected {
            border-color: #ff7518;
            background: #f0f3ff;
        }

        .method-card.selected::after {
            content: '✓';
            position: absolute;
            top: 5px;
            right: 10px;
            background: #28a745;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .method-icon {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .method-name {
            font-weight: 600;
            color: #333;
        }

        .method-checkbox {
            display: none;
        }

        .selected-methods {
            margin-top: 20px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            border: 2px solid #ff7518;
        }

        .selected-methods h4 {
            color: #ff7518;
            margin-bottom: 15px;
        }

        .selected-method-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .selected-method-name {
            font-weight: 600;
            color: #333;
        }

        .remove-method-btn {
            padding: 5px 12px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .analyze-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #ff7518 0%, #ffca28 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.2em;
            font-weight: 600;
            cursor: pointer;
            margin-top: 25px;
            transition: all 0.3s;
        }

        .analyze-btn:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .analyze-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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

        .help-content ol {
            margin-left: 20px;
            line-height: 1.8;
        }

        @media (max-width: 768px) {
            .method-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-section {
                padding: 15px;
            }

            .selected-method-item {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .method-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <h1 style="color: #000000; margin-bottom: 15px;">Analisis Nutrisi Makanan</h1>
        <p style="color: #666; margin-bottom: 25px;">Bandingkan perubahan nutrisi berdasarkan metode pengolahan yang berbeda
        </p>

        <a href="#" class="help-btn" onclick="showHelp(); return false;"><i class="bi bi-question-lg"></i> Tutorial Cara
            Analisis</a>

        <form action="{{ route('analisis.analyze') }}" method="POST" id="analisisForm">
            @csrf

            <div class="form-section">
                <div class="form-group">
                    <label class="form-label">1. Pilih Makanan</label>
                    <select name="makanan_id" id="makananSelect" class="form-select" required>
                        <option value="">-- Pilih Makanan --</option>
                        @foreach ($makananList as $makanan)
                            <option value="{{ $makanan->id }}"
                                {{ old('makanan', request('makanan')) == $makanan->id ? 'selected' : '' }}
                                data-metode-cocok="{{ json_encode($makanan->metode_cocok ?? []) }}"
                                data-catatan="{{ $makanan->catatan_pengolahan ?? '' }}">
                                {{ $makanan->name }} ({{ $makanan->kategori }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">2. Pilih Metode Pengolahan (Minimal 1, bisa lebih)
                        <a href="#" onclick="showMetodeInfo(); return false;"
                            style="font-size: 0.9em; color: #ff7518; font-weight: 500;">
                            <i class="bi bi-info-circle"></i> Apa itu masing-masing metode?
                        </a>
                    </label>
                    <div class="method-grid">
                        @foreach ($metodeList as $metode)
                            <label for="metode_{{ $metode->id }}" class="method-card"
                                data-metode-id="{{ $metode->id }}">
                                <input type="checkbox" name="metode_ids[]" value="{{ $metode->id }}"
                                    id="metode_{{ $metode->id }}" class="method-checkbox">
                                <div class="method-icon">{{ $metode->icon }}</div>
                                <div class="method-name">{{ $metode->name }}</div>
                            </label>
                        @endforeach
                    </div>

                    <div id="catatanBox"
                        style="display: none; background: #fff3cd; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #ffc107;">
                        <strong>Catatan:</strong>
                        <p id="catatanText" style="margin-top: 8px; color: #856404;"></p>
                    </div>

                    <div class="selected-methods" id="selectedMethods" style="display: none;">
                        <h4>✓ Metode Terpilih:</h4>
                        <div id="selectedMethodsList"></div>
                    </div>
                </div>

                <button type="submit" class="analyze-btn" id="analyzeBtn" disabled>
                    Analisis & Bandingkan
                </button>
            </div>
        </form>
    </div>

    <!-- Help Modal -->
    <div class="help-modal" id="helpModal">
        <div class="help-content">
            <h3>Cara Melakukan Analisis Nutrisi</h3>
            <ol>
                <li><strong>Pilih Makanan</strong> yang ingin Anda analisis dari dropdown.</li>
                <li><strong>Pilih Metode Pengolahan</strong> dengan klik card metode. Anda bisa pilih lebih dari satu untuk
                    membandingkan.</li>
                <li>Metode yang terpilih akan ditandai dengan ✓ hijau.</li>
                <li>Klik tombol <strong>"Analisis & Bandingkan"</strong> untuk melihat hasil.</li>
                <li>Anda akan melihat tabel komparasi nutrisi antar metode.</li>
                <li>Sistem akan memberikan rekomendasi metode terbaik.</li>
            </ol>
            <button onclick="closeHelp()" class="btn btn-primary" style="margin-top: 20px;">Mengerti</button>
        </div>
    </div>
    <!-- Modal Info Metode -->
    <div class="help-modal" id="metodeModal">
        <div class="help-content">
            <h3>Penjelasan Metode Pengolahan</h3>
            <div style="display: flex; flex-direction: column; gap: 16px; margin-top: 10px;">
                @foreach ($metodeList as $metode)
                    @php
                        $parts = explode(';', $metode->description ?? '');
                        $subtitle = trim($parts[0] ?? '');
                        $deskripsi = trim($parts[1] ?? '');
                    @endphp
                    <div
                        style="border-left: 4px solid #ff7518; padding: 12px 16px; background: #f8f9fa; border-radius: 0 8px 8px 0;">
                        <div style="font-weight: 700; font-size: 1em; margin-bottom: 4px;">
                            {{ $metode->name }}{{ $subtitle ? ' / ' . $subtitle : '' }}
                        </div>
                        <div style="color: #555; font-size: 0.95em; line-height: 1.6;">
                            {{ $deskripsi ?: 'Belum ada deskripsi.' }}
                        </div>
                    </div>
                @endforeach
                <div> Catatan: Jika metode pengolahan tidak bisa diklik maka data untuk metode pengolahan pada makanan
                    tersebut belum tersedia.
                </div>
            </div>
            <button onclick="closeMetodeInfo()" class="btn btn-primary" style="margin-top: 20px;">Tutup</button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const makananSelect = document.getElementById('makananSelect');
        const catatanBox = document.getElementById('catatanBox');
        const catatanText = document.getElementById('catatanText');
        const methodCards = document.querySelectorAll('.method-card');
        const selectedMethodsDiv = document.getElementById('selectedMethods');
        const selectedMethodsList = document.getElementById('selectedMethodsList');
        const analyzeBtn = document.getElementById('analyzeBtn');

        let selectedMethods = []; // array untuk menyimpan metode yang dipilih {id, name}

        // ====================
        // Saat pilih makanan dari dropdown
        // ====================
        makananSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const metodeCocok = JSON.parse(selectedOption.dataset.metodeCocok || '[]');
            const catatan = selectedOption.dataset.catatan || '';

            // Reset semua checkbox dan visual card
            document.querySelectorAll('.method-checkbox').forEach(cb => cb.checked = false);
            methodCards.forEach(card => {
                card.classList.remove('selected', 'disabled');
                card.style.opacity = '1';
                card.style.pointerEvents = 'auto';
                card.querySelector('input').disabled = false;
            });

            selectedMethods = []; // penting: reset daftar metode terpilih
            updateSelectedList();
            updateAnalyzeButton();

            // Jika ada batasan metode cocok
            const semuaDisable = metodeCocok.length === 0;

            methodCards.forEach(card => {
                const metodeId = parseInt(card.dataset.metodeId);
                const shouldDisable = semuaDisable || !metodeCocok.includes(metodeId);

                card.classList.toggle('disabled', shouldDisable);
                card.style.opacity = shouldDisable ? '0.4' : '1';
                card.style.pointerEvents = shouldDisable ? 'none' : 'auto';
                card.querySelector('input').disabled = shouldDisable;
            });

            if (semuaDisable) {
                catatanBox.style.display = 'block';
                catatanText.textContent = 'Belum ada metode pengolahan yang tersedia untuk makanan ini.';
            } else if (catatan.trim()) {
                catatanBox.style.display = 'block';
                catatanText.textContent = catatan;
            } else {
                catatanBox.style.display = 'none';
            }
        });

        // ====================
        // Event klik pada method card (atau label/checkbox)
        // ====================
        methodCards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Jangan proses kalau card disabled
                if (this.classList.contains('disabled')) return;

                const checkbox = this.querySelector('input[type="checkbox"]');
                const metodeId = parseInt(this.dataset.metodeId);
                const metodeName = this.querySelector('.method-name')?.textContent.trim() || 'Metode';

                // Toggle checked
                checkbox.checked = !checkbox.checked;

                if (checkbox.checked) {
                    this.classList.add('selected');
                    addSelectedMethod(metodeId, metodeName);
                } else {
                    this.classList.remove('selected');
                    removeSelectedMethod(metodeId);
                }

                updateAnalyzeButton();
            });
        });

        // Optional: tambahan listener langsung ke checkbox (lebih reliable kalau klik label)
        document.querySelectorAll('.method-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const card = this.closest('.method-card');
                if (card.classList.contains('disabled')) {
                    this.checked = false;
                    return;
                }

                const metodeId = parseInt(card.dataset.metodeId);
                const metodeName = card.querySelector('.method-name')?.textContent.trim() || 'Metode';

                if (this.checked) {
                    card.classList.add('selected');
                    addSelectedMethod(metodeId, metodeName);
                } else {
                    card.classList.remove('selected');
                    removeSelectedMethod(metodeId);
                }

                updateAnalyzeButton();
            });
        });

        // ====================
        // Fungsi helper
        // ====================
        function addSelectedMethod(id, name) {
            // Cek duplikat sebelum tambah
            if (!selectedMethods.some(m => m.id === id)) {
                selectedMethods.push({
                    id,
                    name
                });
                updateSelectedList();
            }
        }

        function removeSelectedMethod(id) {
            selectedMethods = selectedMethods.filter(m => m.id !== id);
            updateSelectedList();

            // Sinkronkan checkbox & visual
            const checkbox = document.querySelector(`#metode_${id}`);
            if (checkbox) checkbox.checked = false;

            const card = document.querySelector(`.method-card[data-metode-id="${id}"]`);
            if (card) card.classList.remove('selected');
        }

        function updateSelectedList() {
            if (selectedMethods.length > 0) {
                selectedMethodsDiv.style.display = 'block';
                selectedMethodsList.innerHTML = selectedMethods.map(m => `
                <div class="selected-method-item">
                    <span class="selected-method-name">${m.name}</span>
                    <button type="button" class="remove-method-btn" onclick="removeSelectedMethod(${m.id})">✕</button>
                </div>
            `).join('');
            } else {
                selectedMethodsDiv.style.display = 'none';
            }
        }

        function updateAnalyzeButton() {
            const select = document.getElementById('makananSelect');
            const makananDipilih = select.value && select.value.trim() !== ""; // lebih aman
            const adaMetode = selectedMethods.length > 0;

            analyzeBtn.disabled = !(makananDipilih && adaMetode);
        }

        updateAnalyzeButton(); // panggil sekali saat halaman load

        // ===== FUNCTION =====

        function showHelp() {
            document.getElementById('helpModal').classList.add('active');
        }

        function closeHelp() {
            document.getElementById('helpModal').classList.remove('active');
        }

        document.getElementById('helpModal').addEventListener('click', function(e) {
            if (e.target === this) closeHelp();
        });

        function showMetodeInfo() {
            document.getElementById('metodeModal').classList.add('active');
        }

        function closeMetodeInfo() {
            document.getElementById('metodeModal').classList.remove('active');
        }

        document.getElementById('metodeModal').addEventListener('click', function(e) {
            if (e.target === this) closeMetodeInfo();
        });
    </script>
@endsection
