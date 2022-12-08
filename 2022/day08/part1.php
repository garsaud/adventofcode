<?php

$input = trim(stream_get_contents(STDIN));

$getVisible = function ($trees) {
    return array_filter(
        array_keys($trees),
        fn ($i) =>
            max(array_slice($trees, 0, $i) ?: [-1]) < $trees[$i] ||
            max(array_slice($trees, $i+1) ?: [-1]) < $trees[$i]
    );
};

$treeMap = array_map('str_split', explode("\n", $input));
$visibleTrees = [];

foreach ($treeMap as $y => $trees) {
    foreach ($getVisible($trees) as $x) {
        $visibleTrees["$y-$x"] = true;
    }
}
foreach (array_map(null, ...$treeMap) as $x => $trees) {
    foreach ($getVisible($trees) as $y) {
        $visibleTrees["$y-$x"] = true;
    }
}

echo count($visibleTrees);
