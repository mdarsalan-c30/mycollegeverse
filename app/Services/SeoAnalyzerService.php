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
            $report['details'][] = ['status' => 'pass', 'message' => 'Title length is optimal for Search Engine Result Pages (SERPs).'];
        } else {
            $report['details'][] = ['status' => 'warning', 'message' => 'Title length should be between 30-60 characters for best SEO results.'];
        }

        // 2. Meta Description Analysis 🛡️
        $descLen = strlen($metaDescription);
        if ($descLen >= 120 && $descLen <= 160) {
            $points += 20;
            $report['details'][] = ['status' => 'pass', 'message' => 'Meta description length is perfect for high click-through rates.'];
        } elseif ($descLen > 0) {
            $report['details'][] = ['status' => 'warning', 'message' => 'Meta description is recommended to be 120-160 characters.'];
        } else {
            $report['details'][] = ['status' => 'fail', 'message' => 'Meta description is missing—this is critical for discovery!'];
        }

        // 3. Keyword Density 🧬
        $wordCount = str_word_count(strip_tags($content));
        if ($wordCount > 0 && !empty($keywords)) {
            $firstKeyword = is_array($keywords) ? ($keywords[0] ?? '') : $keywords;
            if ($firstKeyword) {
                $keywordCount = substr_count(strtolower(strip_tags($content)), strtolower($firstKeyword));
                $density = ($keywordCount / $wordCount) * 100;
                
                if ($density >= 0.5 && $density <= 2.5) {
                    $points += 30;
                    $report['details'][] = ['status' => 'pass', 'message' => "Keyword density for '{$firstKeyword}' is optimal (" . round($density, 2) . "%)."];
                } else {
                    $report['details'][] = ['status' => 'warning', 'message' => "Keyword density (" . round($density, 2) . "%) is outside the recommended 0.5-2.5% zone."];
                }
            }
        }

        // 4. Content Depth 🏛️
        if ($wordCount >= 300) {
            $points += 20;
            $report['details'][] = ['status' => 'pass', 'message' => "Solid content depth! ({$wordCount} words detected)."];
        } else {
            $report['details'][] = ['status' => 'warning', 'message' => "Content is light ({$wordCount} words). Aim for at least 300 words for authority."];
        }

        // 5. Structure Check (H1, H2)
        $h1Count = substr_count($content, '<h1');
        $h2Count = substr_count($content, '<h2');

        if ($h1Count === 0) {
            $points += 10; // Assuming the page title acts as H1
            $report['details'][] = ['status' => 'pass', 'message' => 'Article title used as primary heading node.'];
        } else {
            $report['details'][] = ['status' => 'warning', 'message' => 'Multiple H1 tags detected. SEO hierarchy might be compromised.'];
        }

        if ($h2Count > 0) {
            $report['details'][] = ['status' => 'pass', 'message' => 'Good use of subheadings for better readability.'];
        }

        $report['score'] = min(100, $points);
        return $report;
    }
}
