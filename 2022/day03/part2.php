<?php

$input = stream_get_contents(STDIN);

$bags = explode("\n", $input);

$groups = array_chunk(array_filter($bags), 3);

$groupsCommonItems = array_map(
    fn($bags) => [...array_intersect(
        str_split($bags[0]),
        str_split($bags[1]),
        str_split($bags[2])
    )][0],
    $groups
);

$groupsBadgeValues = array_map(
    fn($item) => ctype_lower($item) ?
        array_search($item, range('a', 'z')) + 1 :
        array_search($item, range('A', 'Z')) + 27,
    $groupsCommonItems
);

echo array_sum($groupsBadgeValues);
