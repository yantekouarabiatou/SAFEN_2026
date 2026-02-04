<?php

if (!function_exists('getInitials')) {
    function getInitials(string $name): string
    {
        $words = explode(' ', trim($name));
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= mb_strtoupper(mb_substr($word, 0, 1));
            }
            if (strlen($initials) >= 2) {
                break;
            }
        }
        
        return $initials ?: '?';
    }
}