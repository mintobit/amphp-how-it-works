<?php

declare(strict_types=1);

namespace AmphpHowItWorks;

if (!function_exists('println')) {
    function println(string $string, ...$placeholders): void
    {
        if ([] !== $placeholders) {
            $string = sprintf($string, ...$placeholders);
        }
        echo $string . PHP_EOL;
    }
}