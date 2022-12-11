<?php

$input = trim(stream_get_contents(STDIN));

$instructions = explode("\n", $input);

$cycles = 1;
$x = 1;

$tick = function($wait, $add) use (&$x, &$cycles) {
    while ($wait--) {
        $position = $cycles % 40;
        echo in_array($position, [$x, $x+1, $x+2]) ?
            '#' : ' ';
        if (!$position) {
            echo "\n";
        }
        $cycles++;
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
