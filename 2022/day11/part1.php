<?php

$input = trim(stream_get_contents(STDIN));

$monkeys = array_map(
    function ($section) {
        preg_match('/Monkey (?<index>\d+).*Starting items: (?<items>[\d, ]+).*old (?<sign>.) (?<factor>\w+).*by (?<by>\d+).*true.*(?<true>\d+).*false.*(?<false>\d+)/s', $section, $matches);
        $matches['items'] = explode(', ', $matches['items']);
        return $matches;
    },
    explode("\n\n", $input)
);

print_r($monkeys);exit;
