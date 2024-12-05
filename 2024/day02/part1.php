<?php

$input = trim(stream_get_contents(STDIN));

$lines = explode("\n", $input);
$numbers_in_line = array_map(
    function ($line) {
        return explode(' ', $line);
    },
    $lines
);

$is_it_safe = function ($numbers) {
    $all_increasing_or_decreasing = (function($copy) use (&$numbers) {
        sort($copy); if ($copy == $numbers) return true;
        rsort($copy); if ($copy == $numbers) return true;
        return false;
    })($numbers);
    if (!$all_increasing_or_decreasing) return false;
    
    foreach ($numbers as $i => $number) {
        if (!$i) continue;
        if (!in_array(abs($number - $numbers[$i-1]), [1,2,3])) return false;
    }
    
    return true;
};

$safe_reports = array_filter(
    $numbers_in_line,
    $is_it_safe
);

echo count($safe_reports);
