<?php

$input = trim(stream_get_contents(STDIN));

$lines = explode("\n", $input);
$m = array_map('str_split', $lines);

$find_mmss = function ($y0, $x0, $y1, $x1, $y2, $x2, $y3, $x3) use ($m) {
    if (!isset($m[$x1][$y1]) || !isset($m[$x2][$y2]) || !isset($m[$x3][$y3]))
        return false;
    return "{$m[$y0][$x0]}{$m[$y1][$x1]}{$m[$y2][$x2]}{$m[$y3][$x3]}" == 'MMSS';
};

$finds = 0;

foreach ($m as $y => $line) {
    foreach ($line as $x => $char) {
        if ($char !== 'A') continue;
        
        // 0 deg
        if ($find_mmss($y-1, $x-1,  $y-1, $x+1,  $y+1, $x+1,  $y+1, $x-1)) $finds++;
        // 90 deg
        if ($find_mmss($y-1, $x+1,  $y+1, $x+1,  $y+1, $x-1,  $y-1, $x-1)) $finds++;
        // 180 deg
        if ($find_mmss($y+1, $x+1,  $y+1, $x-1,  $y-1, $x-1,  $y-1, $x+1)) $finds++;
        // 270 deg
        if ($find_mmss($y+1, $x-1,  $y-1, $x-1,  $y-1, $x+1,  $y+1, $x+1)) $finds++;
    }
}

echo $finds;
