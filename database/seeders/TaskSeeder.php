<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Seed sample tasks untuk demo integrasi Tasks ↔ Calendar.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $tasks = [
            // === TODO ===
            [
                'title' => 'Mengerjakan PR Matematika Bab 4',
                'description' => 'Latihan soal halaman 45 nomor 1-10. Jangan lupa pelajari rumus phytagoras.',
                'type' => 'deadline',
                'status' => 'todo',
                'priority' => 'high',
                'due_date' => now()->toDateString(),
                'subject' => 'Matematika',
            ],
            [
                'title' => 'Cari 3 Jurnal Referensi Kemerdekaan',
                'description' => 'Langkah 1 dari "Makalah Sejarah". Pastikan sumber dari Google Scholar yang diterbitkan 5 tahun terakhir.',
                'type' => 'study',
                'status' => 'todo',
                'priority' => 'medium',
                'due_date' => now()->addDay()->toDateString(),
                'subject' => 'Sejarah',
                'is_ai' => true,
            ],
            [
                'title' => 'Kumpul Makalah Sejarah',
                'description' => 'Makalah tentang sejarah kemerdekaan Indonesia, minimal 10 halaman.',
                'type' => 'deadline',
                'status' => 'todo',
                'priority' => 'high',
                'due_date' => now()->addDays(5)->toDateString(),
                'subject' => 'Sejarah',
            ],

            // === IN PROGRESS ===
            [
                'title' => 'Baca Bab 3: Sistem Ekskresi',
                'description' => 'Biologi - Persiapan ulangan harian jumat ini. Buat ringkasan mind map.',
                'type' => 'study',
                'status' => 'progress',
                'priority' => 'medium',
                'due_date' => now()->addDays(3)->toDateString(),
                'subject' => 'Biologi',
                'progress_percent' => 45,
            ],

            // === DONE ===
            [
                'title' => 'Resume Bahasa Inggris',
                'description' => 'Chapter 5: Narrative Text summary.',
                'type' => 'deadline',
                'status' => 'done',
                'priority' => 'medium',
                'due_date' => now()->subDay()->toDateString(),
                'subject' => 'Bahasa Inggris',
                'progress_percent' => 100,
                'completed_at' => now()->subDay(),
            ],
            [
                'title' => 'Kuis Harian Geografi',
                'description' => 'Bab 2: Dinamika Atmosfer.',
                'type' => 'exam',
                'status' => 'done',
                'priority' => 'medium',
                'due_date' => now()->subDays(3)->toDateString(),
                'subject' => 'Geografi',
                'progress_percent' => 100,
                'completed_at' => now()->subDays(3),
            ],

            // === EXTRA EVENTS (muncul di calendar) ===
            [
                'title' => 'Ujian Biologi',
                'description' => 'Ulangan harian Bab 3 - Sistem Ekskresi.',
                'type' => 'exam',
                'status' => 'todo',
                'priority' => 'high',
                'due_date' => now()->addDays(7)->toDateString(),
                'due_time' => '08:00',
                'subject' => 'Biologi',
            ],
            [
                'title' => 'AI: Review Bab 1 Fisika',
                'description' => 'Sesi review menggunakan spaced repetition yang dijadwalkan AI.',
                'type' => 'study',
                'status' => 'todo',
                'priority' => 'medium',
                'due_date' => now()->addDays(4)->toDateString(),
                'due_time' => '14:00',
                'subject' => 'Fisika',
                'is_ai' => true,
            ],
            [
                'title' => 'AI: Latihan Soal Matematika',
                'description' => 'Latihan soal otomatis yang dibuatkan AI untuk persiapan ujian.',
                'type' => 'study',
                'status' => 'todo',
                'priority' => 'medium',
                'due_date' => now()->addDays(14)->toDateString(),
                'subject' => 'Matematika',
                'is_ai' => true,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create(array_merge($task, ['user_id' => $user->id]));
        }
    }
}
