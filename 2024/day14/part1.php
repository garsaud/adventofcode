<?php

$input = trim(stream_get_contents(STDIN));
$lines = explode("\n", $input);

define('WIDTH', 101);
define('HEIGHT', 103);

$square_topleft = 0;
$square_topright = 0;
$square_bottomleft = 0;
$square_bottomright = 0;

foreach ($lines as $line) {
    preg_match('/p=(-?\d+),(-?\d+) v=(-?\d+),(-?\d+)/', $line, $matches);
    [$_, $px, $py, $vx, $vy] = $matches;

    $non_negative_mod = function($a, $b) {
        $r = $a % $b;
        if ($r < 0) return $r + $b;
        return $r;
    };
    
    $final_x = $non_negative_mod($px + $vx * 100, WIDTH);
    $final_y = $non_negative_mod($py + $vy * 100, HEIGHT);

    if (($final_x < (WIDTH - 1) / 2) && ($final_y < (HEIGHT - 1) / 2)) {
        $square_topleft++;
    }
    if (($final_x < (WIDTH - 1) / 2) && ($final_y > (HEIGHT - 1) / 2)) {
        $square_bottomleft++;
    }
    if (($final_x > (WIDTH - 1) / 2) && ($final_y < (HEIGHT - 1) / 2)) {
        $square_topright++;
    }
    if (($final_x > (WIDTH - 1) / 2) && ($final_y > (HEIGHT - 1) / 2)) {
        $square_bottomright++;
    }
}

echo $square_topleft*$square_topright*$square_bottomleft*$square_bottomright;
