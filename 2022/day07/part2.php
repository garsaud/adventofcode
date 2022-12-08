<?php

$input = stream_get_contents(STDIN);

$lines = explode("\n", $input);

function simplify($path) {
    $parts = explode('/', $path);
    $absolute = array_reduce($parts, function($stack, $part) {
        $part == '..' ?
            array_pop($stack) :
            array_push($stack, $part);
        return $stack;
    }, []);
    return implode('/', $absolute);
}

$files = [];
$current = '';

foreach ($lines as $line) {
    if (preg_match('/\$ cd (.*)/', $line, $matches)) {
        $current = simplify("$current/$matches[1]");
    }
    if (preg_match('/(\d+) (.*)/', $line, $matches)) {
        $files["$current/$matches[2]"] = $matches[1];
    }
}

$folders = [];

foreach ($files as $path => $size) {
    do {
        $parent = dirname($path);
        $folders[$parent] = ($folders[$parent] ?? 0) + $size;
        $path = $parent;
    } while (ctype_alpha(basename($path)));
}

asort($folders);
$bigFolders = array_filter(
    $folders,
    // Folders useful to be deleted are those which would, each alone,
    // free up enough space to store 30000000 on a disk of 70000000
    fn($size) => $size > - 70000000 + $folders['/'] + 30000000
);

echo current($bigFolders);
