<?php

$input = trim(stream_get_contents(STDIN));
$lines = explode("\n", $input);

$instructions = array_map(fn($line) => sscanf($line, '%d: %[^[]]'), $lines);

$instructions_that_are_true = array_filter($instructions, function ($ins) {
    $parts = array_map('intval', explode(' ', $ins[1]));
    $spaces = count($parts) - 1;
    $max = pow(2, $spaces);
    for ($permutation = 0; $permutation < $max; $permutation++) {
        $operations = str_split(sprintf("%0{$spaces}b", $permutation));
        $result = array_reduce(
            array_keys($operations),
            fn($acc, $i) => $operations[$i] ? $acc + $parts[$i+1] : $acc * $parts[$i+1],
            $parts[0]
        );
        if ($result == $ins[0]) return true;
    }
    return false;
});

echo array_sum(array_column($instructions_that_are_true, 0));
