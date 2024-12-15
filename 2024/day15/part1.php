<?php

$input = trim(stream_get_contents(STDIN));

[$map, $instructions] = explode("\n\n", $input);
$map = array_map('str_split', explode("\n", $map));
$instructions = str_split($instructions);

$player = ['y' => 0, 'x' => 0, 'vy' => 0, 'vx' => 0];
$walls = [];
$blocks = [];
foreach ($map as $y => $line) {
    foreach ($line as $x => $char) {
        if ($char == '#') {
            $walls["$y,$x"]=true;
        }
        if ($char == 'O') {
            $blocks[] = ['y' => $y, 'x' => $x];
        }
        if ($char == '@') {
            $player = ['y' => $y, 'x' => $x];
        }
    }
}
function block_at_coords($y, $x) {
    global $blocks;
    foreach ($blocks as $id => $block) {
        if ($block['y'] == $y && $block['x'] == $x) return $id;
    }
    return null;
}

$directions = [
    '>' => ['y' => 0, 'x' => 1],
    '<' => ['y' => 0, 'x' => -1],
    '^' => ['y' => -1, 'x' => 0],
    'v' => ['y' => 1, 'x' => 0],
];

class theres_a_wall extends Exception{}

function perform_movement(&$item)
{
    global $walls, $blocks;
    // check destination
    $dest_y = $item['y']+$item['vy'];
    $dest_x = $item['x']+$item['vx'];
    // is there a wall?
    if (isset($walls["$dest_y,$dest_x"])) {
        $item['vy']=$item['vx']=null;
        throw new theres_a_wall;
    }
    // is there a block?
    $block_id = block_at_coords($dest_y, $dest_x);
    if (is_null($block_id)) {
        [$item['y'],$item['x']] = [$dest_y,$dest_x];
        return;
    }
    $block = &$blocks[$block_id];
    // transmit vector to block
    [$block['vy'],$block['vx']] = [$item['vy'],$item['vx']];
    perform_movement($block);
    // no throw happened: we can move the item
    [$item['y'],$item['x']] = [$dest_y,$dest_x];
}

//   01234567
// 0 ########
// 1 #..O.O.#
// 2 ##@.O..#
// 3 #...O..#
// 4 #.#.O..#
// 5 #...O..#
// 6 #......#
// 7 ########
//
//<^^>>>vv<v>>v<<

foreach ($instructions as $instruction) {
    // apply vector
    $player['vx'] = $directions[$instruction]['x'];
    $player['vy'] = $directions[$instruction]['y'];
    try {
    perform_movement($player);
    } catch (theres_a_wall) { /* ¯\_(ツ)_/¯ */ }
    
    // visualization!
    ob_start();
    echo "\e[H\e[J"; // clear screen
    echo "Move $instruction:\n";
    foreach ($map as $y => $line) {
        foreach ($line as $x => $char) {
            if (isset($walls["$y,$x"])) {
                echo '#';
            }
            elseif (block_at_coords($y, $x)!==null) {
                echo 'O';
            }
            elseif ($player['y'] == $y && $player['x'] == $x) {
                echo '@';
            }
            else {
                echo '.';
            }
        }
        echo "\n";
    }
    ob_end_flush();
//    usleep(100000);
}

echo array_reduce($blocks, function($carry, $item) {
    return $carry + 100*$item['y'] + $item['x'];
});
