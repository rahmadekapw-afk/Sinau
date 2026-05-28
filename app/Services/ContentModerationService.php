<?php

namespace App\Services;

class ContentModerationService
{
    /**
     * Extensive list of vulgar, sexual, offensive, and inappropriate keywords.
     * Categorized for clarity and maintainability.
     */
    protected array $forbiddenKeywords = [
        // --- 1. Seksual & Pornografi (Indonesian) ---
        'ngentot', 'ngewe', 'entot', 'memek', 'kontol', 'pantek', 'pepek', 'jembut', 'perek', 'lonte', 'pelacur', 'silit', 
        'tetek', 'toket', 'sange', 'coli', 'masturbasi', 'porno', 'bokep', 'hentai', 'seksual', 'bersetubuh', 'mesum', 
        'cabul', 'pemerkosaan', 'perkosa', 'itil', 'colmek', 'gigolo', 'open bo', 'openbo', 'vibrator', 'orgasme', 
        'pelumas seks', 'kondom', 'alat kelamin', 'payudara', 'bokong', 'bispak', 'londri lendir', 'pijat plus', 
        'vagina', 'penis', 'seks', 'ejakulasi', 'fetish', 'pornogradi', 'pornografi', 'semprot', 'lendir', 'sodomi',
        'kamasutra', 'telanjang', 'bugil', 'tanpa busana', 'susu gede', 'payudara', 'tete', 'bogel', 'bokep indo',

        // --- 2. Kata Kasar, Makian & Kebencian (Indonesian / Slang / Daerah) ---
        'anjing', 'babi', 'bangsat', 'goblok', 'tolol', 'idiot', 'bego', 'bajingan', 'brengsek', 'asu', 'jancok', 
        'jancuk', 'peli', 'banci', 'bencong', 'kunyuk', 'kampret', 'keparat', 'setan', 'iblis', 'lonte', 'sinting',
        'sarap', 'bejat', 'pantat', 'pecun', 'ngentod', 'anying', 'anyink', 'anjinglu', 'ngasuu', 'perek', 'jablay',
        'palkon', 'jembuts', 'ngehe', 'tai', 'taik', 'tae', 'kamfret', 'kimak', 'pukimak', 'bodoh', 'ndasmu', 
        'matamu', 'raimu', 'gatel', 'lonte', 'monyet', 'setan lu', 'dasar tolol', 'dasar goblok', 'kontolodon',

        // --- 3. Sexual / Vulgar / Offensive (English) ---
        'fuck', 'shit', 'asshole', 'bitch', 'bastard', 'cunt', 'dick', 'pussy', 'porn', 'sex', 'hentai', 'erotic', 
        'masturbate', 'blowjob', 'cock', 'vagina', 'clitoris', 'boobs', 'breast', 'penis', 'orgasm', 'prostitute', 
        'whore', 'slut', 'milf', 'naked', 'nudity', 'nude', 'dildo', 'orgasmic', 'intercourse', 'pornographic',
        'anal', 'condom', 'suck my', 'deepthroat', 'fucker', 'motherfucker', 'wanker', 'crap', 'bullshit'
    ];

    /**
     * Check if a text contains any inappropriate content.
     *
     * @param string $text
     * @return bool
     */
    public function hasInappropriateContent(string $text): bool
    {
        $textLower = strtolower($text);

        // Normalize text to detect attempts to bypass filters (e.g., "n g e n t o t", "c-o-l-i", or "b.o.k.e.p")
        $normalizedText = preg_replace('/[^a-z0-9]/', '', $textLower);

        foreach ($this->forbiddenKeywords as $keyword) {
            $keywordLower = strtolower($keyword);
            
            // 1. Check direct word boundary match
            // E.g., match "anjing" but not "panji"
            $escapedKeyword = preg_quote($keywordLower, '/');
            if (preg_match('/\b' . $escapedKeyword . '\b/i', $textLower)) {
                return true;
            }

            // 2. Direct substring search for multi-word or compound keywords
            if (str_contains($textLower, $keywordLower)) {
                return true;
            }

            // 3. Continuous match inside normalized text for specific dangerous words to catch space bypasses
            // To prevent false positives, we only do normalized check for highly sensitive words (length > 3)
            $normalizedKeyword = str_replace(' ', '', $keywordLower);
            if (strlen($normalizedKeyword) > 3 && str_contains($normalizedText, $normalizedKeyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Filter and mask bad words in a text (alternative/fallback option).
     *
     * @param string $text
     * @return string
     */
    public function sanitizeText(string $text): string
    {
        $words = explode(' ', $text);
        foreach ($words as &$word) {
            // Strip punctuation for matching
            $cleanWord = preg_replace('/[^a-zA-Z]/', '', $word);
            if ($this->hasInappropriateContent($cleanWord)) {
                $word = str_repeat('*', strlen($word));
            }
        }
        return implode(' ', $words);
    }
}
