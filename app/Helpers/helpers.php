<?php

function cleanDecimal(mixed $value, ?string $nutrisiKey = null): string
{
    if ($value === null || !is_numeric($value)) {
        return '0';
    }

    $floatValue = (float) $value;

    if ($nutrisiKey === 'kalori') {
        return (string) (int) round($floatValue, 0);
    }

    $makronutrien = ['protein', 'lemak', 'karbohidrat'];
    $vitamin = [
        'vitamin_a', 'beta_karoten',
        'vitamin_b1', 'vitamin_b2', 'vitamin_b3', 'vitamin_b5', 'vitamin_b6', 'vitamin_b12',
        'vitamin_c'
    ];

    $decimals = match (true) {
        in_array($nutrisiKey, $makronutrien) => 2,
        in_array($nutrisiKey, $vitamin) => 3,
        default => 3
    };

    $formatted = number_format($floatValue, $decimals);
    return rtrim(rtrim($formatted, '0'), '.');
}

