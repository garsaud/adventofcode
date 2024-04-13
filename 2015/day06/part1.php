<?php

$start = microtime(true);

// 1D representation of the lights array
$lights = array_fill(0, 1000*1000, 0);

while (($line = fgets(STDIN)) !== false) {
    [$todo, $x1, $y1, $x2, $y2] = sscanf($line, '%8[a-z ] %d,%d through %d,%d');

    for ($x = $x1; $x <= $x2; $x++) {
        for ($y = $y1; $y <= $y2; $y++) {
            $index = 1000 * $x + $y;
            switch ($todo) {
                case 'turn on ':
                    $lights[$index] = 1;
                    continue 2;
                case 'turn off':
                    $lights[$index] = 0;
                    continue 2;
                case 'toggle ':
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
