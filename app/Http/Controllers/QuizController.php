<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Services\OpenRouterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // If user has no class set, redirect them to select it first
        if (!$user->kelas) {
            return redirect()->route('materials.pilih-kelas')->with('info', 'Silakan pilih kelas Anda terlebih dahulu untuk memulai Quiz.');
        }

        // Get subjects for this class
        $subjects = Subject::forKelas($user->kelas)->orderBy('sort_order')->get();

        // If no subjects exist, fetch default ones
        if ($subjects->isEmpty()) {
            $subjects = Subject::orderBy('sort_order')->limit(5)->get();
        }

        return view('quiz.index', compact('subjects'));
    }

    public function generate(Request $request)
    {
        // Extend execution time as generation might take up to 20-30s on free tiers
        set_time_limit(120);
        ini_set('default_socket_timeout', 90);

        $request->validate([
            'subject_id' => 'required|string',
            'difficulty' => 'required|string|in:mudah,sedang,sulit',
            'count'      => 'required|integer|in:5,10,15',
        ]);

        $subjectName = $request->subject_id;
        if (is_numeric($request->subject_id)) {
            $subject = Subject::find($request->subject_id);
            if ($subject) {
                $subjectName = $subject->name;
            }
        }

        $difficulty = ucfirst($request->difficulty);
        $count = $request->count;
        $kelasLabel = Subject::kelasLabel(Auth::user()->kelas ?? 'SMP/SMA');

        // Design strict prompt for OpenRouter to output JSON format
        $prompt = "Buatkan {$count} soal pilihan ganda (A, B, C, D) untuk mata pelajaran '{$subjectName}' tingkat '{$kelasLabel}' dengan tingkat kesulitan '{$difficulty}'. 
Harus ditulis dalam Bahasa Indonesia yang baik dan benar, sesuai kurikulum nasional.

Kembalikan respon HANYA berupa array JSON yang valid tanpa penjelasan pembuka, tanpa kata penutup, dan tanpa format markdown atau blok kode. Format JSON harus persis seperti skema berikut:
[
  {
    \"question\": \"Pertanyaan soal...\",
    \"options\": {
      \"A\": \"Pilihan jawaban A\",
      \"B\": \"Pilihan jawaban B\",
      \"C\": \"Pilihan jawaban C\",
      \"D\": \"Pilihan jawaban D\"
    },
    \"correct_answer\": \"A\",
    \"explanation\": \"Penjelasan langkah demi langkah cara menemukan jawabannya...\"
  }
]";

        try {
            $openRouterService = app(OpenRouterService::class);
            $systemInstruction = 'Kamu adalah pakar kurikulum sekolah nasional dan pembuat kuis profesional. Tugasmu membuat soal kuis pilihan ganda yang akurat, menantang, dan edukatif. Kamu WAJIB membalas HANYA dengan kode JSON murni tanpa pembuka/penutup dan tanpa markdown ```json.';
            
            $aiResponse = $openRouterService->generateContent([
                ['role' => 'user', 'parts' => [['text' => $prompt]]]
            ], $systemInstruction);

            // Clean any potential markdown code blocks returned by the model
            $cleanedJson = preg_replace('/^```(?:json)?|```$/m', '', trim($aiResponse));
            $questions = json_decode($cleanedJson, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($questions)) {
                Log::error("Quiz Generator JSON Decode Failure: " . json_last_error_msg() . " | Raw Response: " . $aiResponse);
                return response()->json([
                    'error' => 'Format kuis yang dihasilkan oleh AI tidak valid. Silakan coba menghasilkan ulang kuis baru.'
                ], 500);
            }

            return response()->json([
                'subject' => $subjectName,
                'difficulty' => $request->difficulty,
                'questions' => $questions
            ]);

        } catch (\Exception $e) {
            Log::error("Quiz Generator API Failure: " . $e->getMessage());
            return response()->json([
                'error' => 'Gagal memanggil layanan AI untuk membuat kuis. Silakan coba beberapa saat lagi.'
            ], 500);
        }
    }
}
