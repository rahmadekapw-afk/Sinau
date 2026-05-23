<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $this->seed10SMA();
        $this->seed11SMA();
        $this->seed7SMP();
    }

    private function seed10SMA(): void
    {
        $k = '10-sma';

        // MATEMATIKA
        $sub = Subject::where('name','Matematika')->where('kelas',$k)->first();
        if ($sub) {
            $this->m($sub, $k, 'Eksponen dan Logaritma', 1, 'mudah', 25,
                'Memahami sifat-sifat eksponen dan logaritma serta penerapannya.',
                "## Eksponen\n\nEksponen adalah operasi perkalian berulang.\n\n### Sifat-sifat Eksponen\n- **a^m × a^n = a^(m+n)**\n- **a^m ÷ a^n = a^(m-n)**\n- **(a^m)^n = a^(m×n)**\n- **a^0 = 1** (a ≠ 0)\n- **a^(-n) = 1/a^n**\n\n### Contoh Soal\n1. Sederhanakan: 2³ × 2⁴ = 2^(3+4) = 2⁷ = **128**\n2. Sederhanakan: 5⁶ ÷ 5² = 5^(6-2) = 5⁴ = **625**\n\n## Logaritma\n\nLogaritma adalah invers dari eksponen. Jika a^x = b, maka log_a(b) = x.\n\n### Sifat-sifat Logaritma\n- **log(a×b) = log a + log b**\n- **log(a/b) = log a - log b**\n- **log(a^n) = n × log a**\n- **log_a(a) = 1**\n- **log_a(1) = 0**\n\n### Contoh Soal\n1. log₂(8) = log₂(2³) = **3**\n2. log 100 = log 10² = **2**\n\n## Latihan\n1. Sederhanakan: 3² × 3⁵\n2. Hitung: log₃(81)\n3. Jika log 2 = 0,301, tentukan log 8"
            );
            $this->m($sub, $k, 'Persamaan dan Pertidaksamaan Linear', 2, 'sedang', 30,
                'Menyelesaikan persamaan dan pertidaksamaan linear satu variabel.',
                "## Persamaan Linear Satu Variabel (PLSV)\n\nBentuk umum: **ax + b = 0**, dengan a ≠ 0\n\n### Langkah Penyelesaian\n1. Kelompokkan variabel di satu sisi\n2. Kelompokkan konstanta di sisi lain\n3. Selesaikan\n\n### Contoh\n3x + 5 = 14\n→ 3x = 14 - 5\n→ 3x = 9\n→ x = **3**\n\n## Pertidaksamaan Linear\n\nMenggunakan tanda: <, >, ≤, ≥\n\n### Aturan Penting\n- Jika dikalikan/dibagi bilangan **negatif**, tanda pertidaksamaan **berbalik**\n\n### Contoh\n-2x + 4 > 10\n→ -2x > 6\n→ x < -3 *(tanda berbalik karena dibagi -2)*\n\n## Latihan\n1. Selesaikan: 5x - 3 = 2x + 9\n2. Selesaikan: 4x + 7 ≤ 23"
            );
        }

        // FISIKA
        $sub = Subject::where('name','Fisika')->where('kelas',$k)->first();
        if ($sub) {
            $this->m($sub, $k, 'Besaran dan Satuan', 1, 'mudah', 20,
                'Memahami besaran pokok, besaran turunan, dan sistem satuan internasional.',
                "## Besaran Pokok\n\n| No | Besaran | Satuan SI | Simbol |\n|----|---------|-----------|--------|\n| 1 | Panjang | meter | m |\n| 2 | Massa | kilogram | kg |\n| 3 | Waktu | sekon | s |\n| 4 | Suhu | kelvin | K |\n| 5 | Kuat arus | ampere | A |\n| 6 | Intensitas cahaya | kandela | cd |\n| 7 | Jumlah zat | mol | mol |\n\n## Besaran Turunan\n\nBesaran yang diturunkan dari besaran pokok:\n- **Kecepatan** = jarak / waktu → m/s\n- **Percepatan** = kecepatan / waktu → m/s²\n- **Gaya** = massa × percepatan → kg⋅m/s² (Newton)\n- **Energi** = gaya × jarak → kg⋅m²/s² (Joule)\n\n## Konversi Satuan\n- 1 km = 1.000 m\n- 1 jam = 3.600 s\n- 1 kg = 1.000 g\n\n### Contoh\nKonversi 72 km/jam ke m/s:\n72 × 1000/3600 = **20 m/s**"
            );
            $this->m($sub, $k, 'Gerak Lurus', 2, 'sedang', 35,
                'Kinematika gerak lurus beraturan (GLB) dan gerak lurus berubah beraturan (GLBB).',
                "## Gerak Lurus Beraturan (GLB)\n\nGerak dengan **kecepatan tetap** (percepatan = 0).\n\n**Rumus:** s = v × t\n- s = jarak (m)\n- v = kecepatan (m/s)\n- t = waktu (s)\n\n### Contoh\nMobil bergerak 20 m/s selama 10 s. Jarak = 20 × 10 = **200 m**\n\n## Gerak Lurus Berubah Beraturan (GLBB)\n\nGerak dengan **percepatan tetap**.\n\n### Rumus GLBB\n- **v = v₀ + a⋅t**\n- **s = v₀⋅t + ½⋅a⋅t²**\n- **v² = v₀² + 2⋅a⋅s**\n\nKeterangan:\n- v₀ = kecepatan awal, v = kecepatan akhir\n- a = percepatan, t = waktu, s = perpindahan\n\n### Contoh Soal\nMobil dari diam (v₀=0) dipercepat 2 m/s² selama 5 s.\n- v = 0 + 2(5) = **10 m/s**\n- s = 0(5) + ½(2)(25) = **25 m**\n\n## Gerak Jatuh Bebas\nKasus khusus GLBB dengan a = g = 10 m/s²\n- v = g⋅t\n- h = ½⋅g⋅t²"
            );
        }

        // KIMIA
        $sub = Subject::where('name','Kimia')->where('kelas',$k)->first();
        if ($sub) {
            $this->m($sub, $k, 'Struktur Atom', 1, 'sedang', 30,
                'Model atom, partikel penyusun atom, dan konfigurasi elektron.',
                "## Partikel Penyusun Atom\n\n| Partikel | Muatan | Massa (sma) | Lokasi |\n|----------|--------|-------------|--------|\n| Proton | +1 | 1 | Inti atom |\n| Neutron | 0 | 1 | Inti atom |\n| Elektron | -1 | 1/1836 | Kulit atom |\n\n## Nomor Atom dan Massa\n- **Nomor atom (Z)** = jumlah proton\n- **Nomor massa (A)** = proton + neutron\n- Jumlah elektron = jumlah proton (atom netral)\n\n## Konfigurasi Elektron\n\nElektron mengisi kulit dari yang terdekat ke inti:\n- Kulit K (n=1): maks 2 elektron\n- Kulit L (n=2): maks 8 elektron\n- Kulit M (n=3): maks 18 elektron\n\n### Contoh: Natrium (Na, Z=11)\nKonfigurasi: 2, 8, 1\n→ Elektron valensi = 1\n\n## Isotop, Isobar, Isoton\n- **Isotop**: Z sama, A beda (C-12 dan C-14)\n- **Isobar**: A sama, Z beda\n- **Isoton**: neutron sama"
            );
        }

        // BIOLOGI
        $sub = Subject::where('name','Biologi')->where('kelas',$k)->first();
        if ($sub) {
            $this->m($sub, $k, 'Keanekaragaman Hayati', 1, 'mudah', 25,
                'Tingkat keanekaragaman hayati dan klasifikasi makhluk hidup.',
                "## Tingkat Keanekaragaman Hayati\n\n### 1. Keanekaragaman Gen\nVariasi antar individu dalam **satu spesies**.\nContoh: Warna bunga mawar (merah, putih, kuning)\n\n### 2. Keanekaragaman Jenis (Spesies)\nVariasi antar **spesies berbeda** dalam satu famili.\nContoh: Kucing, harimau, singa (famili Felidae)\n\n### 3. Keanekaragaman Ekosistem\nVariasi antar **ekosistem** yang berbeda.\nContoh: Hutan hujan tropis, savana, terumbu karang\n\n## Klasifikasi Makhluk Hidup\n\nUrutan taksonomi (dari umum ke khusus):\n**Kingdom → Filum → Kelas → Ordo → Famili → Genus → Spesies**\n\nCara mengingat: **King Philip Came Over For Good Spaghetti**\n\n## 5 Kingdom\n1. **Monera** - bakteri (prokariotik)\n2. **Protista** - amoeba, alga\n3. **Fungi** - jamur\n4. **Plantae** - tumbuhan\n5. **Animalia** - hewan\n\n## Binomial Nomenclature\nAturan penamaan ilmiah:\n- Dua kata: *Genus spesies*\n- Dicetak miring: *Oryza sativa* (padi)"
            );
        }

        // BAHASA INDONESIA
        $sub = Subject::where('name','Bahasa Indonesia')->where('kelas',$k)->first();
        if ($sub) {
            $this->m($sub, $k, 'Teks Laporan Hasil Observasi', 1, 'mudah', 20,
                'Struktur, ciri kebahasaan, dan cara menyusun teks LHO.',
                "## Pengertian\n\nTeks Laporan Hasil Observasi (LHO) adalah teks yang menyampaikan informasi tentang suatu objek berdasarkan **pengamatan** yang telah dilakukan.\n\n## Struktur Teks LHO\n\n1. **Pernyataan Umum/Klasifikasi** - Pengantar tentang objek\n2. **Deskripsi Bagian** - Penjelasan detail per bagian/aspek\n3. **Simpulan** (opsional) - Ringkasan hasil observasi\n\n## Ciri Kebahasaan\n- Menggunakan kata **objektif** (fakta, bukan opini)\n- Kata kerja: *merupakan, termasuk, terdiri atas*\n- Kata teknis sesuai bidang ilmu\n- Kalimat definisi dan deskripsi\n\n## Contoh Singkat\n\n**Kucing Domestik**\n\n*Pernyataan Umum:*\nKucing (Felis catus) merupakan hewan mamalia karnivora dari keluarga Felidae.\n\n*Deskripsi:*\nKucing memiliki tubuh yang fleksibel, refleks cepat, serta cakar yang dapat ditarik. Berat badan kucing dewasa berkisar 4-5 kg.\n\n## Latihan\nBuatlah teks LHO tentang lingkungan sekolahmu!"
            );
        }

        // BAHASA INGGRIS
        $sub = Subject::where('name','Bahasa Inggris')->where('kelas',$k)->first();
        if ($sub) {
            $this->m($sub, $k, 'Descriptive Text', 1, 'mudah', 20,
                'Understanding and writing descriptive texts about people, places, and things.',
                "## What is Descriptive Text?\n\nA text that **describes** a particular person, place, or thing in detail.\n\n## Structure\n\n1. **Identification** - Introduces the subject\n2. **Description** - Details about features, qualities, characteristics\n\n## Language Features\n- **Simple Present Tense**: *The beach is beautiful*\n- **Adjectives**: *tall, beautiful, famous, ancient*\n- **Has/Have**: *It has blue water*\n- **Figurative language**: *as white as snow*\n\n## Example\n\n**Borobudur Temple**\n\n*Identification:*\nBorobudur is the largest Buddhist temple in the world, located in Magelang, Central Java.\n\n*Description:*\nThe temple has nine stacked platforms, six square and three circular. It is decorated with 2,672 relief panels and 504 Buddha statues. The temple sits majestically surrounded by lush green mountains and rice fields.\n\n## Practice\n1. Write a descriptive text about your best friend\n2. Write a descriptive text about your favorite place"
            );
        }

        // SEJARAH
        $sub = Subject::where('name','Sejarah')->where('kelas',$k)->first();
        if ($sub) {
            $this->m($sub, $k, 'Manusia dan Sejarah', 1, 'mudah', 20,
                'Konsep berpikir sejarah dan manusia sebagai makhluk sejarah.',
                "## Apa itu Sejarah?\n\nSejarah berasal dari bahasa Arab **syajaratun** yang berarti pohon. Sejarah adalah ilmu yang mempelajari peristiwa masa lalu manusia.\n\n## Konsep Berpikir Sejarah\n\n1. **Kronologis** - Urutan waktu peristiwa\n2. **Diakronik** - Memanjang dalam waktu\n3. **Sinkronik** - Melebar dalam ruang pada waktu tertentu\n4. **Kausalitas** - Hubungan sebab-akibat\n5. **Periodisasi** - Pembabakan waktu\n\n## Sumber Sejarah\n- **Sumber Primer**: Artefak asli, dokumen asli, saksi mata\n- **Sumber Sekunder**: Buku sejarah, artikel ilmiah\n- **Sumber Tersier**: Ensiklopedia, kamus sejarah\n\n## Langkah Penelitian Sejarah\n1. **Heuristik** - Pengumpulan sumber\n2. **Verifikasi/Kritik** - Menguji keabsahan sumber\n3. **Interpretasi** - Menafsirkan fakta\n4. **Historiografi** - Penulisan sejarah"
            );
        }
    }

    private function seed11SMA(): void
    {
        $k = '11-sma';

        $sub = Subject::where('name','Matematika')->where('kelas',$k)->first();
        if ($sub) {
            $this->m($sub, $k, 'Program Linear', 1, 'sedang', 35,
                'Menyelesaikan masalah optimasi dengan program linear.',
                "## Pengertian\n\nProgram linear adalah metode untuk menentukan **nilai optimum** (maksimum atau minimum) dari fungsi linear yang memenuhi serangkaian pertidaksamaan linear.\n\n## Langkah Penyelesaian\n\n1. Buat **model matematika** (pertidaksamaan)\n2. Gambar **daerah feasible** (daerah yang memenuhi semua pertidaksamaan)\n3. Tentukan **titik pojok** daerah feasible\n4. Substitusi titik pojok ke **fungsi tujuan**\n5. Pilih nilai **maksimum/minimum**\n\n## Contoh Soal\n\nTentukan nilai maksimum f(x,y) = 3x + 2y dengan syarat:\n- x + y ≤ 6\n- x + 2y ≤ 8\n- x ≥ 0, y ≥ 0\n\n**Penyelesaian:**\nTitik pojok: (0,0), (6,0), (4,2), (0,4)\n\n| Titik | f = 3x + 2y |\n|-------|-------------|\n| (0,0) | 0 |\n| (6,0) | 18 |\n| (4,2) | 16 |\n| (0,4) | 8 |\n\n**Nilai maksimum = 18** di titik (6,0)"
            );
        }

        $sub = Subject::where('name','Fisika')->where('kelas',$k)->first();
        if ($sub) {
            $this->m($sub, $k, 'Kesetimbangan dan Dinamika Rotasi', 1, 'sulit', 40,
                'Torsi, momen inersia, dan kesetimbangan benda tegar.',
                "## Torsi (Momen Gaya)\n\nTorsi adalah kemampuan gaya untuk memutar benda.\n\n**Rumus:** τ = F × r × sin θ\n- τ = torsi (N⋅m)\n- F = gaya (N)\n- r = jarak ke poros (m)\n- θ = sudut antara F dan r\n\n## Kesetimbangan\n\nSyarat kesetimbangan benda tegar:\n1. **ΣF = 0** (resultan gaya = 0)\n2. **Στ = 0** (resultan torsi = 0)\n\n## Momen Inersia\n\nAnalog massa pada gerak rotasi.\n- Partikel: I = m⋅r²\n- Silinder pejal: I = ½mr²\n- Bola pejal: I = ⅖mr²\n\n## Hukum II Newton Rotasi\n**Στ = I × α**\n- α = percepatan sudut (rad/s²)\n\n## Contoh\nBatang 2 m, massa 5 kg, ditumpu di tengah. Gaya 10 N di ujung kanan.\nτ = 10 × 1 = **10 N⋅m**"
            );
        }
    }

    private function seed7SMP(): void
    {
        $k = '7-smp';

        $sub = Subject::where('name','Matematika')->where('kelas',$k)->first();
        if ($sub) {
            $this->m($sub, $k, 'Bilangan Bulat', 1, 'mudah', 20,
                'Operasi hitung pada bilangan bulat positif dan negatif.',
                "## Bilangan Bulat\n\nBilangan bulat terdiri dari:\n- **Bilangan bulat positif**: 1, 2, 3, 4, ...\n- **Nol**: 0\n- **Bilangan bulat negatif**: -1, -2, -3, ...\n\n## Operasi Bilangan Bulat\n\n### Penjumlahan\n- Positif + Positif = Positif → 3 + 5 = **8**\n- Negatif + Negatif = Negatif → (-3) + (-5) = **-8**\n- Beda tanda = selisih, ambil tanda yang lebih besar → (-3) + 5 = **2**\n\n### Perkalian\n- (+) × (+) = (+) → 3 × 4 = **12**\n- (-) × (-) = (+) → (-3) × (-4) = **12**\n- (+) × (-) = (-) → 3 × (-4) = **-12**\n\n### Pembagian\nAturan tanda sama seperti perkalian.\n\n## Sifat-sifat Operasi\n- **Komutatif**: a + b = b + a\n- **Asosiatif**: (a + b) + c = a + (b + c)\n- **Distributif**: a × (b + c) = ab + ac\n\n## Latihan\n1. (-7) + 12 = ?\n2. (-4) × (-6) = ?\n3. 15 + (-20) = ?"
            );
        }

        $sub = Subject::where('name','IPA')->where('kelas',$k)->first();
        if ($sub) {
            $this->m($sub, $k, 'Objek IPA dan Pengamatannya', 1, 'mudah', 20,
                'Mengenal objek IPA, pengukuran, dan besaran.',
                "## Objek IPA\n\nIPA mempelajari fenomena alam melalui **pengamatan** dan **pengukuran**.\n\n## Pengukuran\n\nPengukuran adalah membandingkan besaran dengan **satuan** tertentu.\n\n### Alat Ukur\n| Besaran | Alat Ukur | Satuan |\n|---------|-----------|--------|\n| Panjang | Penggaris, jangka sorong | m, cm |\n| Massa | Timbangan | kg, g |\n| Waktu | Stopwatch | s |\n| Suhu | Termometer | °C |\n\n## Besaran Pokok dan Turunan\n\n**Besaran Pokok**: Panjang, massa, waktu, suhu, kuat arus, intensitas cahaya, jumlah zat\n\n**Besaran Turunan**: Luas (m²), volume (m³), kecepatan (m/s)\n\n## Langkah Metode Ilmiah\n1. Observasi (mengamati)\n2. Merumuskan masalah\n3. Membuat hipotesis\n4. Melakukan percobaan\n5. Menganalisis data\n6. Menarik kesimpulan"
            );
        }
    }

    /**
     * Helper: insert satu material.
     */
    private function m(Subject $sub, string $kelas, string $title, int $order, string $diff, int $mins, string $desc, string $content): void
    {
        Material::updateOrCreate(
            ['subject_id' => $sub->id, 'title' => $title],
            [
                'kelas' => $kelas,
                'description' => $desc,
                'content' => $content,
                'chapter_order' => $order,
                'difficulty' => $diff,
                'estimated_minutes' => $mins,
            ]
        );
    }
}
