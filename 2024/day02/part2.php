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
    function ($numbers) use (&$is_it_safe) {
        // safe without removing any level
        if ($is_it_safe($numbers)) return true;
        foreach ($numbers as $level => $_) {
            // safe if $level is removed
            $copy = [...$numbers];
            unset($copy[$level]);
            if ($is_it_safe(array_values($copy))) return true;
        }
        return false;
    }
);

echo count($safe_reports);
