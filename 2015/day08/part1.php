<?php

$start = microtime(true);

$input = trim(stream_get_contents(STDIN));

$result = array_sum(
    array_map(
        fn ($line) => strlen($line) - eval("return strlen({$line});"),
        explode("\n", $input)
    )
);

$end = microtime(true);

echo "Result : {$result}\n";
echo "Timing : ".($end-$start);
