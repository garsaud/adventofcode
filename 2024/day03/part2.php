<?php

$input = stream_get_contents(STDIN);

// .* doesn’t match line breaks
// (?:.|\n)* matches everything
$input = preg_replace('/don\'t\(\)(?:.|\n)*do\(\)/mU', '', $input.'do()');

preg_match_all('/mul\((\d+),(\d+)\)/m', $input, $instructions, PREG_SET_ORDER);

$results = array_map(fn ($i) => $i[1] * $i[2], $instructions);

echo array_sum($results);
