<?php

$input = trim(stream_get_contents(STDIN));
$lines = explode("\n", $input);

$instructions = array_map(fn($line) => sscanf($line, '%d: %[^[]]'), $lines);

$instructions_that_are_true = array_filter($instructions, function ($ins) {
    $parts = explode(' ', $ins[1]);
    $spaces = count($parts) - 1;
    $max = pow(3, $spaces);
    for ($permutation = 0; $permutation < $max; $permutation++) {
        $operations = array_pad(str_split(base_convert($permutation, 10, 3)), -$spaces, 0);
        $result = array_reduce(
            array_keys($operations),
            fn($acc, $i) => match ((int)$operations[$i]) {
                0 => $acc * $parts[$i+1],
                1 => $acc + $parts[$i+1],
                2 => $acc . $parts[$i+1],
            },
            $parts[0]
        );
        if ($result == $ins[0]) return true;
    }
    return false;
});

echo array_sum(array_column($instructions_that_are_true, 0));
