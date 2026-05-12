<?php

namespace App\Services;

class SeoAnalyzerService
{
    /**
     * Analyze content and return a structured SEO report with a cumulative score.
     */
    public function analyze($title, $content, $metaDescription, $keywords = [])
    {
        $report = [
            'score' => 0,
            'details' => [],
        ];

        $points = 0;
        $maxPoints = 100;

        // 1. Title Analysis 🛰️
        $tl = strlen($title);
        if ($tl >= 30 && $tl <= 60) {
            $points += 20;
        } elseif ($tl > 0) {
            $points += min(15, ($tl / 30) * 20);
        }

        // 2. Meta Description Analysis 🛡️
        $dl = strlen($metaDescription);
        if ($dl >= 120 && $dl <= 160) {
            $points += 20;
        } elseif ($dl > 0) {
            $points += min(15, ($dl / 120) * 20);
        }

        // 3. Keyword Density 🧬
        $wordCount = str_word_count(strip_tags($content));
        if ($wordCount > 0 && !empty($keywords)) {
            $firstKeyword = is_array($keywords) ? ($keywords[0] ?? '') : trim(explode(',', $keywords)[0]);
            if ($firstKeyword) {
                $keywordCount = substr_count(strtolower(strip_tags($content)), strtolower($firstKeyword));
                $density = ($keywordCount / $wordCount) * 100;
                
                if ($density >= 0.5 && $density <= 3.0) {
                    $points += 30;
                } elseif ($density > 0) {
                    $points += 15;
                }
            }
        }

        // 4. Content Depth 🏛️
        if ($wordCount >= 300) {
            $points += 20;
        } elseif ($wordCount > 0) {
            $points += ($wordCount / 300) * 20;
        }

        // 5. Structure Check
        $hCount = substr_count($content, '<h2') + substr_count($content, '<h3');
        if ($hCount > 0) {
            $points += 10;
        }

        $report['score'] = round(min(100, $points));
        return $report;
    }
}
