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

        $prompt = "You are an expert academic professor. Generate premium, exam-oriented study notes.\n\n"
            . "Topic: {$topic}\n"
            . "Subject: {$subjectName}\n"
            . "Detail Level: {$detailInstruction}\n\n"
            . "Requirements:\n"
            . "- Use HTML5 semantic tags.\n"
            . "- Use <h2> for major sections and <h3> for sub-sections.\n"
            . "- Use <ul> and <li> for lists with clear pointers.\n"
            . "- Use <table> for comparisons or technical data where applicable.\n"
            . "- Use <div class='info-box'>...</div> for important definitions.\n"
            . "- Use <div class='exam-tip'>...</div> for exam-oriented tips and mnemonics.\n"
            . "- Use <div class='diagram-box'>[Diagram Concept: Describe what a diagram here would show]</div> to indicate where a visual should be.\n"
            . "- Include a 'Quick Revision Table' at the beginning.\n"
            . "- Use <strong> for high-importance keywords.\n"
            . "- Structure the content to be highly engaging with proper spacing.\n"
            . "- Do NOT include html, head, body tags. Only content HTML.";

        try {
            $apiKey = env('GEMINI_API_KEY');
            
            if (!$apiKey) {
                return ['error' => 'API Key missing in .env (GEMINI_API_KEY)'];
            }

            // Supported models to try in order of stability/free-tier likelihood
            $models = array_filter([
                env('GEMINI_MODEL'), 
                'gemini-flash-latest', 
                'gemini-1.5-flash', 
                'gemini-2.0-flash', 
                'gemini-pro-latest'
            ]);

            $lastError = 'Unknown Exception';
            
            foreach ($models as $model) {
                $response = Http::timeout(120)->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                    [
                        'contents' => [
                            ['parts' => [['text' => $prompt]]]
                        ]
                    ]
                );

                if ($response->successful()) {
                    $data = $response->json();
                    $aiContent = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($aiContent) {
                        return ['content' => trim($aiContent), 'model' => $model];
                    }
                }

                $errorBody = $response->json();
                $lastError = $errorBody['error']['message'] ?? 'Status ' . $response->status();
                
                // If it's a quota or 404 issue, try the next model
                if ($response->status() === 429 || $response->status() === 404) {
                    \Log::warning("Gemini model {$model} failed (Status {$response->status()}). Trying next...");
                    continue;
                }

                // For other errors (unauthorized, etc.), don't bother retrying with other models
                break;
            }

            return ['error' => "All AI models failed. Last error ({$model}): {$lastError}"];

        } catch (\Exception $e) {
            \Log::error('AI Generation Exception: ' . $e->getMessage());
            return ['error' => 'System Exception: ' . $e->getMessage()];
        }
    }
}
