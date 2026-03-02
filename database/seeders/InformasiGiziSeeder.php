<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InformasiGizi;

class InformasiGiziSeeder extends Seeder
{
    public function run(): void
    {
        $informasiData = [

            
    [
        'judul' => 'Goreng / Fried / Deep-Fry',
        'kategori' => 'fakta',
        'konten' => 'Memasak dengan minyak panas dalam jumlah banyak.<br><strong>Kelebihan :</strong><br>- Tekstur makanan renyah (crispy)<br>- Rasa lebih kaya<br>- Penyerapan vitamin larut lemak lebih baik<br><strong>Kekurangan :</strong><br>- Tambah lemak ekstra<br>- Kurangi beberapa vitamin sensitif panas (vit. C, dll)<br><strong>Cocok untuk makanan :</strong><br>- Daging, ikan, ayam, kentang, tempe, tahu',
        'icon' => null,
        'image' => 'https://www.dapurkobe.co.id/wp-content/uploads/tempe-goreng-kriuk-ala-tepung-kobe.jpg',
        'sumber' => 'https://doaj.org/article/edcc352d413f42f9b55b0785ee42a9f2, https://www.dapurkobe.co.id/tempe-kriuk-tepung-kobe',
        'is_published' => true,
    ],
    [
        'judul' => 'Air Fryer',
        'kategori' => 'fakta',
        'konten' => 'Memasak dengan udara panas berputar cepat, mirip goreng tapi minyak sangat sedikit atau tanpa.<br><strong>Kelebihan :</strong><br>- Kurangi lemak dibanding goreng biasa<br>- Tekstur renyah mirip goreng<br>- Kurangi zat berbahaya seperti acrylamide<br><strong>Kekurangan :</strong><br>- Panas tinggi bisa kurangi nutrisi sensitif kalau terlalu lama<br>- Hasil kadang kurang lembab<br><strong>Cocok untuk makanan :</strong><br>- Kentang goreng, ayam, ikan, nugget, sayur seperti brokoli, camilan',
        'icon' => null,
        'image' => 'https://airfried.com/wp-content/uploads/2021/02/chicken-nuggets-in-air-fryer.jpg',
        'sumber' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC9097553, https://airfried.com/air-fryer-frozen-chicken-nuggets/',
        'is_published' => true,
    ],
    [
        'judul' => 'Tumis / Sauteed / Oseng',
        'kategori' => 'fakta',
        'konten' => 'Menggoreng cepat dengan sedikit minyak sambil diaduk di wajan panas.<br><strong>Kelebihan :</strong><br>- Rasa lebih intens<br>- Penyerapan nutrisi larut lemak meningkat<br>- Matang cepat, tekstur renyah terjaga<br><strong>Kekurangan :</strong><br>- Vitamin sensitif panas bisa berkurang kalau terlalu lama<br><strong>Cocok untuk makanan :</strong><br>- Sayur cepat matang (kangkung, buncis), daging tipis, seafood, tempe oseng',
        'icon' => null,
        'image' => 'http://www.authenticworldfood.com/data/r0/00000110-00000120.jpg',
        'sumber' => 'https://pubs.acs.org/doi/10.1021/jf072304b, https://www.authenticworldfood.com/en/exotic-recipes/indonesia/stir-fried-mixed-vegetables-oseng-oseng-tumis-sayuran.html',
        'is_published' => true,
    ],
    [
        'judul' => 'Brown / Searing',
        'kategori' => 'fakta',
        'konten' => 'Membuat permukaan makanan coklat dengan panas tinggi singkat.<br><strong>Kelebihan :</strong><br>- Tingkatkan rasa dan aroma dalam<br>- Jaga nutrisi di dalam tanpa overcook<br><strong>Kekurangan :</strong><br>- Hanya permukaan, kalau over bisa gosong<br><strong>Cocok untuk makanan :</strong><br>- Daging steak, ayam sebelum direbus atau panggang',
        'icon' => null,
        'image' => 'https://api.meatguy.id/admin/image/blogs/97539823-bde4-4c9b-91a7-317c039553be',
        'sumber' => 'https://doaj.org/article/edcc352d413f42f9b55b0785ee42a9f2, https://meatguysteakhouse.com/id/blog/pan-seared-steak',
        'is_published' => true,
    ],
    [
        'judul' => 'Bakar / Grill',
        'kategori' => 'fakta',
        'konten' => 'Memasak langsung di atas api atau panggangan.<br><strong>Kelebihan :</strong><br>- Rasa smoky enak<br>- Kurangi lemak di daging<br>- Tekstur bagus<br><strong>Kekurangan :</strong><br>- Makanan gosong memiliki zat karsinogen yang dapat menyebabkan kanker<br>- Kurangi vitamin sensitif<br><strong>Cocok untuk makanan :</strong><br>- Daging, ikan, sayur tebal, jagung, sate',
        'icon' => null,
        'image' => 'https://thumbs.dreamstime.com/b/satay-sate-indonesian-malaysian-dish-consisting-small-pieces-meat-grilled-skewer-served-spiced-sauce-145378049.jpg',
        'sumber' => 'https://doaj.org/article/edcc352d413f42f9b55b0785ee42a9f2, https://thumbs.dreamstime.com/b/satay-sate-indonesian-malaysian-dish-consisting-small-pieces-meat-grilled-skewer-served-spiced-sauce-145378049.jpg',
        'is_published' => true,
        // https://dailycookingquest.com/sate-babi-indonesian-pork-satay.html
    ],
    [
        'judul' => 'Roasted / Panggang Oven',
        'kategori' => 'fakta',
        'konten' => 'Memanggang di oven dengan panas kering.<br><strong>Kelebihan :</strong><br>- Rasa karamelisasi enak<br>- Jaga nutrisi di dalam makanan<br>- Kurangi lemak ekstra<br><strong>Kekurangan :</strong><br>- Panas lama bisa kurangi vitamin sensitif<br><strong>Cocok untuk makanan :</strong><br>- Daging, sayur akar, ayam utuh',
        'icon' => null,
        'image' => 'https://www.hypermart.co.id/wp-content/uploads/elementor/thumbs/82-p5q08kcf3d0lwn296wiprds6lco6xhci500fw5m7fc.jpg',
        'sumber' => 'https://journal.pan.olsztyn.pl/Effect-of-Different-Cooking-Methods-on-Lipid-Content-and-Fatty-Acid-Profile-of-Red,159651,0,2.html, https://www.hypermart.co.id/resep-roasted-chicken-with-herb/',
        'is_published' => true,
    ],
    [
        'judul' => 'Baked / Oven Baking',
        'kategori' => 'fakta',
        'konten' => 'Memanggang di oven tanpa cairan tambahan.<br><strong>Kelebihan :</strong><br>- Jaga nutrisi relatif baik<br>- Kurangi tambahan lemak<br><strong>Kekurangan :</strong><br>- Bisa kering kalau terlalu lama<br><strong>Cocok untuk makanan :</strong><br>- Roti, kue, sayur, daging',
        'icon' => null,
        'image' => 'https://www.budgetbytes.com/wp-content/uploads/2020/05/Baked-Potatoes-Overhead.jpg', // adaptasi baked-like
        'sumber' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC8391696, https://www.budgetbytes.com/how-to-make-baked-potatoes/',
        'is_published' => true,
    ],
    [
        'judul' => 'Pepes (Steaming in Banana Leaf)',
        'kategori' => 'fakta',
        'konten' => 'Mengukus makanan dibungkus daun pisang dengan bumbu.<br><strong>Kelebihan :</strong><br>- Rasa bumbu meresap dalam<br>- Aroma daun pisang enak<br>- Jaga nutrisi karena kukus tanpa minyak<br><strong>Kekurangan :</strong><br>- Butuh waktu lebih lama<br>- Daun pisang harus segar<br><strong>Cocok untuk makanan :</strong><br>- Ikan (mas, nila, bandeng), ayam, tahu, tempe, jamur',
        'icon' => null,
        'image' => 'https://www.cookmeindonesian.com/wp-content/uploads/2020/07/pepes-ikan-kembung2.jpg',
        'sumber' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC6049644, https://www.cookmeindonesian.com/pepes-ikan-kembung-steamed-grilled-mackerel-in-banana-leaves/',
        'is_published' => true,
    ],
    [
        'judul' => 'Presto (Pressure Cooking)',
        'kategori' => 'fakta',
        'konten' => 'Memasak dengan tekanan tinggi di panci presto.<br><strong>Kelebihan :</strong><br>- Bikin makanan empuk cepat<br>- Lunakkan tulang ikan jadi bisa dimakan<br>- Hemat waktu dan gas<br><strong>Kekurangan :</strong><br>- Tekanan tinggi bisa ubah tekstur kalau overcook<br>- Nutrisi larut air mungkin hilang ke kuah<br><strong>Cocok untuk makanan :</strong><br>- Bandeng presto, daging keras, sop tulang, pepes ikan presto',
        'icon' => null,
        'image' => 'https://tradisikuliner.com/wp-content/uploads/2025/04/WhatsApp-Image-2022-03-08-at-21.25.04.jpeg',
        'sumber' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC8391696, https://tradisikuliner.com/wp-content/uploads/2025/04/WhatsApp-Image-2022-03-08-at-21.25.04.jpeg',
        'is_published' => true,
    ],
    [
        'judul' => 'Rebus / Boil',
        'kategori' => 'fakta',
        'konten' => 'Memasak dalam air mendidih.<br><strong>Kelebihan :</strong><br>- Bikin makanan lembut<br>- Mudah dicerna<br>- Cocok untuk sup<br><strong>Kekurangan :</strong><br>- Nutrisi larut air bisa hilang ke air rebusan<br><strong>Cocok untuk makanan :</strong><br>- Sayur daun, telur, pasta, daging keras',
        'icon' => null,
        'image' => 'https://thumbs.dreamstime.com/b/tumis-oseng-pare-tempe-stir-fried-bitter-melon-tempeh-traditional-indonesian-home-dish-popular-indonesia-419769211.jpg', // adaptasi rebus sayur
        'sumber' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC6049644, https://www.dreamstime.com/tumis-oseng-pare-tempe-stir-fried-bitter-melon-tempeh-traditional-indonesian-home-dish-popular-indonesia-image419769211',
        'is_published' => true,
    ],
    [
        'judul' => 'Kukus / Steam',
        'kategori' => 'fakta',
        'konten' => 'Memasak dengan uap air panas tanpa kontak langsung air.<br><strong>Kelebihan :</strong><br>- Jaga nutrisi lebih baik, terutama vitamin dan antioksidan<br>- Tanpa tambah lemak<br><strong>Kekurangan :</strong><br>- Butuh waktu lebih lama untuk makanan tebal<br><strong>Cocok untuk makanan :</strong><br>- Sayur seperti brokoli, wortel, ikan, tahu',
        'icon' => null,
        'image' => 'https://foodcomas.com/wp-content/uploads/2013/03/p1200273.jpg',
        'sumber' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC2722699, https://foodcomas.com/2013/05/31/pepes-ikan/',
        'is_published' => true,
    ],
    [
        'judul' => 'Poached',
        'kategori' => 'fakta',
        'konten' => 'Merebus pelan dalam cairan tanpa mendidih keras.<br><strong>Kelebihan :</strong><br>- Makanan tetap lembut dan lembab<br>- Nutrisi lebih terjaga daripada rebus biasa<br><strong>Kekurangan :</strong><br>- Butuh pengawasan agar tidak overcook<br><strong>Cocok untuk makanan :</strong><br>- Telur, ikan lembut, buah',
        'icon' => null,
        'image' => 'https://otaokitchen.com.au/files/thumb?w=750&h=500&src=/uploads//Fish-1.jpeg',
        'sumber' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC8391696, https://otaokitchen.com.au/recipes/indonesian/pepes-ikan-indonesian-steamed-fish-r295.html',
        'is_published' => true,
    ],
    [
        'judul' => 'Simmered (with/without drippings)',
        'kategori' => 'fakta',
        'konten' => 'Merebus pelan di cairan, kadang pakai tetesan lemak.<br><strong>Kelebihan :</strong><br>- Rasa kuah kaya<br>- Nutrisi larut ke kuah (bisa dimakan)<br><strong>Kekurangan :</strong><br>- Lama masak bisa kurangi nutrisi sensitif<br><strong>Cocok untuk makanan :</strong><br>- Sup, gulai, daging keras',
        'icon' => null,
        'image' => 'https://www.shutterstock.com/shutterstock/photos/1186340404/display_1500/stock-photo-oseng-tumis-kerang-indonesian-food-stir-fried-mussels-with-soy-sauce-lemongrass-bayleaf-ginger-1186340404.jpg', // adaptasi simmer kuah
        'sumber' => 'https://www.cell.com/heliyon/fulltext/S2405-8440(23)08917-X, https://www.shutterstock.com/image-photo/oseng-tumis-kerang-indonesian-food-stirfried-1186340404',
        'is_published' => true,
    ],
    [
        'judul' => 'Stewed',
        'kategori' => 'fakta',
        'konten' => 'Merebus pelan dengan kuah banyak.<br><strong>Kelebihan :</strong><br>- Rasa meresap<br>- Bikin makanan empuk<br><strong>Kekurangan :</strong><br>- Nutrisi larut ke kuah, kalau kuah dibuang bisa hilang<br><strong>Cocok untuk makanan :</strong><br>- Gulai, semur, sayur berkuah',
        'icon' => null,
        'image' => 'https://www.shutterstock.com/shutterstock/photos/1186340404/display_1500/stock-photo-oseng-tumis-kerang-indonesian-food-stir-fried-mussels-with-soy-sauce-lemongrass-bayleaf-ginger-1186340404.jpg',
        'sumber' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC6049644, https://www.shutterstock.com/image-photo/oseng-tumis-kerang-indonesian-food-stirfried-1186340404',
        'is_published' => true,
    ],

    //TIPS

    [
        'judul' => 'Gula Berlebih Picu Peradangan Diam-diam',
        'kategori' => 'tips',
        'konten' => 'Gula tambahan (added sugar) bukan cuma bikin gemuk—picu peradangan kronis di tubuh yang jadi akar diabetes, jantung, bahkan mood buruk. Fruktosa di gula lebih cepat ubah jadi lemak di hati dibanding glukosa—kurangi minuman manis ya!',
        'icon' => null,
        'image' => 'https://www.manipalhospitals.com/uploads/image_gallery/conditions-soft-drinks-can-lead-to.jpg',
        'sumber' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC9471313, https://www.manipalhospitals.com/uploads/image_gallery/conditions-soft-drinks-can-lead-to.jpg',
        'is_published' => true,
    ],
    [
        'judul' => 'Porsi Gula Aman Sehari-hari Menurut WHO',
        'kategori' => 'tips',
        'konten' => 'WHO saranin kurangi gula bebas (added sugar) kurang dari 10% total kalori (sekitar 50g atau 12 sdt untuk 2000 kalori), idealnya di bawah 5% (25g atau 6 sdt) buat manfaat ekstra. Di Indonesia sering lewatin batas ini dari teh/es manis saja—cek label makanan!',
        'icon' => null,
        'image' => 'https://www.news-medical.net/images/news/ImageForNews_798492_17344780676279201.png',
        'sumber' => 'https://www.who.int/publications/i/item/9789241549028, https://www.news-medical.net/images/news/ImageForNews_798492_17344780676279201.png',
        'is_published' => true,
    ],
    [
        'judul' => 'Gorengan Ga Sehat Karena Apa?',
        'kategori' => 'tips',
        'konten' => 'Gorengan enak renyah, tapi makanan menyerap minyak banyak karena terendam panas sehingga kalori & lemak naik drastis. Minyak panas oksidasi cepat, bentuk senyawa berbahaya yang risiko jantung & peradangan. Jangka panjang: obesitas, diabetes, tekanan darah tinggi. Solusi: kurangi gorengan, atau ganti tumis sedikit, kukus, air fryer.',        'icon' => null,
        'image' => 'https://thumbs.dreamstime.com/b/gorengan-fried-food-one-type-popular-snack-indonesia-tempeh-tofu-banana-bakwan-gorengan-147790618.jpg',
        'sumber' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC7254282, https://thumbs.dreamstime.com/b/gorengan-fried-food-one-type-popular-snack-indonesia-tempeh-tofu-banana-bakwan-gorengan-147790618.jpg',
        'is_published' => true,
    ],
    [
        'judul' => 'Mengukus: Metode Masak Terbaik untuk Nutrisi',
        'kategori' => 'tips',
        'konten' => 'Kalau kamu peduli sama nutrisi, mengukus adalah juaranya! Penelitian menunjukkan bahwa mengukus brokoli hanya menghilangkan 10-15% nutrisinya, sementara merebus bisa menghancurkan 40-50% vitamin. Plus, mengukus itu gampang banget - tinggal taro di kukusan, tunggu 10 menit, done! Hemat waktu, hemat nutrisi.',
        'icon' => null,
        'image' => 'https://static01.nyt.com/images/2024/08/28/multimedia/MP-Broccoli-With-Lemonrex-gzbq/MP-Broccoli-With-Lemonrex-gzbq-square640.jpg',
        'sumber' => 'https://pmc.ncbi.nlm.nih.gov/articles/PMC6267444/, https://static01.nyt.com/images/2024/08/28/multimedia/MP-Broccoli-With-Lemonrex-gzbq/MP-Broccoli-With-Lemonrex-gzbq-square640.jpg',
        'is_published' => true,
    ],
    [
        'judul' => 'Protein Tetap Aman Walau Dimasak',
        'kategori' => 'tips',
        'konten' => 'Kabar baik buat pecinta daging! Berbeda dengan vitamin, protein itu tangguh. Mau digoreng, dipanggang, atau direbus, kandungan protein di daging tetap stabil. Yang berubah hanya strukturnya (denaturasi), tapi nilai gizinya? Tetap sama! Jadi fokus kamu bukan ke cara masak, tapi ke jenis dagingnya - pilih yang rendah lemak seperti ayam tanpa kulit atau ikan.',
        'icon' => null,
        'image' => 'https://www.health.com/thmb/Wdd9zrqHUNJNu9dpnSLE2lfJafg=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/Health-GettyImages-1205073473-5b2dc8e1104248fd87df846c43b57d67.jpg',
        'sumber' => 'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC6723444/, https://www.health.com/thmb/Wdd9zrqHUNJNu9dpnSLE2lfJafg=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/Health-GettyImages-1205073473-5b2dc8e1104248fd87df846c43b57d67.jpg',
        'is_published' => true,
    ],
    [
        'judul' => 'Air Rebusan Sayur Jangan Dibuang!',
        'kategori' => 'tips',
        'konten' => 'Ini dia life hack yang jarang orang tahu: air bekas rebusan sayuran itu "kaya raya" nutrisi! Saat kamu merebus sayur, vitamin larut air seperti vitamin B dan C pindah ke air rebusannya. Jangan dibuang, manfaatkan untuk kuah sup, bikin nasi, atau tumis. Gratis nutrisi ekstra tanpa effort!',
        'icon' => null,
        'image' => 'https://static01.nyt.com/images/2024/08/28/multimedia/MP-Broccoli-With-Lemonrex-gzbq/MP-Broccoli-With-Lemonrex-gzbq-square640.jpg',
        'sumber' => 'https://www.bbc.com/future/article/20200302-the-best-ways-to-cook-vegetables, https://static01.nyt.com/images/2024/08/28/multimedia/MP-Broccoli-With-Lemonrex-gzbq/MP-Broccoli-With-Lemonrex-gzbq-square640.jpg',
        'is_published' => true,
    ],
    [
        'judul' => 'Memanggang vs Menggoreng: Mana Lebih Sehat?',
        'kategori' => 'tips',
        'konten' => 'Buat kamu yang bingung pilih metode masak, ini dia fakta singkatnya: memanggang mengurangi lemak karena meleleh dan menetes (bagus untuk diet!), tapi vitamin berkurang 30-40%. Menggoreng? Lemak naik drastis tapi protein aman. Solusinya? Panggang untuk daging berlemak seperti sapi, goreng cepat (tumis) untuk sayuran agar vitamin tetap terjaga. Mix & match sesuai bahan makanan!',
        'icon' => null,
        'image' => 'https://static01.nyt.com/images/2015/09/23/dining/23ROASTEDVEGETABLES/23ROASTEDVEGETABLES-square640.jpg',
        'sumber' => 'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC9331532/, https://static01.nyt.com/images/2015/09/23/dining/23ROASTEDVEGETABLES/23ROASTEDVEGETABLES-square640.jpg',
        'is_published' => true,
    ],
    [
        'judul' => 'Karbohidrat Kompleks vs Sederhana',
        'kategori' => 'tips',
        'konten' => 'Nasi putih vs nasi merah, apa bedanya? Nasi putih (karbohidrat sederhana) bikin gula darah naik cepat, turun cepat juga - jadinya cepat laper lagi. Nasi merah atau kentang (karbohidrat kompleks) dicerna lambat, energi stabil, kenyang lebih lama. Mau produktif seharian? Pilih yang kompleks. Bonus: serat di dalamnya bantu pencernaan!',
        'icon' => null,
        'image' => 'https://media.post.rvohealth.io/wp-content/uploads/2020/08/732x549_THUMBNAIL_Brown_Rice_vs._White_Rice.jpg',
        'sumber' => 'https://www.hsph.harvard.edu/nutritionsource/carbohydrates/, https://media.post.rvohealth.io/wp-content/uploads/2020/08/732x549_THUMBNAIL_Brown_Rice_vs._White_Rice.jpg',
        'is_published' => true,
    ],
    [
        'judul' => 'Warna Sayuran Menunjukkan Nutrisinya',
        'kategori' => 'tips',
        'konten' => 'Pernah dengar "eat the rainbow"? Ternyata warna sayuran itu bukan cuma cantik di piring, tapi menunjukkan jenis nutrisinya! Orange (wortel, labu) = vitamin A untuk mata. Hijau tua (bayam, brokoli) = zat besi dan folat. Merah-ungu (terong, bit) = antioksidan untuk tangkal radikal bebas. Makin beragam warnanya, makin lengkap nutrisimu!',
        'icon' => null,
        'image' => 'https://kikibix.com/cdn/shop/articles/rainbow-diet-padham-health-news-768x576_768x.jpg?v=1651757625',
        'sumber' => 'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC3649719/, https://kikibix.com/cdn/shop/articles/rainbow-diet-padham-health-news-768x576_768x.jpg?v=1651757625',
        'is_published' => true,
    ],
    [
        'judul' => 'Lemak Itu Tidak Selalu Jahat',
        'kategori' => 'tips',
        'konten' => 'Stop takut sama lemak! Tubuh kita butuh lemak untuk menyerap vitamin A, D, E, dan K. Kuncinya: pilih lemak yang "baik" seperti yang ada di alpukat, kacang-kacangan, dan ikan. Lemak trans dari gorengan dan makanan olahan? Nah itu yang harus dihindari. Fun fact: makan alpukat dengan salad sayur bikin penyerapan vitamin meningkat 5 kali lipat!',
        'icon' => null,
        'image' => 'https://www.virtua.org/-/media/Project/Virtua-Tenant/Virtua/Images/Articles/Stock/recipe-for-gut-health-avocado-and-black-bean-salad.jpg',
        'sumber' => 'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC6315740/, https://www.virtua.org/-/media/Project/Virtua-Tenant/Virtua/Images/Articles/Stock/recipe-for-gut-health-avocado-and-black-bean-salad.jpg',
        'is_published' => true,
    ],
    [
        'judul' => 'Porsi Ideal Makan: Pakai Tangan Aja!',
        'kategori' => 'tips',
        'konten' => 'Gak punya timbangan makanan? Pakai tanganmu sebagai patokan! Protein (daging/ikan) = 1 telapak tangan. Karbohidrat (nasi/kentang) = 1 genggaman. Sayuran = 2 genggaman. Lemak sehat (minyak/mentega) = 1 ujung jempol. Simple kan? Cara ini sudah terbukti efektif dan direkomendasikan ahli gizi di seluruh dunia. No ribet, no timbangan!',
        'icon' => null,
        'image' => 'https://kaynutrition.com/wp-content/uploads/2024/12/hand-portion-sizes-8.jpg',
        'sumber' => 'https://www.precisionnutrition.com/calorie-control-guide-infographic, https://kaynutrition.com/wp-content/uploads/2024/12/hand-portion-sizes-8.jpg',
        'is_published' => true,
    ],



            // [
            //     'judul' => '',
            //     'kategori' => 'fakta',
            //     'konten' => '',
            //     'icon' => null,
            //     'image' => '',
            //     'sumber' => ', ',
            //     'is_published' => true,
            // ],
        ];

        foreach ($informasiData as $data) {
            InformasiGizi::create($data);
        }

        $this->command->info('✓ Berhasil menambahkan ' . count($informasiData) . ' informasi gizi dengan sumber terpercaya');
    }
}
