@extends('layouts.app')

@section('title', 'Analisis Nutrisi Makanan')

@section('styles')
<style>
    .help-btn {
        display: inline-block;
        padding: 10px 20px;
        background: #ffc107;
        color: #333;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .help-btn:hover {
        background: #e0a800;
        transform: translateY(-2px);
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
        border-color: #667eea;
        transform: scale(1.05);
    }

    .method-card.selected {
        border-color: #667eea;
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
        border: 2px solid #667eea;
    }

    .selected-methods h4 {
        color: #667eea;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        background: rgba(0,0,0,0.5);
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
        <h1 style="color: #000000; margin-bottom: 15px;">🔬 Analisis Nutrisi Makanan</h1>
        <p style="color: #666; margin-bottom: 25px;">Bandingkan perubahan nutrisi berdasarkan metode pengolahan yang berbeda</p>

        <a href="#" class="help-btn" onclick="showHelp(); return false;">❓ Tutorial Cara Analisis</a>

        <form action="{{ route('analisis.analyze') }}" method="POST" id="analisisForm">
            @csrf

            <div class="form-section">
                <div class="form-group">
                    <label class="form-label">1. Pilih Makanan</label>
                    <select name="makanan_id" id="makananSelect" class="form-select" required>
                        <option value="">-- Pilih Makanan --</option>
                        @foreach($makananList as $makanan)
                            <option value="{{ $makanan->id }}" {{ request('makanan') == $makanan->id ? 'selected' : '' }}>
                                {{ $makanan->image }} {{ $makanan->name }} ({{ $makanan->kategori }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">2. Pilih Metode Pengolahan (Minimal 1, bisa lebih)</label>
                    <div class="method-grid">
                        @foreach($metodeList as $metode)
                            <label for="metode_{{ $metode->id }}" class="method-card" data-metode-id="{{ $metode->id }}">
                                <input type="checkbox" name="metode_ids[]" value="{{ $metode->id }}"
                                       id="metode_{{ $metode->id }}" class="method-checkbox">
                                <div class="method-icon">{{ $metode->icon }}</div>
                                <div class="method-name">{{ $metode->name }}</div>
                            </label>
                        @endforeach
                    </div>

                    <div class="selected-methods" id="selectedMethods" style="display: none;">
                        <h4>✓ Metode Terpilih:</h4>
                        <div id="selectedMethodsList"></div>
                    </div>
                </div>

                <button type="submit" class="analyze-btn" id="analyzeBtn" disabled>
                    🔬 Analisis & Bandingkan
                </button>
            </div>
        </form>
    </div>

    <!-- Help Modal -->
    <div class="help-modal" id="helpModal">
        <div class="help-content">
            <h3>📖 Cara Menggunakan Analisis Nutrisi</h3>
            <ol>
                <li><strong>Pilih Makanan</strong> yang ingin Anda analisis dari dropdown</li>
                <li><strong>Pilih Metode Pengolahan</strong> dengan klik card metode. Anda bisa pilih lebih dari satu untuk membandingkan</li>
                <li>Metode yang terpilih akan ditandai dengan ✓ hijau</li>
                <li>Klik tombol <strong>"Analisis & Bandingkan"</strong> untuk melihat hasil</li>
                <li>Anda akan melihat tabel komparasi nutrisi antar metode</li>
                <li>Sistem akan memberikan rekomendasi metode terbaik</li>
            </ol>
            <button onclick="closeHelp()" class="btn btn-primary" style="margin-top: 20px;">Mengerti</button>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const methodCards = document.querySelectorAll('.method-card');
    const selectedMethodsDiv = document.getElementById('selectedMethods');
    const selectedMethodsList = document.getElementById('selectedMethodsList');
    const analyzeBtn = document.getElementById('analyzeBtn');
    let selectedMethods = [];

    methodCards.forEach(card => {
        card.addEventListener('click', function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;

            if (checkbox.checked) {
                this.classList.add('selected');
                addSelectedMethod(checkbox.value, this.querySelector('.method-name').textContent);
            } else {
                this.classList.remove('selected');
                removeSelectedMethod(checkbox.value);
            }

            updateAnalyzeButton();
        });
    });

    function addSelectedMethod(id, name) {
        if (!selectedMethods.find(m => m.id === id)) {
            selectedMethods.push({ id, name });
            updateSelectedList();
        }
    }

    function removeSelectedMethod(id) {
        selectedMethods = selectedMethods.filter(m => m.id !== id);
        updateSelectedList();

        // Uncheck checkbox
        document.querySelector(`#metode_${id}`).checked = false;
        document.querySelector(`label[data-metode-id="${id}"]`).classList.remove('selected');
    }

    function updateSelectedList() {
        if (selectedMethods.length > 0) {
            selectedMethodsDiv.style.display = 'block';
            selectedMethodsList.innerHTML = selectedMethods.map(m => `
                <div class="selected-method-item">
                    <span class="selected-method-name">${m.name}</span>
                    <button type="button" class="remove-method-btn" onclick="removeSelectedMethod('${m.id}')">✕</button>
                </div>
            `).join('');
        } else {
            selectedMethodsDiv.style.display = 'none';
        }
    }

    function updateAnalyzeButton() {
        const makananSelected = document.getElementById('makananSelect').value;
        const metodesSelected = selectedMethods.length > 0;

        analyzeBtn.disabled = !(makananSelected && metodesSelected);
    }

    document.getElementById('makananSelect').addEventListener('change', updateAnalyzeButton);

    function showHelp() {
        document.getElementById('helpModal').classList.add('active');
    }

    function closeHelp() {
        document.getElementById('helpModal').classList.remove('active');
    }

    // Close modal when clicking outside
    document.getElementById('helpModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeHelp();
        }
    });
</script>
@endsection
