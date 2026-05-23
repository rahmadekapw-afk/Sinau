<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Seed subjects (mata pelajaran) untuk semua kelas.
     */
    public function run(): void
    {
        $subjects = [
            // ==================== KELAS 10 SMA ====================
            ['name' => 'Matematika',       'kelas' => '10-sma', 'icon' => '📐', 'color' => 'blue',    'description' => 'Aljabar, geometri, trigonometri, dan statistika dasar',  'sort_order' => 1],
            ['name' => 'Fisika',           'kelas' => '10-sma', 'icon' => '⚛️', 'color' => 'purple',  'description' => 'Mekanika, kinematika, dan hukum Newton',                'sort_order' => 2],
            ['name' => 'Kimia',            'kelas' => '10-sma', 'icon' => '🧪', 'color' => 'green',   'description' => 'Struktur atom, tabel periodik, dan ikatan kimia',       'sort_order' => 3],
            ['name' => 'Biologi',          'kelas' => '10-sma', 'icon' => '🧬', 'color' => 'emerald', 'description' => 'Keanekaragaman hayati, sel, dan klasifikasi makhluk hidup','sort_order' => 4],
            ['name' => 'Bahasa Indonesia', 'kelas' => '10-sma', 'icon' => '📖', 'color' => 'red',     'description' => 'Teks laporan, eksposisi, dan anekdot',                   'sort_order' => 5],
            ['name' => 'Bahasa Inggris',   'kelas' => '10-sma', 'icon' => '🌍', 'color' => 'indigo',  'description' => 'Narrative, descriptive, dan recount text',               'sort_order' => 6],
            ['name' => 'Sejarah',          'kelas' => '10-sma', 'icon' => '🏛️', 'color' => 'amber',   'description' => 'Manusia dan sejarah, peradaban awal',                   'sort_order' => 7],

            // ==================== KELAS 11 SMA ====================
            ['name' => 'Matematika',       'kelas' => '11-sma', 'icon' => '📐', 'color' => 'blue',    'description' => 'Induksi, program linear, matriks, dan limit',           'sort_order' => 1],
            ['name' => 'Fisika',           'kelas' => '11-sma', 'icon' => '⚛️', 'color' => 'purple',  'description' => 'Dinamika rotasi, fluida, dan gelombang',                'sort_order' => 2],
            ['name' => 'Kimia',            'kelas' => '11-sma', 'icon' => '🧪', 'color' => 'green',   'description' => 'Termokimia, laju reaksi, dan kesetimbangan',            'sort_order' => 3],
            ['name' => 'Biologi',          'kelas' => '11-sma', 'icon' => '🧬', 'color' => 'emerald', 'description' => 'Sistem organ, transportasi, dan reproduksi',             'sort_order' => 4],
            ['name' => 'Bahasa Indonesia', 'kelas' => '11-sma', 'icon' => '📖', 'color' => 'red',     'description' => 'Teks prosedur, eksplanasi, ceramah, dan cerpen',         'sort_order' => 5],
            ['name' => 'Bahasa Inggris',   'kelas' => '11-sma', 'icon' => '🌍', 'color' => 'indigo',  'description' => 'Analytical exposition, explanation text',               'sort_order' => 6],

            // ==================== KELAS 12 SMA ====================
            ['name' => 'Matematika',       'kelas' => '12-sma', 'icon' => '📐', 'color' => 'blue',    'description' => 'Integral, dimensi tiga, dan statistika lanjut',         'sort_order' => 1],
            ['name' => 'Fisika',           'kelas' => '12-sma', 'icon' => '⚛️', 'color' => 'purple',  'description' => 'Listrik, magnet, fisika modern, dan inti atom',         'sort_order' => 2],
            ['name' => 'Kimia',            'kelas' => '12-sma', 'icon' => '🧪', 'color' => 'green',   'description' => 'Sifat koligatif, elektrokimia, makromolekul',            'sort_order' => 3],
            ['name' => 'Biologi',          'kelas' => '12-sma', 'icon' => '🧬', 'color' => 'emerald', 'description' => 'Genetika, evolusi, dan bioteknologi',                   'sort_order' => 4],

            // ==================== KELAS 7 SMP ====================
            ['name' => 'Matematika',       'kelas' => '7-smp',  'icon' => '📐', 'color' => 'blue',    'description' => 'Bilangan bulat, pecahan, aljabar dasar',                'sort_order' => 1],
            ['name' => 'IPA',              'kelas' => '7-smp',  'icon' => '🔬', 'color' => 'green',   'description' => 'Objek IPA, klasifikasi makhluk hidup, dan zat',          'sort_order' => 2],
            ['name' => 'Bahasa Indonesia', 'kelas' => '7-smp',  'icon' => '📖', 'color' => 'red',     'description' => 'Teks deskripsi, narasi, dan prosedur',                   'sort_order' => 3],
            ['name' => 'Bahasa Inggris',   'kelas' => '7-smp',  'icon' => '🌍', 'color' => 'indigo',  'description' => 'Self-introduction, describing things',                  'sort_order' => 4],
            ['name' => 'IPS',              'kelas' => '7-smp',  'icon' => '🌏', 'color' => 'amber',   'description' => 'Manusia, tempat, dan lingkungan',                        'sort_order' => 5],

            // ==================== KELAS 8 SMP ====================
            ['name' => 'Matematika',       'kelas' => '8-smp',  'icon' => '📐', 'color' => 'blue',    'description' => 'Sistem koordinat, relasi fungsi, SPLDV',                'sort_order' => 1],
            ['name' => 'IPA',              'kelas' => '8-smp',  'icon' => '🔬', 'color' => 'green',   'description' => 'Gerak, gaya, tekanan, dan sistem pencernaan',            'sort_order' => 2],
            ['name' => 'Bahasa Indonesia', 'kelas' => '8-smp',  'icon' => '📖', 'color' => 'red',     'description' => 'Teks berita, iklan, eksposisi, puisi',                   'sort_order' => 3],
            ['name' => 'Bahasa Inggris',   'kelas' => '8-smp',  'icon' => '🌍', 'color' => 'indigo',  'description' => 'Asking for attention, opinions, recount text',           'sort_order' => 4],

            // ==================== KELAS 9 SMP ====================
            ['name' => 'Matematika',       'kelas' => '9-smp',  'icon' => '📐', 'color' => 'blue',    'description' => 'Pangkat, akar, bangun ruang, dan transformasi',          'sort_order' => 1],
            ['name' => 'IPA',              'kelas' => '9-smp',  'icon' => '🔬', 'color' => 'green',   'description' => 'Listrik, magnet, pewarisan sifat, bioteknologi',         'sort_order' => 2],
            ['name' => 'Bahasa Indonesia', 'kelas' => '9-smp',  'icon' => '📖', 'color' => 'red',     'description' => 'Teks cerita pendek, tanggapan, diskusi',                 'sort_order' => 3],
            ['name' => 'Bahasa Inggris',   'kelas' => '9-smp',  'icon' => '🌍', 'color' => 'indigo',  'description' => 'Report text, procedure text, narrative',                'sort_order' => 4],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['name' => $subject['name'], 'kelas' => $subject['kelas']],
                $subject
            );
        }
    }
}
