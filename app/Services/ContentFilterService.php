<?php

namespace App\Services;

class ContentFilterService
{
    /**
     * Categories of words to block or flag. 🛡️
     */
    protected $blocks = [
        // 🔴 DEFAMATION / CRIMINAL ALLEGATIONS
        'scam', 'fraud', 'criminal', 'thief', 'chor', 'corrupt', 'bribery', 'bribe', 'illegal', 'mafia', 'blackmail',
        
        // 🔴 ABUSIVE / GAALI
        'idiot', 'stupid', 'dumb', 'bastard', 'asshole', 'shit', 'shitty', 'fuck', 'fucking',
        'chutiya', 'madarchod', 'bhenchod', 'harami', 'kutta', 'kamina', 'mc', 'bc',
        
        // 🔴 PERSONAL ATTACKS
        'useless person', 'worst human', 'disgusting person', 'pathetic teacher',
        
        // 🔴 RELIGION / CASTE / POLITICS
        'hindu', 'muslim', 'caste', 'slurs',
        
        // 🔴 VIOLENCE / THREATS
        'kill', 'beat', 'attack',
    ];

    protected $flags = [
        // ⚠️ WORDS TO FLAG (NOT BLOCK)
        'strict', 'rude', 'biased', 'unfair', 'not helpful',
        
        // 🔴 SEXUAL / INAPPROPRIATE CONTENT
        'sexy', 'hot', 'flirting', 'affair', 'inappropriate touch', 'pervert',
    ];

    /**
     * Check content against the code of conduct.
     * 
     * @param string $text
     * @return array
     */
    public function check($text)
    {
        $text = strtolower($text);

        // Check for Hard Blocks ❌
        foreach ($this->blocks as $word) {
            if (str_contains($text, $word)) {
                return [
                    'status' => 'block',
                    'reason' => "Guideline Violation: Your observation contains terms that violate our Academic Code of Conduct (Category: Safety/Respect)."
                ];
            }
        }

        // Check for Flags ⚠️
        foreach ($this->flags as $word) {
            if (str_contains($text, $word)) {
                return [
                    'status' => 'flag',
                    'reason' => "The observation contains sensitive terminology and will be prioritized for manual council verification."
                ];
            }
        }

        return ['status' => 'pass'];
    }
}
