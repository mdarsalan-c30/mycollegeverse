<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Note;

trait GeneratesAiContent
{
    /**
     * Core Gemini API wrapper for generating academic content.
     */
    public function performAiGeneration($topic, $subjectName, $detailLevel)
    {
        $detailLabels = [
            'quick' => 'Quick summary with key points (500-800 words)',
            'detailed' => 'Comprehensive detailed notes with examples (1500-2500 words)',
            'exam' => 'Exam-ready revision notes with important questions, formulas, and mnemonics (2000-3000 words)',
        ];

        $detailInstruction = $detailLabels[$detailLevel] ?? $detailLabels['detailed'];

        $prompt = "You are an expert academic professor. Generate high-quality study notes.\n\n"
            . "Topic: {$topic}\n"
            . "Subject: {$subjectName}\n"
            . "Detail Level: {$detailInstruction}\n\n"
            . "Requirements:\n"
            . "- Use clear HTML formatting with h2, h3, p, ul, ol, li, strong, em tags\n"
            . "- Include key definitions, concepts, and explanations\n"
            . "- Add practical examples where relevant\n"
            . "- Include important formulas or mnemonics if applicable\n"
            . "- End with 'Key Takeaways' section\n"
            . "- Do NOT include html, head, body tags. Only content HTML.\n"
            . "- Make it student-friendly and easy to understand\n"
            . "- Use proper academic language";

        try {
            $apiKey = env('GEMINI_API_KEY');
            $model = "gemini-1.5-flash"; // Primary stable model

            $response = Http::timeout(120)->post(
                "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]
            );

            if (!$response->successful()) {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? 'Unknown API Error';
                \Log::error('Gemini API Error: ' . $response->body());
                return ['error' => "API Error: {$errorMessage} (Status: {$response->status()})"];
            }

            $data = $response->json();
            $aiContent = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$aiContent) {
                return ['error' => 'AI returned empty content.'];
            }

            // Clean up markdown code fences if Gemini wraps in ```html
            $aiContent = preg_replace('/^```html\s*/i', '', $aiContent);
            $aiContent = preg_replace('/```\s*$/', '', $aiContent);

            return ['content' => trim($aiContent)];

        } catch (\Exception $e) {
            \Log::error('AI Generation Exception: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
