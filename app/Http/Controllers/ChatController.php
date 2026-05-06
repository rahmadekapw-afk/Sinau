<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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

        // Retrieve full history for context
        $histories = ChatHistory::where('user_id', $userId)
            ->where('session_id', $sessionId)
            ->orderBy('created_at')
            ->get();

        $contents = $histories->map(fn($h) => [
            'role'  => $h->role,
            'parts' => [['text' => $h->message]],
        ])->values()->toArray();

        // Call Gemini API
        $apiKey = config('services.gemini.api_key');
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                'system_instruction' => [
                    'parts' => [['text' => 'Kamu adalah asisten belajar AI bernama "Sinau". Tugasmu membantu pelajar Indonesia belajar dengan cara yang menyenangkan, jelas, dan mudah dipahami. Selalu gunakan Bahasa Indonesia yang ramah dan supportif. Jika relevan, berikan contoh nyata, analogi, atau penjelasan bertahap.']]
                ],
                'contents' => $contents,
            ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal menghubungi AI. Cek API key Anda.'], 500);
        }

        $aiText = $response->json('candidates.0.content.parts.0.text') ?? 'Maaf, saya tidak bisa memberikan respons saat ini.';

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
            ->select('session_id')
            ->distinct()
            ->withMax('created_at', 'created_at')
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
}
