<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Subject;
use App\Services\OpenRouterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudyPlanController extends Controller
{
    /**
     * Display the study plan page.
     */
    public function index()
    {
        $user = Auth::user();

        // Redirect if class is not selected
        if (!$user->kelas) {
            return redirect()->route('materials.pilih-kelas')
                ->with('info', 'Silakan pilih kelas Anda terlebih dahulu untuk memulai Rencana Belajar.');
        }

        $subjects = $user->subjects();
        
        // Fetch AI study tasks created for this week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $studyTasks = Task::forUser($user->id)
            ->where('type', 'study')
            ->whereBetween('due_date', [$startOfWeek, $endOfWeek])
            ->orderBy('due_date')
            ->orderBy('due_time')
            ->get();

        return view('study-plan.index', compact('user', 'subjects', 'studyTasks'));
    }

    /**
     * Generate personalized study plan using AI.
     */
    public function generate(Request $request)
    {
        set_time_limit(120);
        ini_set('default_socket_timeout', 90);

        $user = Auth::user();
        if (!$user->kelas) {
            return redirect()->route('materials.pilih-kelas');
        }

        $subjects = $user->subjects()->pluck('name')->toArray();
        $subjectsList = implode(', ', $subjects);
        $kelasLabel = $user->kelas_label;

        // Calculate dates of the current week to pass to the AI
        $today = Carbon::now();
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $day = Carbon::now()->startOfWeek()->addDays($i);
            $dayName = match($i) {
                0 => 'Senin',
                1 => 'Selasa',
                2 => 'Rabu',
                3 => 'Kamis',
                4 => 'Jumat',
                5 => 'Sabtu',
                6 => 'Minggu',
            };
            $dates[$dayName] = $day->format('Y-m-d');
        }

        $datesString = "";
        foreach ($dates as $name => $date) {
            $datesString .= "- {$name}: {$date}\n";
        }

        $prompt = "Kamu wajib membuat Rencana Belajar Mingguan yang dipersonalisasi dan komprehensif dalam Bahasa Indonesia untuk siswa tingkat '{$kelasLabel}'.
Siswa ini mengambil mata pelajaran: {$subjectsList}.
Hari ini adalah tanggal {$today->format('Y-m-d')}. Berikut adalah daftar tanggal untuk minggu ini:
{$datesString}

PENTING & HARUS DIPATUHI:
1. Buat rencana belajar mingguan yang logis, berimbang, dan spesifik untuk setiap mata pelajaran di atas.
2. Keluaran wajib dibagi menjadi dua bagian dengan pemisah khusus:
   Bagian 1: Roadmap Rencana Belajar (dalam format Markdown). Tuliskan ringkasan rencana, tips sukses, dan gol pembelajaran minggu ini.
   Bagian 2: Daftar Tugas Belajar otomatis (dalam format JSON murni). Daftar ini akan otomatis dimasukkan ke sistem kalender/task manager siswa.

Format respon wajib persis seperti berikut (jangan tambahkan teks pembuka atau penutup di luar tag):

:::roadmap
# 🎯 Rencana Belajar Mingguan: [Nama Kelas/Jenjang]
[Berikan deskripsi ringkas, motivasi, dan gol besar untuk minggu ini]

## 📅 Jadwal Harian & Fokus Materi
- **Senin**: [Fokus materi mapel X]
- **Selasa**: [Fokus materi mapel Y]
[dan seterusnya sampai Minggu]

## 💡 Tips Belajar Minggu Ini
- **Tip 1**: [Tuliskan tip praktis]
- **Tip 2**: [Tuliskan tip praktis lainnya]
:::

:::tasks
[
  {
    \"title\": \"Belajar [Topik Spesifik Mapel 1]\",
    \"subject\": \"[Nama Mapel yang Valid]\",
    \"due_date\": \"[YYYY-MM-DD dari daftar tanggal di atas]\",
    \"due_time\": \"[HH:MM, gunakan waktu sore/malam misal 16:00 atau 19:00]\",
    \"description\": \"Pelajari konsep [Topik], catat rumus penting, dan baca bab materi di Sinau.\"
  },
  {
    \"title\": \"Latihan Soal [Topik Spesifik Mapel 2]\",
    \"subject\": \"[Nama Mapel yang Valid]\",
    \"due_date\": \"[YYYY-MM-DD dari daftar tanggal di atas]\",
    \"due_time\": \"[HH:MM]\",
    \"description\": \"Kerjakan latihan soal mengenai [Topik] dan gunakan Quiz Generator di Sinau.\"
  }
]
:::

Ketentuan Daftar Tugas Belajar (tasks):
- Buatkan 4 sampai 6 tugas belajar mandiri yang bervariasi sepanjang minggu ini.
- Gunakan tanggal yang benar dari daftar tanggal minggu ini yang disediakan di atas.
- Pastikan field 'subject' berisi salah satu dari mata pelajaran valid ini: {$subjectsList}.
- Balas HANYA dengan struktur di atas tanpa markdown kode block (```json atau ```markdown).";

        try {
            $openRouterService = app(OpenRouterService::class);
            $systemInstruction = "Kamu adalah Konselor Akademik dan Asisten Kurikulum AI Sinau yang sangat ahli, penyayang, dan terstruktur. Tugasmu adalah mendesain jadwal belajar mingguan yang dipersonalisasi, akurat secara akademis, realistis, dan memotivasi siswa untuk berprestasi.";

            $aiResponse = $openRouterService->generateContent([
                ['role' => 'user', 'parts' => [['text' => $prompt]]]
            ], $systemInstruction);

            // Parse response
            $roadmapText = "";
            $tasksJson = "";

            if (preg_match('/:::roadmap\s*(.*?)\s*:::tasks/s', $aiResponse, $matchesRoadmap)) {
                $roadmapText = trim($matchesRoadmap[1]);
            }

            if (preg_match('/:::tasks\s*(.*?)\s*:::/s', $aiResponse, $matchesTasks)) {
                $tasksJson = trim($matchesTasks[1]);
            } else if (preg_match('/:::tasks\s*(.*)/s', $aiResponse, $matchesTasks)) {
                $tasksJson = trim($matchesTasks[1]);
            }

            // Fallback parsing if formatting has slight variations
            if (empty($roadmapText)) {
                $parts = explode(':::tasks', $aiResponse);
                $roadmapText = str_replace(':::roadmap', '', $parts[0] ?? '');
                $tasksJson = $parts[1] ?? '';
                $tasksJson = str_replace(':::', '', $tasksJson);
            }

            $roadmapText = trim($roadmapText);
            $tasksJson = trim($tasksJson);

            // Clean json format
            $tasksJson = preg_replace('/```json\s*|```/', '', $tasksJson);
            $decodedTasks = json_decode($tasksJson, true);

            if ($decodedTasks === null) {
                // If direct decode failed, try cleaning curly quotes
                $cleanedJson = str_replace(['“', '”', '‟', '″', '″'], '"', $tasksJson);
                $cleanedJson = preg_replace('/,\s*([\]}])/m', '$1', $cleanedJson);
                $decodedTasks = json_decode($cleanedJson, true);
            }

            // Save Roadmap to user
            $user->update([
                'study_plan' => $roadmapText ?: $aiResponse,
                'study_plan_generated_at' => now(),
            ]);

            // Save tasks to tasks table
            if (is_array($decodedTasks)) {
                // Delete old study tasks generated for this week to avoid duplicates
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                Task::forUser($user->id)
                    ->where('type', 'study')
                    ->where('is_ai', true)
                    ->whereBetween('due_date', [$startOfWeek, $endOfWeek])
                    ->delete();

                foreach ($decodedTasks as $taskItem) {
                    Task::create([
                        'user_id' => $user->id,
                        'title' => $taskItem['title'] ?? 'Belajar Mandiri',
                        'description' => $taskItem['description'] ?? 'Belajar topik mandiri dengan bantuan Sinau.',
                        'type' => 'study',
                        'status' => 'todo',
                        'priority' => 'medium',
                        'due_date' => isset($taskItem['due_date']) ? Carbon::parse($taskItem['due_date'])->toDateString() : now()->toDateString(),
                        'due_time' => $taskItem['due_time'] ?? '16:00',
                        'subject' => $taskItem['subject'] ?? 'Umum',
                        'is_ai' => true,
                        'progress_percent' => 0,
                    ]);
                }
            }

            return redirect()->route('study-plan.index')
                ->with('success', '✨ Rencana belajar mingguan berhasil dibuat dan ditambahkan ke Kalender Pintarmu!');

        } catch (\Exception $e) {
            Log::error("Study Plan Generator Failure: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->route('study-plan.index')
                ->with('error', 'Gagal merancang rencana belajar: ' . $e->getMessage());
        }
    }
}
