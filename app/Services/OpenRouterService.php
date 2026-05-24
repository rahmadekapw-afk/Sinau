<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.api_key') ?? '';
        $this->model = config('services.openrouter.model') ?? 'meta-llama/llama-3-8b-instruct:free';
        $this->baseUrl = rtrim(config('services.openrouter.base_url') ?? 'https://openrouter.ai/api/v1', '/');
    }

    /**
     * Send a request to OpenRouter/Ollama API with formatted chat history.
     *
     * @param array $contents The chat history (can be Gemini format or standard OpenAI format)
     * @param string $systemInstruction Optional system instruction
     * @return string
     * @throws \Exception
     */
    public function generateContent(array $contents, string $systemInstruction = '')
    {
        $messages = [];

        // 1. Add system instruction if provided
        if (!empty($systemInstruction)) {
            $messages[] = [
                'role' => 'system',
                'content' => $systemInstruction
            ];
        }

        // 2. Parse and convert input messages to standard OpenAI format
        foreach ($contents as $msg) {
            $role = $msg['role'] ?? 'user';
            
            // Normalize role name: 'model' in Gemini becomes 'assistant' in OpenAI
            if ($role === 'model' || $role === 'assistant') {
                $role = 'assistant';
            }

            // Extract content safely
            $content = '';
            if (isset($msg['parts'][0]['text'])) {
                $content = $msg['parts'][0]['text'];
            } elseif (isset($msg['content'])) {
                $content = $msg['content'];
            } elseif (isset($msg['message'])) {
                $content = $msg['message'];
            }

            if ($content !== '') {
                $messages[] = [
                    'role' => $role,
                    'content' => $content
                ];
            }
        }

        if (empty($messages)) {
            throw new \Exception('Pesan chat kosong atau tidak valid.');
        }

        try {
            Log::info("Mengirim request ke OpenRouter/Ollama dengan model: {$this->model} di {$this->baseUrl}");

            $headers = [
                'Content-Type' => 'application/json',
            ];

            // Only add Authorization header if API Key is set (not needed for local Ollama)
            if (!empty($this->apiKey)) {
                $headers['Authorization'] = 'Bearer ' . $this->apiKey;
            }

            // OpenRouter recommended headers for analytics tracking
            $headers['HTTP-Referer'] = config('app.url', 'http://localhost');
            $headers['X-Title'] = config('app.name', 'Sinau');

            $payload = [
                'model' => $this->model,
                'messages' => $messages,
            ];

            // Send request — timeout 90s to handle slow free-tier queues
            $response = Http::withHeaders($headers)
                ->timeout(90)
                ->retry(1, 2000, function ($exception) {
                    return $exception instanceof \Illuminate\Http\Client\ConnectionException;
                })
                ->post("{$this->baseUrl}/chat/completions", $payload);

            if ($response->successful()) {
                $aiText = $response->json('choices.0.message.content');
                if ($aiText !== null) {
                    Log::info("Request OpenRouter/Ollama berhasil.");
                    return $aiText;
                }
            }

            // Handle errors gracefully
            $statusCode = $response->status();
            $errorMsg = $response->json('error.message') ?? $response->json('error') ?? $response->body();
            
            // If it's a string / array error format, stringify it
            if (is_array($errorMsg)) {
                $errorMsg = json_encode($errorMsg);
            }

            Log::error("Gagal request OpenRouter/Ollama (Status {$statusCode}): {$errorMsg}");
            throw new \Exception("Status {$statusCode}: {$errorMsg}");

        } catch (\Exception $e) {
            Log::error("Terjadi exception pada OpenRouter/Ollama Service: " . $e->getMessage());
            throw $e;
        }
    }
}
