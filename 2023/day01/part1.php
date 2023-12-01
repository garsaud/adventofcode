<?php

$input = stream_get_contents(STDIN);

$lines = explode("\n", trim($input));

$numbers = array_map(
    function($line) {
        $digits = preg_replace('/\D/', '', $line);
        return $digits[0].$digits[-1];
    },
    $lines
);

echo array_sum($numbers);
