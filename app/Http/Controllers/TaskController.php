<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Tasks Page — Kanban board grouped by status.
     */
    public function index()
    {
        $tasks = Task::forUser(Auth::id())
            ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 ELSE 4 END")
            ->orderBy('due_date')
            ->get();

        $todoTasks = $tasks->where('status', 'todo');
        $progressTasks = $tasks->where('status', 'progress');
        $doneTasks = $tasks->where('status', 'done');

        return view('tasks.index', compact('todoTasks', 'progressTasks', 'doneTasks'));
    }

    /**
     * Calendar Page — Monthly grid with tasks as events.
     */
    public function calendar(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Tasks milik user dalam bulan ini
        $tasks = Task::forUser(Auth::id())
            ->inDateRange($startOfMonth, $endOfMonth)
            ->orderBy('due_date')
            ->orderBy('due_time')
            ->get();

        // Group tasks berdasarkan tanggal (key = 'Y-m-d')
        $tasksGrouped = $tasks->groupBy(fn($task) => $task->due_date->format('Y-m-d'));

        // Data calendar
        $daysInMonth = $startOfMonth->daysInMonth;
        $firstDayOfWeek = $startOfMonth->dayOfWeek; // 0=Minggu, 1=Senin, ...
        $today = now()->toDateString();

        // Upcoming tasks (untuk sidebar / widget)
        $upcomingTasks = Task::forUser(Auth::id())
            ->upcoming()
            ->limit(5)
            ->get();

        return view('calendar.index', compact(
            'month', 'year', 'startOfMonth', 'endOfMonth',
            'tasksGrouped', 'daysInMonth', 'firstDayOfWeek',
            'today', 'upcomingTasks'
        ));
    }

    /**
     * Store a new task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:deadline,exam,study',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable|date_format:H:i',
            'subject' => 'nullable|string|max:100',
            'is_ai' => 'nullable|boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'todo';
        if ($request->has('is_ai')) {
            $validated['is_ai'] = (bool) $request->input('is_ai');
        }

        $task = Task::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil ditambahkan oleh AI!',
                'task' => $task
            ]);
        }

        // Redirect berdasarkan dari mana user datang
        if ($request->has('from_calendar')) {
            return redirect()->route('calendar.index', [
                'month' => $request->get('month', now()->month),
                'year' => $request->get('year', now()->year),
            ])->with('success', 'Jadwal berhasil ditambahkan!');
        }

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan!');
    }

    /**
     * Update task status (for kanban drag & drop or quick update).
     */
    public function update(Request $request, Task $task)
    {
        // Pastikan task milik user
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|in:deadline,exam,study',
            'status' => 'sometimes|in:todo,progress,done',
            'priority' => 'sometimes|in:low,medium,high',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable|date_format:H:i',
            'subject' => 'nullable|string|max:100',
            'progress_percent' => 'sometimes|integer|min:0|max:100',
        ]);

        // Jika status berubah ke done, set completed_at
        if (isset($validated['status']) && $validated['status'] === 'done') {
            $validated['completed_at'] = now();
            $validated['progress_percent'] = 100;
        }

        $task->update($validated);

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Submit jawaban tugas (dari kolom Sedang Dikerjakan).
     * Setelah submit, status otomatis berubah ke 'done'.
     */
    public function submitAnswer(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'submission' => 'required|string|min:5',
        ]);

        $task->update([
            'submission'      => $request->input('submission'),
            'submitted_at'    => now(),
            'status'          => 'done',
            'progress_percent'=> 100,
            'completed_at'    => now(),
        ]);

        return redirect()->route('tasks.index')
            ->with('success', '✅ Jawaban berhasil dikumpulkan! Tugas dipindahkan ke Selesai.');
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->back()->with('success', 'Tugas berhasil dihapus!');
    }

    /**
     * Generate AI study materials/assignments for a given task.
     */
    public function generateMaterials(Request $request, Task $task)
    {
        // Pastikan task milik user
        if ($task->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'error' => 'Akses ditolak.'], 403);
        }

        // Jika sudah ada ai_content dan tidak meminta regenerasi, langsung kembalikan
        if (!empty($task->ai_content) && !$request->input('regenerate')) {
            return response()->json([
                'success' => true,
                'ai_content' => $task->ai_content
            ]);
        }

        set_time_limit(120);

        try {
            $openRouterService = app(\App\Services\OpenRouterService::class);
            
            $kelasLabel = \App\Models\Subject::kelasLabel(Auth::user()->kelas ?? 'SMP/SMA');

            $prompt = "Kamu wajib membuat lembar belajar mandiri yang nyata, komprehensif, akademis, dan berkualitas tinggi dalam Bahasa Indonesia untuk jenjang {$kelasLabel}.
Tugas yang harus dibahas:
- Judul Tugas: \"{$task->title}\"
- Mata Pelajaran: \"" . ($task->subject ?: 'Umum') . "\"
- Jenjang Kelas: \"{$kelasLabel}\"
- Deskripsi Awal: \"" . ($task->description ?: 'Belajar mandiri') . "\"

PENTING & HARUS DIPATUHI:
1. Analisis Judul Tugas secara teliti. Jika ada kesalahan ketik (seperti 'aritamtika' yang seharusnya 'aritmatika'), koreksi secara otomatis dan buat materi berdasarkan topik akademik yang benar.
2. DILARANG menggunakan teks umum atau kalimat placeholder kosong (seperti 'Penjelasan teori ini adalah bagian...'). Kamu harus menyajikan penjelasan materi nyata secara mendalam, rumus matematika/sains yang relevan, definisi konkret, langkah logis, dan contoh riil yang sesuai dengan kurikulum {$kelasLabel}.
3. LARANGAN SINTAKS LATEX: DILARANG KERAS menggunakan simbol dollar ($) atau format LaTeX dalam rumus matematika. Tulis rumus atau hitungan matematika dengan teks biasa yang bersih, rapi, dan mudah dibaca. Gunakan simbol 'x' untuk perkalian, ':' untuk pembagian, '+' untuk penjumlahan, '-' untuk pengurangan, serta tanda kurung '( )' jika diperlukan.
4. Format 3 Latihan Soal harus berupa Pilihan Ganda (A, B, C, D) yang memiliki bobot kesulitan kurikulum {$kelasLabel}.
5. PENTING: DILARANG KERAS memasukkan kunci jawaban, solusi, pembetulan, atau pembahasan dalam bentuk apa pun (baik tertulis langsung maupun menggunakan spoiler/dropdown). Tugas ini bertujuan untuk menguji pemahaman siswa secara mandiri, sehingga hanya boleh berisi soal-soal latihan saja tanpa jawaban.
6. Struktur keluaran wajib menggunakan format Markdown yang rapi berikut:

# 📖 Rangkuman Konsep: [Nama Topik Yang Benar]
[Tuliskan teori dasar, penjelasan konsep secara ilmiah namun mudah dimengerti, lengkap dengan rumus dan ilustrasi logika jika relevan]

# 💡 Langkah-langkah Pemecahan Masalah / Cara Belajar
- **Langkah 1**: [Jelaskan langkah pertama untuk menyelesaikan masalah atau memahami topik ini]
- **Langkah 2**: [Jelaskan langkah kedua secara berurutan]
- **Langkah 3**: [Jelaskan langkah ketiga secara berurutan]

# 📝 Latihan Soal Mandiri

**Soal 1**: [Pertanyaan latihan soal pertama]
A. [Pilihan jawaban A]
B. [Pilihan jawaban B]
C. [Pilihan jawaban C]
D. [Pilihan jawaban D]

**Soal 2**: [Pertanyaan latihan soal kedua]
A. [Pilihan jawaban A]
B. [Pilihan jawaban B]
C. [Pilihan jawaban C]
D. [Pilihan jawaban D]

**Soal 3**: [Pertanyaan latihan soal ketiga]
A. [Pilihan jawaban A]
B. [Pilihan jawaban B]
C. [Pilihan jawaban C]
D. [Pilihan jawaban D]

Harap balas HANYA dengan konten utama tersebut dalam format Markdown tanpa kalimat pembuka (\"Tentu, ini tugasnya...\") atau kalimat penutup.";

            $systemInstruction = "Kamu adalah asisten guru AI Sinau yang ahli, akurat, dan berdedikasi tinggi. Tugasmu adalah memberikan konten materi pelajaran sekolah yang konkret, akurat secara akademis, bebas typo, dan berstruktur Markdown rapi.";

            $aiResponse = $openRouterService->generateContent([
                ['role' => 'user', 'parts' => [['text' => $prompt]]]
            ], $systemInstruction);

            // Simpan hasil ke database agar tidak perlu generate ulang
            $task->update([
                'ai_content' => $aiResponse
            ]);

            return response()->json([
                'success' => true,
                'ai_content' => $aiResponse
            ]);

        } catch (\Exception $e) {
            \Log::error("AI Task Materials Generation Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => 'Gagal meminta AI menyiapkan tugas: ' . $e->getMessage()
            ], 500);
        }
    }
}
