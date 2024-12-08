<?php

$input = trim(stream_get_contents(STDIN));
$lines = explode("\n", $input);
$matrix = array_map('str_split', $lines);

$find_antennas_alike = function ($y, $x) use ($matrix) {
    $freq = $matrix[$y][$x];
    $finds = [];
    foreach ($matrix as $y0 => $row) {
        foreach ($row as $x0 => $cell) {
            if ($cell !== $freq) continue; // skip other freqs
            if ($y0 == $y && $x0 == $x) continue; // ignore self
            $vector = [$y0-$y,$x0-$x];
            $finds["$y0,$x0"] = [$y0+$vector[0], $x0+$vector[1]];
        }
    }
    return $finds;
};

$unique_antinodes = [];
foreach ($matrix as $y => $row) {
    foreach ($row as $x => $cell) {
        if ($cell === '.') continue; // skip empty locations
        $others = $find_antennas_alike($y, $x);
        foreach ($others as $other) {
            if (!isset($matrix[$other[0]][$other[1]])) continue; // skip off-map antinodes
            $unique_antinodes["{$other[0]},{$other[1]}"] = null;
        }
    }
}

echo count($unique_antinodes);
