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
        $titleLen = strlen($title);
        if ($titleLen >= 30 && $titleLen <= 60) {
            $points += 20;
            $report['details'][] = ['status' => 'pass', 'message' => 'Optimal Title length.'];
        } else {
            $report['details'][] = ['status' => 'fail', 'message' => 'Title should be 30-60 chars.'];
        }

        // 2. Meta Description Analysis 🛡️
        $descLen = strlen($metaDescription);
        if ($descLen >= 120 && $descLen <= 160) {
            $points += 20;
            $report['details'][] = ['status' => 'pass', 'message' => 'Perfect Meta Description.'];
        } else {
            $report['details'][] = ['status' => 'fail', 'message' => 'Meta Description should be 120-160 chars.'];
        }

        // 3. Keyword Density 🧬
        $wordCount = str_word_count(strip_tags($content));
        if ($wordCount > 0 && !empty($keywords)) {
            $firstKeyword = is_array($keywords) ? ($keywords[0] ?? '') : trim(explode(',', $keywords)[0]);
            if ($firstKeyword) {
                $keywordCount = substr_count(strtolower(strip_tags($content)), strtolower($firstKeyword));
                $density = ($keywordCount / $wordCount) * 100;
                
                if ($density >= 0.5 && $density <= 2.5) {
                    $points += 30;
                    $report['details'][] = ['status' => 'pass', 'message' => 'Optimal Keyword density.'];
                } else {
                    $report['details'][] = ['status' => 'fail', 'message' => 'Keyword density is off (aim for 0.5-2.5%).'];
                }
            }
        }

        // 4. Content Depth 🏛️
        if ($wordCount >= 300) {
            $points += 20;
            $report['details'][] = ['status' => 'pass', 'message' => 'Good content depth.'];
        } else {
            $report['details'][] = ['status' => 'fail', 'message' => 'Write at least 300 words.'];
        }

        // 5. Structure Check
        $hCount = substr_count($content, '<h2') + substr_count($content, '<h3');
        if ($hCount > 0) {
            $points += 10;
            $report['details'][] = ['status' => 'pass', 'message' => 'Structure is solid.'];
        } else {
            $report['details'][] = ['status' => 'fail', 'message' => 'Use H2 or H3 headings.'];
        }

        $report['score'] = min(100, $points);
        return $report;
    }
}
