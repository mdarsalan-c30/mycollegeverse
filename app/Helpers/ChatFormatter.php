<?php

namespace App\Helpers;

class ChatFormatter
{
    /**
     * Format a chat message:
     * - **bold** → <strong>bold</strong>
     * - URLs → clickable <a> tags
     * - \n → <br>
     */
    public static function format(?string $text): string
    {
        if (!$text) return '';

        // Escape HTML to prevent XSS
        $t = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        // **bold** → <strong>bold</strong>
        $t = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $t);

        // URLs → clickable links
        $t = preg_replace(
            '/(https?:\/\/[^\s<]+)/',
            '<a href="$1" target="_blank" rel="noopener" class="underline opacity-80 hover:opacity-100 break-all">$1</a>',
            $t
        );

        // Newlines → <br>
        $t = nl2br($t);

        return $t;
    }
}
