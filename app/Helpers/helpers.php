<?php

if (!function_exists('getInitials')) {
    /**
     * Génère les initiales d'un nom.
     *
     * @param string $name
     * @param int $max
     * @return string
     */
    function getInitials($name, $max = 2)
    {
        $words = explode(' ', trim($name));
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(mb_substr($word, 0, 1));
            }
            if (strlen($initials) >= $max) {
                break;
            }
        }

        return $initials ?: '?';
    }
}