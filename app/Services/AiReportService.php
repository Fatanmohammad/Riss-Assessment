<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiReportService
{
    private string $apiUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.ai.url', 'https://api.anthropic.com/v1/messages');
        $this->apiKey = config('services.ai.key', '');
    }

    public function buatRingkasan(array $dataTemuan): string
    {
        $prompt = $this->susunPrompt($dataTemuan);

        $response = Http::withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->post($this->apiUrl, [
            'model'      => 'claude-3-haiku-20240307',
            'max_tokens' => 512,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        if ($response->failed()) {
            logger()->error('AiReportService gagal: ' . $response->body());
            return 'Ringkasan otomatis tidak tersedia saat ini.';
        }

        return $response->json('content.0.text', 'Ringkasan tidak dapat dibuat.');
    }

    private function susunPrompt(array $dataTemuan): string
    {
        $ringkasanData = collect($dataTemuan)->map(function ($item) {
            return "- Bidang: {$item['bidang']}, Temuan: {$item['deskripsi']}, Status: {$item['status']}, Skor: {$item['skor']}";
        })->implode("\n");

        return <<<PROMPT
Kamu adalah asisten audit internal bank. Berdasarkan data temuan berikut, buat ringkasan naratif singkat (maksimal 3 kalimat) dalam Bahasa Indonesia yang menjelaskan kondisi kepatuhan dan risiko utama yang perlu diperhatikan supervisor.

Data temuan:
{$ringkasanData}

Ringkasan:
PROMPT;
    }
}
