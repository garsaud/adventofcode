<?php

$start = microtime(true);

gc_disable();
$wires = [];

$operations = [
    'RSHIFT' => fn($a, $b) => $a >> $b,
    'LSHIFT' => fn($a, $b) => $a << $b,
    'OR' => fn($a, $b) => $a | $b,
    'AND' => fn($a, $b) => $a & $b,
    'NOT' => fn($a, $b) => $a ^ 65535, // unsigned 16 bit trick
];

function resolveWire($wireName)
{
    if (empty($wireName)) {
        return null;
    }
    if (is_numeric($wireName)) {
        return $wireName;
    }

    $wires = &$GLOBALS['wires'];
    $wire = $wires[$wireName] ?? null;

    if (empty($wire)) {
        return null;
    }
    if (is_numeric($wire)) {
        return $wire;
    }

    $operations = &$GLOBALS['operations'];

    return $wires[$wireName] = $wire['command']
        ? $operations[$wire['command']](resolveWire(@$wire['args'][0]), resolveWire(@$wire['args'][1]))
        : resolveWire($wire['args'][0]);
}

while (($line = fgets(STDIN)) !== false) {
    preg_match_all('/[A-Z]+/', $line, $commands);
    preg_match_all('/[a-z\d]+/', $line, $args);
    $args = array_map(fn($arg) => is_numeric($arg) ? (int)$arg : $arg, $args[0] ?? []);
    $destination = array_pop($args);
    $wires[$destination] = [
        'command' => $commands[0][0] ?? null,
        'args' => $args,
    ];
}

$result = resolveWire('a');

$end = microtime(true);

echo "Result : {$result}\n";
echo "Timing : ".($end-$start);
