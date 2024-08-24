<?php

if (!function_exists('obfuscate_email')) {
    function obfuscate_email(string $email): string
    {
        $splitted    = explode('@', $email);
        $splitted[0] = $splitted[0][0] . str_repeat('*', strlen($splitted[0]) - 1);
        $email       = implode('@', $splitted);

        return $email;
    }
}
