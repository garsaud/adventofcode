<?php

$input = trim(stream_get_contents(STDIN));

$instructions = explode("\n", $input);

$signals = [1];
$x = 1;

$tick = function($wait, $add) use (&$x, &$signals) {
    while ($wait--) {
        $cycle = count($signals);
        $signals[$cycle] = $x * $cycle;
    }
    $x += $add;
};

foreach ($instructions as $instruction) {
    @[$command, $add] = explode(' ', $instruction);
    if ($command == 'noop') {
        $tick(1, 0);
    }
    if ($command == 'addx') {
        $tick(2, $add);
    }
}

echo
    $signals[20] +
    $signals[60] +
    $signals[100] +
    $signals[140] +
    $signals[180] +
    $signals[220];
