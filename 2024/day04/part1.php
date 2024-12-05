<?php

$input = trim(stream_get_contents(STDIN));

$lines = explode("\n", $input);
$m = array_map('str_split', $lines);

$find_xmas = function ($y0, $x0, $y1, $x1, $y2, $x2, $y3, $x3) use ($m) {
    if (!isset($m[$x1][$y1]) || !isset($m[$x2][$y2]) || !isset($m[$x3][$y3]))
        return false;
    return "{$m[$y0][$x0]}{$m[$y1][$x1]}{$m[$y2][$x2]}{$m[$y3][$x3]}" == 'XMAS';
};

$finds = 0;

foreach ($m as $y => $line) {
    foreach ($line as $x => $char) {
        if ($char !== 'X') continue;
        
        // search horz+
        if ($find_xmas($y, $x, $y, $x+1, $y, $x+2, $y, $x+3)) $finds++;
        // search horz-
        if ($find_xmas($y, $x, $y, $x-1, $y, $x-2, $y, $x-3)) $finds++;
        // search vert+
        if ($find_xmas($y, $x, $y+1, $x, $y+2, $x, $y+3, $x)) $finds++;
        // search vert-
        if ($find_xmas($y, $x, $y-1, $x, $y-2, $x, $y-3, $x)) $finds++;
        // search diag++
        if ($find_xmas($y, $x, $y+1, $x+1, $y+2, $x+2, $y+3, $x+3)) $finds++;
        // search diag+-
        if ($find_xmas($y, $x, $y+1, $x-1, $y+2, $x-2, $y+3, $x-3)) $finds++;
        // search diag-+
        if ($find_xmas($y, $x, $y-1, $x+1, $y-2, $x+2, $y-3, $x+3)) $finds++;
        // search diag--
        if ($find_xmas($y, $x, $y-1, $x-1, $y-2, $x-2, $y-3, $x-3)) $finds++;
    }
}

echo $finds;
