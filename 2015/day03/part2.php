<?php

$x = $y = 0;
$x2 = $y2 = 0;
$houses = ["$x,$y" => true];

$move = function ($c, &$x, &$y) {
    switch ($c) {
        case '^':
            $y--;
            break;
        case '>':
            $x++;
            break;
        case 'v':
            $y++;
            break;
        case '<':
            $x--;
            break;
    }
};

while (($c = fgetc(STDIN)) !== false) {
    $move($c, $x, $y);
    $houses["$x,$y"] = true;

    $c2 = fgetc(STDIN);
    $move($c2, $x2, $y2);
    $houses["$x2,$y2"] = true;
}

echo count($houses);
