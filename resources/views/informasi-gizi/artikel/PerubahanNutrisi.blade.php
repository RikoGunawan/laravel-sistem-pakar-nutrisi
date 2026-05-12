@extends('layouts.app')

@section('title', 'Mengapa Nutrisi Dada Ayam Berubah Setelah Dimasak?')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        Mengapa Nutrisi Dada Ayam Berubah Setelah Dimasak? Yuk, Simak Faktanya!
    </h1>

    <div class="prose prose-lg text-gray-700 leading-relaxed">
        <p class="mb-6">
            Pernahkah Anda bingung saat melihat tabel gizi? Mengapa dada ayam bakar seolah-olah punya vitamin dan protein lebih tinggi daripada ayam mentah? Apakah nutrisinya bertambah secara ajaib saat terkena api?
        </p>

        <p class="mb-8">
            Jawabannya adalah tidak. Mari kita bedah rahasia di balik angka-angka tersebut agar Anda tidak salah dalam menghitung asupan harian.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-10 mb-4">1. Efek "Penyusutan": Nutrisi Padat karena Air Hilang</h2>
        <p class="mb-6">
            Saat ayam dipanggang atau dibakar, ia kehilangan banyak kadar air. Bayangkan Anda punya 100g ayam mentah. Setelah dibakar, beratnya mungkin menyusut jadi 75g.
        </p>
        <p class="mb-6">
            Jika kita membandingkan 100g ayam mentah dengan 100g ayam matang, maka ayam matang akan terlihat lebih "hebat" nutrisinya. Padahal, itu hanya karena nutrisinya menjadi lebih padat (terkonsentrasi) setelah airnya menguap.
            Jadi, kenaikan nutrisi (seperti Niasin yang stabil terhadap panas) adalah hal yang wajar karena penyusutan berat.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-10 mb-4">2. Daging vs Tepung: Mana yang Menyerap Minyak?</h2>
        <p class="mb-4">
            Banyak orang mengira daging ayam menyerap banyak minyak saat digoreng. Faktanya:
        </p>

        <div class="bg-gray-50 border-l-4 border-orange-500 pl-6 py-5 my-6">
            <strong class="block mb-3">Daging Dada Polos:</strong>
            <p class="mb-0">
                Seratnya sangat rapat, sehingga sulit menyerap minyak ke dalam. Bahkan, lemak alami di dalam daging seringkali justru "meleleh" keluar saat digoreng. Inilah mengapa beberapa data menunjukkan lemak daging ayam justru turun.
            </p>
        </div>

        <div class="bg-gray-50 border-l-4 border-orange-500 pl-6 py-5 my-6">
            <strong class="block mb-3">Ayam Tepung (Crispy):</strong>
            <p class="mb-0">
                Di sinilah letak jebakannya. Tepung bersifat seperti spons. Saat digoreng, air dalam tepung keluar dan digantikan oleh minyak. Inilah yang membuat ayam goreng tepung (seperti di restoran cepat saji) memiliki kalori dan lemak yang jauh lebih tinggi.
            </p>
        </div>

        <h2 class="text-2xl font-semibold text-gray-800 mt-10 mb-4">3. Cara Menghitung yang Paling Adil</h2>
        <p class="mb-6">
            Agar perbandingan nutrisi antar metode masak menjadi adil (misal: Bakar vs Goreng), cara terbaik adalah menghitung dari berat mentah yang sama.
        </p>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 my-8">
            <p class="font-semibold text-amber-800 mb-3">Tips untuk Anda:</p>
            <ul class="list-disc pl-6 space-y-3 text-gray-700">
                <li>Jika Anda memasak di rumah, gunakan patokan 100g berat mentah. Meski setelah dimasak ukurannya mengecil, nutrisi utamanya tetap terjaga.</li>
                <li>Jika Anda membeli ayam goreng tepung di luar, lebih akurat menggunakan data "per potong matang", karena kita tidak tahu pasti berapa banyak minyak yang terperangkap di dalam tepungnya.</li>
            </ul>
        </div>

        <h2 class="text-2xl font-semibold text-gray-800 mt-10 mb-4">Kesimpulan</h2>
        <p class="text-gray-700 leading-relaxed">
            Metode masak tidak menambah vitamin, tapi bisa mengubah kepadatan nutrisi dan menambah lemak tambahan (terutama jika menggunakan tepung).
            Pilihlah metode masak yang sesuai dengan target diet Anda: Bakar atau rebus untuk menjaga kalori tetap rendah, atau goreng tepung jika Anda siap dengan ekstra kalori dari minyak yang terserap.
        </p>
    </div>
</div>
@endsection
