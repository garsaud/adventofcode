<?php

$start = microtime(true);

// 1D representation of the lights array
$lights = array_fill(0, 1000*1000, 0);

while (($line = fgets(STDIN)) !== false) {
    preg_match('/(?<todo>[a-z ]+) (?<x1>\d+),(?<y1>\d+) through (?<x2>\d+),(?<y2>\d+)/', $line, $matches);
    $x1 = (int) $matches['x1'];
    $x2 = (int) $matches['x2'];
    $y1 = (int) $matches['y1'];
    $y2 = (int) $matches['y2'];

    for ($x = $x1; $x <= $x2; $x++) {
        for ($y = $y1; $y <= $y2; $y++) {
            $index = 1000 * $x + $y;
            switch ($matches['todo']) {
                case 'turn on':
                    $lights[$index] = 1;
                    continue 2;
                case 'turn off':
                    $lights[$index] = 0;
                    continue 2;
                case 'toggle':
                    $lights[$index] ^= 1;
                    continue 2;
            }
        }
    }
}

$result = array_sum($lights);

$end = microtime(true);

echo "Result : {$result}\n";
echo "Timing : ".($end-$start);
