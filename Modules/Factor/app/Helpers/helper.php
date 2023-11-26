<?php

if (! function_exists('calcPercentOfPrice')) {
    function calcPercentOfPrice(float $percent, int $price): int
    {
        if ($percent < 0 || $percent > 100) {
            throw new InvalidArgumentException('Percentage must be between 0 and 100.');
        }

        return (int) round(($percent / 100) * $price);
    }
}
