<?php declare(strict_types=1);

if (!function_exists('format_amount')) {
    /**
     * Formater un montant de manière sécurisée
     */
    function format_amount($amount, int $decimals = 0, string $currency = 'XOF'): string
    {
        return number_format((float) $amount, $decimals) . ' ' . $currency;
    }
}

if (!function_exists('safe_number_format')) {
    /**
     * Formater un nombre de manière sécurisée
     */
    function safe_number_format($number, int $decimals = 0): string
    {
        return number_format((float) $number, $decimals);
    }
}
