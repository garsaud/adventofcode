<?php

$input = stream_get_contents(STDIN);

$rows = explode("\n", $input);

$pairsWithSections = array_map(
    function ($row) {
        $pair = explode(',', $row);
        return [
            call_user_func('range', ...explode('-', $pair[0])),
            call_user_func('range', ...explode('-', $pair[1])),
        ];
    },
    array_filter($rows)
);

$overlapping = array_filter(
    $pairsWithSections,
    fn($pair) =>
        array_intersect(...$pair)
);

echo count($overlapping);
