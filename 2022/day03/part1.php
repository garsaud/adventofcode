<?php

$input = stream_get_contents(STDIN);

$bags = explode("\n", $input);

$bagsCompartments = array_map(
    fn($bag) => str_split($bag, strlen($bag) / 2),
    array_filter($bags)
);

$bagsRedundantItems = array_map(
    fn($compartments) => [...array_intersect(
        str_split($compartments[0]),
        str_split($compartments[1])
    )][0],
    $bagsCompartments
);

$bagsItemsValues = array_map(
    fn($item) => ctype_lower($item) ?
        array_search($item, range('a', 'z')) + 1 :
        array_search($item, range('A', 'Z')) + 27,
    $bagsRedundantItems
);

echo array_sum($bagsItemsValues);
