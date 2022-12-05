<?php

$input = stream_get_contents(STDIN);

$rows = explode("\n", $input);

$pairsWithSections = array_map(
    function ($row) {
        $pair = explode(',', $row);
        return [
            explode('-', $pair[0]),
            explode('-', $pair[1]),
        ];
    },
    array_filter($rows)
);

$overlappingCompletely = array_filter(
    $pairsWithSections,
    fn($pair) =>
        ($pair[0][0] >= $pair[1][0] && $pair[0][1] <= $pair[1][1]) ||
        ($pair[0][0] <= $pair[1][0] && $pair[0][1] >= $pair[1][1])
);

echo count($overlappingCompletely);
