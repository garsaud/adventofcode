<?php

$input = trim(stream_get_contents(STDIN));
$lines = explode("\n", $input);

define('WIDTH', 101);
define('HEIGHT', 103);

$seconds = 0;
$robots = [];
$map = [];

foreach ($lines as $line) {
    preg_match('/p=(-?\d+),(-?\d+) v=(-?\d+),(-?\d+)/', $line, $matches);
    $robots[] = $matches;
}

$non_negative_mod = function($a, $b) {
    $r = $a % $b;
    if ($r < 0) return $r + $b;
    return $r;
};

$has_long_vertical_line = function() use (&$map) {
    for ($py = 0; $py < WIDTH; $py++) {
        $count = 0;
        for ($px = 0; $px < HEIGHT; $px++) {
            if (isset($map["$py,$px"])) {
                $count++;
            } else {
                $count = 0; // reset
            }
            // 5: 1096 too low
            // 6: 1699 too low
            if ($count > 7) {
                return true;
            }
        }
    }

    return false;
};

for (;;) {
    $map = []; // clear map
    foreach ($robots as [, &$px, &$py, $vx, $vy]) {
        $px = $non_negative_mod($px + $vx, WIDTH);
        $py = $non_negative_mod($py + $vy, HEIGHT);
        $map["$py,$px"] = true;
    }
    $seconds++;
    
    if ($has_long_vertical_line()) {
        break;
    }
}

echo $seconds;
