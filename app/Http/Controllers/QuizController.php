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
Harus ditulis menggunakan Bahasa Indonesia formal yang baku, alami, sangat jelas, baik dan benar, sesuai dengan standar kurikulum nasional (sesuai EYD/PUEBI dan KBBI). 
PENTING: Pastikan tidak ada kesalahan ketik (typo/saltik), kalimat tidak rancu, pilihan jawaban harus masuk akal dan relevan dengan pertanyaan, serta penjelasan langkah demi langkah disajikan dengan runtut dan profesional. Hindari menerjemahkan istilah akademik secara asal-asalan; gunakan istilah resmi dalam Bahasa Indonesia yang diajarkan di sekolah.

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
            $systemInstruction = 'Kamu adalah pakar kurikulum sekolah nasional Indonesia dan pembuat kuis profesional yang sangat teliti. Tugasmu membuat soal kuis pilihan ganda yang akurat, menantang, edukatif, dan mutlak BEBAS dari kesalahan ketik (typo/saltik). Gunakan Bahasa Indonesia formal yang baku, indah, presisi, dan mudah dipahami oleh siswa. Kamu WAJIB membalas HANYA dengan kode JSON murni tanpa pembuka/penutup dan tanpa markdown ```json.';
            
            $aiResponse = $openRouterService->generateContent([
                ['role' => 'user', 'parts' => [['text' => $prompt]]]
            ], $systemInstruction);

            // Ekstrak, bersihkan, dan perbaiki JSON menggunakan mesin pemulih robust
            $decoded = $this->cleanAndParseJson($aiResponse);

            if ($decoded === null || !is_array($decoded)) {
                Log::error("Quiz Generator JSON Decode Failure: " . json_last_error_msg() . " | Raw Response: " . $aiResponse);
                return response()->json([
                    'error' => 'Format kuis yang dihasilkan oleh AI tidak valid. Silakan coba menghasilkan ulang kuis baru.'
                ], 500);
            }

            // Normalisasi struktur data questions secara tangguh
            $questions = [];
            $decodedList = [];

            // 1. Jika kuis dibungkus dalam key (seperti 'questions', 'quiz', 'soal', 'data', 'list' dll)
            $possibleKeys = ['questions', 'quiz', 'soal', 'data', 'list'];
            $foundNested = false;
            foreach ($possibleKeys as $key) {
                if (isset($decoded[$key]) && is_array($decoded[$key])) {
                    $decodedList = $decoded[$key];
                    $foundNested = true;
                    break;
                }
            }
            
            if (!$foundNested) {
                $decodedList = $decoded;
            }

            // 2. Pastikan kita mengubah associative/object array menjadi sequential array
            if (is_array($decodedList)) {
                $decodedList = array_values($decodedList);
            } else {
                $decodedList = [];
            }

            foreach ($decodedList as $item) {
                if (!is_array($item)) {
                    continue;
                }

                // Normalisasi Key: "question" / "soal" / "pertanyaan"
                $questionText = $item['question'] ?? $item['soal'] ?? $item['pertanyaan'] ?? '';
                
                // Normalisasi Key: "options" / "choices" / "pilihan" / "opsi"
                $options = $item['options'] ?? $item['choices'] ?? $item['pilihan'] ?? $item['opsi'] ?? [];
                
                // Normalisasi Key: "correct_answer" / "correct" / "jawaban_benar" / "jawaban" / "correct_option"
                $correctAnswer = $item['correct_answer'] ?? $item['correct'] ?? $item['jawaban_benar'] ?? $item['jawaban'] ?? $item['correct_option'] ?? '';
                
                // Normalisasi Key: "explanation" / "pembahasan" / "penjelasan" / "reason"
                $explanation = $item['explanation'] ?? $item['pembahasan'] ?? $item['penjelasan'] ?? $item['reason'] ?? '';

                // Bersihkan correctAnswer jika berisi text panjang (misal "A. Pilihan" -> ambil "A")
                if (is_string($correctAnswer)) {
                    $correctAnswer = trim($correctAnswer);
                    if (strlen($correctAnswer) > 1) {
                        $firstChar = strtoupper(substr($correctAnswer, 0, 1));
                        if (in_array($firstChar, ['A', 'B', 'C', 'D'])) {
                            $correctAnswer = $firstChar;
                        }
                    } else {
                        $correctAnswer = strtoupper($correctAnswer);
                    }
                }

                // Normalisasi options array jika isinya associative atau sequential
                $normalizedOptions = [
                    'A' => '',
                    'B' => '',
                    'C' => '',
                    'D' => ''
                ];
                
                if (is_array($options)) {
                    // Jika option berbentuk associative ['A' => ..., 'B' => ...]
                    foreach (['A', 'B', 'C', 'D'] as $key) {
                        if (isset($options[$key])) {
                            $normalizedOptions[$key] = $options[$key];
                        } elseif (isset($options[strtolower($key)])) {
                            $normalizedOptions[$key] = $options[strtolower($key)];
                        }
                    }
                    
                    // Jika option berbentuk sequential ["pilihan A", "pilihan B", ...]
                    if (empty(array_filter($normalizedOptions))) {
                        $optionValues = array_values($options);
                        $keys = ['A', 'B', 'C', 'D'];
                        foreach ($keys as $idx => $key) {
                            if (isset($optionValues[$idx])) {
                                $normalizedOptions[$key] = $optionValues[$idx];
                            }
                        }
                    }
                }

                // Hanya masukkan jika ada teks pertanyaan dan minimal 2 opsi
                if (!empty($questionText) && count(array_filter($normalizedOptions)) >= 2) {
                    $questions[] = [
                        'question' => $questionText,
                        'options' => $normalizedOptions,
                        'correct_answer' => $correctAnswer,
                        'explanation' => $explanation
                    ];
                }
            }

            if (empty($questions)) {
                Log::error("Quiz Generator Normalization Failure: Empty questions list. Raw Response: " . $aiResponse);
                return response()->json([
                    'error' => 'Format kuis yang dihasilkan oleh AI tidak valid atau kosong. Silakan coba menghasilkan ulang kuis kustom.'
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

    /**
     * Clean up and repair common JSON syntax errors generated by LLM models.
     * 
     * @param string $rawResponse
     * @return array|null
     */
    private function cleanAndParseJson(string $rawResponse)
    {
        // 1. Ganti smart quotes (curly quotes) yang sering dihasilkan oleh model AI
        $cleaned = str_replace(['“', '”', '‟', '″', '″'], '"', $rawResponse);
        $cleaned = str_replace(['‘', '’', 'ʼ', '`'], "'", $cleaned);

        // 2. Cari kurung siku pertama '[' dan terakhir ']' untuk memotong teks luar/sampah/tags
        $firstBracket = strpos($cleaned, '[');
        $lastBracket = strrpos($cleaned, ']');

        if ($firstBracket !== false && $lastBracket !== false && $lastBracket > $firstBracket) {
            $cleaned = substr($cleaned, $firstBracket, $lastBracket - $firstBracket + 1);
        } else {
            // Jika tidak ada array, coba cari kurung kurawal '{' dan terakhir '}'
            $firstBrace = strpos($cleaned, '{');
            $lastBrace = strrpos($cleaned, '}');
            if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
                $cleaned = substr($cleaned, $firstBrace, $lastBrace - $firstBrace + 1);
                // Ubah menjadi array format jika hanya 1 object
                $cleaned = '[' . $cleaned . ']';
            }
        }

        // 3. Bersihkan sisa tag XML/HTML atau </assistant> yang mungkin menyusup
        $cleaned = preg_replace('/<\/?[a-zA-Z0-9_\-]+>/', '', $cleaned);

        // 4. Perbaiki kutipan yang rusak/campuran seperti "B' atau 'B"
        $cleaned = preg_replace("/\"([A-D])\'/", "\"$1\"", $cleaned);
        $cleaned = preg_replace("/\'([A-D])\"/", "\"$1\"", $cleaned);
        $cleaned = preg_replace("/\'([A-D])\'/", "\"$1\"", $cleaned);

        // 5. Hapus double-quotes yang bertumpuk akibat salah escape di akhir kata
        $cleaned = preg_replace('/""\s*,/', '",', $cleaned);

        // 6. Hapus trailing comma sebelum penutup kurung siku/kurawal
        $cleaned = preg_replace('/,\s*([\]}])/m', '$1', $cleaned);

        // Coba decode pertama
        $decoded = json_decode($cleaned, true);

        // 7. Jika gagal, coba perbaikan agresif untuk single-quote key/value
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning("Mencoba perbaikan agresif JSON karena error: " . json_last_error_msg());
            
            // Perbaiki kutip satu (') yang digunakan AI sebagai pembatas string
            $fixed = preg_replace("/'([a-zA-Z0-9_]+)'\s*:/", '"$1":', $cleaned);
            $fixed = preg_replace("/:\s*'([^']*)'/", ': "$1"', $fixed);
            
            $decoded = json_decode($fixed, true);
        }

        return $decoded;
    }
}
