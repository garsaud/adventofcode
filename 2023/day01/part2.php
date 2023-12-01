<?php

$input = stream_get_contents(STDIN);

$lines = explode("\n", trim($input));

// The input "eightwo" should resolve to 82, not just 8 or 2.
// Therefore, we need to preserve starting and ending letters so they don’t
// break other possible numbers during replacement.
$lines = str_replace(
    ['one','two','three','four','five','six','seven','eight','nine',],
    ['o1ne','t2wo','thr3ee','fo4ur','fi5ve','s6ix','sev7en','eig8ht','ni9ne',],
    $lines
);

$numbers = array_map(
    function($line) {
        $digits = preg_replace('/\D/', '', $line);
        return $digits[0].$digits[-1];
    },
    $lines
);

echo array_sum($numbers);
