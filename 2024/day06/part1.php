<?php

$input = trim(stream_get_contents(STDIN));
$lines = explode("\n", $input);

$guard = null;

$matrix = array_map(function ($y, $line) use (&$guard) {
    $chars = str_split($line);
    if (is_null($guard)) foreach ($chars as $x => $char) {
        if ($char === '^') {
            $guard = [$y, $x];
            break;
        }
    }
    return $chars;
}, array_keys($lines), $lines);

$visited = ["{$guard[0]},{$guard[1]}" => $guard];
$direction = [-1, 0];

for (;;) {
    $next = [$guard[0]+$direction[0], $guard[1]+$direction[1]];
    if (!isset($matrix[$next[0]][$next[1]])) {
        break;
    }
    if ($matrix[$next[0]][$next[1]] === '#') {
        $direction = match ($direction) {
            [-1, 0] => [0, 1],
            [0, 1] => [1, 0],
            [1, 0] => [0, -1],
            [0, -1] => [-1, 0],
        };
        $next = [$guard[0]+$direction[0], $guard[1]+$direction[1]];
    }
    $guard = $next;
    $visited["{$guard[0]},{$guard[1]}"] = $guard;
}

echo count($visited);
