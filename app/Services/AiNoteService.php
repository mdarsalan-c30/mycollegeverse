<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiNoteService
{
    protected $apiKey;
    protected $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = 'AIzaSyCezi2i9eAreTivaji9GFS15DM4HNhTRQo';
    }

    /**
     * Generate high-yield exam notes for a subject.
     */
    public function generateNotes($subjectName, $semester, $isNightmare = false)
    {
        $prompt = $this->buildPrompt($subjectName, $semester, $isNightmare);

        try {
            $response = Http::post("{$this->endpoint}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 4096,
                ]
            ]);

            if (!$response->successful()) {
                Log::error("Gemini API Error for {$subjectName}: " . $response->body());
                return null;
            }

            return $response->json('candidates.0.content.parts.0.text');
        } catch (\Exception $e) {
            Log::error("AiNoteService Exception for {$subjectName}: " . $e->getMessage());
            return null;
        }
    }

    protected function buildPrompt($subjectName, $semester, $isNightmare)
    {
        $nightmareContext = $isNightmare ? "This is a 'Nightmare' subject for students. Focus heavily on simplifying complex concepts, using analogies, and providing 'One-Night-Before-Exam' pro-tips." : "";

        return <<<PROMPT
Act as "MCV AI Scholar", a world-class academic tutor and study expert for MyCollegeVerse.
Generate a high-fidelity, comprehensive, and easy-to-understand study guide for the subject: "$subjectName" (Semester $semester).

The content must be better than generic AI outputs. Follow this structure:

1. **Executive Summary**: A punchy, 2-3 sentence overview of why this subject matters and what is most important.
2. **The "Nightmare" Warning (if applicable)**: Briefly mention why students struggle and how to beat it.
3. **Core Modules (High-Yield)**:
    - Break into 4-5 major modules/units.
    - For each unit, list 3-5 sub-topics with clear, concise explanations.
    - Use bullet points, bold text for keywords, and numbered lists.
4. **Visual Breakdowns**: Use ASCII art or clear Markdown tables to explain complex processes or comparisons.
5. **MCV Pro-Tips (Exam Strategy)**:
    - Guaranteed questions/topics.
    - Common pitfalls to avoid.
    - The "One Night Hack" for this subject.
6. **Key Formulas & Definitions**: A quick-reference table.

**Rules**:
- Style: Professional yet student-friendly (Gen-Z professional).
- Format: Clean Markdown.
- SEO: Use the subject name and related keywords naturally.
- Tone: Confidence-boosting and clear.
$nightmareContext

Final Disclaimer (Keep this exact):
"Insights provided by MCV AI Scholar are generated using advanced artificial intelligence to assist your studies. While designed for high-yield exam readiness, please verify critical formulas and data with your primary academic resources as AI may occasionally present inaccuracies."
PROMPT;
    }
}
