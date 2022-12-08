<?php

$input = trim(stream_get_contents(STDIN));

$treesAhead = function ($trees) {
    $distance = 0;
    foreach($trees as $i => $height) {
        if (!$i) continue;
        $distance = $i;
        if ($height >= $trees[0]) break;
    }
    return $distance;
};

$treeMap = array_map('str_split', explode("\n", $input));
$scenicScores = [];

foreach ($treeMap as $y => $row) {
    foreach ($row as $x => $treeHeight) {
        $column = array_column($treeMap, $x);
        $scenicScores["$y-$x"] =
            // left
            $treesAhead(array_reverse(array_slice($row, 0, $x+1))) *
            // right
            $treesAhead(array_slice($row, $x)) *
            // top
            $treesAhead(array_reverse(array_slice($column, 0, $y+1))) *
            // bottom
            $treesAhead(array_slice($column, $y));
    }
}

echo max($scenicScores);
