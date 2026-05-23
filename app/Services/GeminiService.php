<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected array $apiKeys;
    protected array $models;

    public function __construct()
    {
        $this->apiKeys = config('services.gemini.api_keys', []);
        
        // If config is empty or missing, fallback to single api_key from services.php
        if (empty($this->apiKeys)) {
            $singleKey = config('services.gemini.api_key');
            if ($singleKey) {
                $this->apiKeys = [$singleKey];
            }
        }

        // List of models to try in order of preference
        $this->models = config('services.gemini.models', [
            'gemini-2.5-flash',
            'gemini-2.0-flash',
            'gemini-1.5-flash',
        ]);
    }

    /**
     * Send a request to Gemini API with automatic rotation of keys and model fallbacks.
     *
     * @param array $contents The chat history contents
     * @param string $systemInstruction Optional system instruction
     * @return string
     * @throws \Exception
     */
    public function generateContent(array $contents, string $systemInstruction = '')
    {
        if (empty($this->apiKeys)) {
            throw new \Exception('Gemini API key tidak ditemukan. Harap konfigurasi GEMINI_API_KEY atau GEMINI_API_KEYS di file .env Anda.');
        }

        $lastError = null;

        // Loop through each model in order of priority
        foreach ($this->models as $model) {
            // Loop through each API key in the pool
            foreach ($this->apiKeys as $index => $apiKey) {
                try {
                    Log::info("Mencoba request menggunakan model: {$model} dan API Key ke-" . ($index + 1));

                    // Construct request payload
                    $payload = [
                        'contents' => $contents,
                    ];

                    if (!empty($systemInstruction)) {
                        $payload['system_instruction'] = [
                            'parts' => [['text' => $systemInstruction]]
                        ];
                    }

                    // Send API request with retry on temporary rate limit (429) or network issue
                    // We retry up to 2 times, waiting 1000ms, then 2000ms if it hits a 429
                    $response = Http::withHeaders(['Content-Type' => 'application/json'])
                        ->retry(2, 1000, function ($exception) {
                            // Only retry if it is a connection exception or a transient rate limit (429)
                            return $exception instanceof \Illuminate\Http\Client\ConnectionException || 
                                   (method_exists($exception, 'getCode') && $exception->getCode() === 429);
                        })
                        ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", $payload);

                    if ($response->successful()) {
                        $aiText = $response->json('candidates.0.content.parts.0.text');
                        if ($aiText !== null) {
                            Log::info("Request berhasil menggunakan model: {$model} dan API Key ke-" . ($index + 1));
                            return $aiText;
                        }
                    }

                    // If request fails (e.g. rate limit 429 or quota limit 403)
                    $statusCode = $response->status();
                    $errorMsg = $response->json('error.message') ?? $response->body();
                    Log::warning("Gagal dengan Status {$statusCode} menggunakan model {$model} (API Key ke-" . ($index + 1) . "): {$errorMsg}");
                    
                    $lastError = "Status {$statusCode}: {$errorMsg}";

                } catch (\Exception $e) {
                    Log::error("Terjadi error pada request Gemini (model: {$model}, API Key ke-" . ($index + 1) . "): " . $e->getMessage());
                    $lastError = $e->getMessage();
                }
            }
        }

        // If we exhausted all models and all API keys
        throw new \Exception("Semua API Key dan Model cadangan telah mencapai batas limit (Rate Limit/Quota Exceeded). Error terakhir: {$lastError}");
    }
}
