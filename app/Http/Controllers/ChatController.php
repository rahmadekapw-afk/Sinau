<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index()
    {
        $sessionId = session('chat_session_id', Str::uuid());
        session(['chat_session_id' => $sessionId]);

        $histories = ChatHistory::where('user_id', Auth::id())
            ->where('session_id', $sessionId)
            ->orderBy('created_at')
            ->get();

        return view('chat.index', compact('histories', 'sessionId'));
    }

    public function send(Request $request)
    {
        // Extend PHP execution time to 120s — OpenRouter free tier can take 20-30s
        set_time_limit(120);
        ini_set('default_socket_timeout', 90);

        $request->validate(['message' => 'required|string|max:2000']);

        $sessionId = session('chat_session_id', Str::uuid());
        session(['chat_session_id' => $sessionId]);
        $userId = Auth::id();

        // Save user message
        ChatHistory::create([
            'user_id'    => $userId,
            'session_id' => $sessionId,
            'role'       => 'user',
            'message'    => $request->message,
        ]);

        // Retrieve last 10 messages for context (keep payload small & fast)
        $histories = ChatHistory::where('user_id', $userId)
            ->where('session_id', $sessionId)
            ->latest()
            ->limit(10)
            ->get()
            ->sortBy('created_at')
            ->values();

        $contents = $histories->map(fn($h) => [
            'role'  => $h->role,
            'parts' => [['text' => $h->message]],
        ])->values()->toArray();

        // Call OpenRouter/Ollama API using the robust OpenRouterService
        try {
            $openRouterService = app(\App\Services\OpenRouterService::class);
            $systemInstruction = 'Kamu adalah asisten belajar AI bernama "Sinau". Tugasmu membantu pelajar Indonesia belajar dengan cara yang menyenangkan, jelas, dan mudah dipahami. Selalu gunakan Bahasa Indonesia yang ramah dan supportif. Jika relevan, berikan contoh nyata, analogi, atau penjelasan bertahap.';
            $aiText = $openRouterService->generateContent($contents, $systemInstruction);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::critical("OpenRouter/Ollama Chat Failure: " . $e->getMessage());
            return response()->json([
                'error' => 'Server sedang mengalami gangguan atau limit kuota AI terlampaui. Silakan coba beberapa saat lagi atau hubungi administrator.'
            ], 500);
        }

        // Save AI response
        ChatHistory::create([
            'user_id'    => $userId,
            'session_id' => $sessionId,
            'role'       => 'model',
            'message'    => $aiText,
        ]);

        return response()->json(['message' => $aiText]);
    }

    public function newSession()
    {
        session(['chat_session_id' => Str::uuid()]);
        return redirect()->route('chat.index');
    }

    public function history()
    {
        $userId = Auth::id();
        $sessions = ChatHistory::where('user_id', $userId)
            ->where('session_id', 'not like', 'bot_%')
            ->selectRaw('session_id, MAX(created_at) as max_created_at')
            ->groupBy('session_id')
            ->orderByDesc('max_created_at')
            ->get();

        $sessionData = $sessions->map(function ($s) use ($userId) {
            $first = ChatHistory::where('user_id', $userId)
                ->where('session_id', $s->session_id)
                ->where('role', 'user')
                ->orderBy('created_at')
                ->first();
            return [
                'session_id' => $s->session_id,
                'preview'    => $first ? Str::limit($first->message, 60) : 'Sesi baru',
                'created_at' => $s->max_created_at,
            ];
        });

        return view('chat.history', compact('sessionData'));
    }

    public function loadSession($sessionId)
    {
        $userId = Auth::id();
        $exists = ChatHistory::where('user_id', $userId)
            ->where('session_id', $sessionId)
            ->exists();

        if (! $exists) {
            abort(403);
        }

        session(['chat_session_id' => $sessionId]);
        return redirect()->route('chat.index');
    }

    public function chatbotSend(Request $request)
    {
        // Extend PHP execution time to 120s — OpenRouter free tier can take 20-30s
        set_time_limit(120);
        ini_set('default_socket_timeout', 90);

        $request->validate(['message' => 'required|string|max:1000']);

        // Separate chatbot session from academic chat session using 'bot_' prefix
        $sessionId = session('chatbot_session_id', 'bot_' . Str::uuid());
        session(['chatbot_session_id' => $sessionId]);
        $userId = Auth::id();

        // Save user message
        ChatHistory::create([
            'user_id'    => $userId,
            'session_id' => $sessionId,
            'role'       => 'user',
            'message'    => $request->message,
        ]);

        // Retrieve last 10 messages for chatbot context
        $histories = ChatHistory::where('user_id', $userId)
            ->where('session_id', $sessionId)
            ->latest()
            ->limit(10)
            ->get()
            ->sortBy('created_at')
            ->values();

        $contents = $histories->map(fn($h) => [
            'role'  => $h->role,
            'parts' => [['text' => $h->message]],
        ])->values()->toArray();

        // Call OpenRouterService with specific platform support chatbot prompt
        try {
            $openRouterService = app(\App\Services\OpenRouterService::class);
            $systemInstruction = 'Kamu adalah "Sinau Bot", asisten virtual pintar khusus untuk platform belajar "Sinau". Tugas utamamu adalah membantu pengguna memahami dan menavigasi fitur platform Sinau, yaitu:
1. Dashboard: Menampilkan daftar tugas hari ini, jadwal belajar, dan ringkasan materi belajar berdasarkan kelas.
2. Tugas & Kanban Board: Mengelola tugas belajar (tambah, edit, geser kolom, hapus) untuk merencanakan studi.
3. Kalender: Melihat tanggal penting dan batas waktu tugas secara visual.
4. Analytics: Grafik performa belajar, statistik pengerjaan tugas, dan analisis fokus belajar mingguan.
5. Word to PDF Converter: Alat konversi file dokumen .docx ke format .pdf secara instan.
6. Billing & Pembayaran: Informasi paket langganan, invoice, dan status akun premium.
7. Settings: Mengedit informasi akun, kelas sekolah (SMP/SMA), dan kata sandi.
8. Support: Mengirimkan keluhan teknis atau mengajukan tiket bantuan kepada tim Admin.

Jawablah setiap pertanyaan dengan sangat ramah, ringkas, dan fokus pada solusi navigasi platform. Jika pengguna menanyakan materi pelajaran sekolah akademis (seperti rumus fisika, matematika, bantuan PR bahasa Inggris, atau sejarah), berikan jawaban super singkat lalu rekomendasikan mereka menggunakan fitur "Tanya AI" (Chat AI Akademik) pada menu utama di sidebar kiri untuk belajar interaktif yang mendalam.';

            $aiText = $openRouterService->generateContent($contents, $systemInstruction);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::critical("OpenRouter Chatbot Failure: " . $e->getMessage());
            return response()->json([
                'error' => 'Maaf, saya sedang mengalami kendala koneksi. Silakan coba lagi.'
            ], 500);
        }

        // Save AI response
        ChatHistory::create([
            'user_id'    => $userId,
            'session_id' => $sessionId,
            'role'       => 'model',
            'message'    => $aiText,
        ]);

        return response()->json(['message' => $aiText]);
    }
}
