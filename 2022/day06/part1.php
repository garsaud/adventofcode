<?php

$input = stream_get_contents(STDIN);

$sequence = str_split($input);

for (
    $position = 0;
    count(array_unique(array_slice($sequence, $position, 4))) < 4;
    $position++
);

echo $position + 4;
