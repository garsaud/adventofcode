<?php

$x = $y = 0;
$houses = ["$x,$y" => true];

while (($c = fgetc(STDIN)) !== false) {
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
    $houses["$x,$y"] = true;
}

echo count($houses);
