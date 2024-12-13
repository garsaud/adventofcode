<?php

$input = trim(stream_get_contents(STDIN));
$rows = array_map('str_split', explode("\n", $input));
$crops = [];
$plants_prices = [];

foreach ($rows as $y => $row) {
    foreach ($row as $x => $plant_name) {
        $crops[] = [$y, $x, $plant_name];
        $plants_prices[$plant_name] = 0;
    }
}

function get_crops_in_cluster ($crop, $cluster = []): array {
    global $rows;
    
    [$y, $x, $plant_name] = $crop;
    $cluster["$y,$x"] = true;
    
    $directions = [
                  [-1, 0], //                 Top
        [ 0, -1],          [ 0, 1], // Left         Right
                  [ 1, 0],         //        Bottom
    ];

    foreach ($directions as [$y0, $x0]) {
        $y1 = $y+$y0;
        $x1 = $x+$x0;
        if (!isset($rows[$y1][$x1])) continue; // out of boundaries
        $other_name = $rows[$y1][$x1];
        if (isset($cluster["$y1,$x1"])) continue; // skip visited
        if ($other_name !== $plant_name) continue; // skip other types
        $cluster["$y1,$x1"] = true;
        $other = [$y1, $x1, $other_name];
        $c2 = get_crops_in_cluster($other, $cluster);
        $cluster += $c2;
    }
    return $cluster;
}

function get_perimeter($blocks): int
{
    global $rows;
    $directions = [
                  [-1, 0], //                 Top
        [ 0, -1],          [ 0, 1], // Left         Right
                  [ 1, 0],         //        Bottom
    ];
    $walls = 0;
    foreach ($blocks as [$y, $x, $plant_name]) {
        foreach ($directions as [$y0, $x0]) {
            $y1 = $y+$y0;
            $x1 = $x+$x0;
            if (!isset($rows[$y1][$x1])) {
                $walls++; 
                continue; // out of boundaries
            }
            $other_name = $rows[$y1][$x1];
            if ($other_name !== $plant_name) {
                $walls++; 
                continue; // other type
            }
        }
    }
    return $walls;
}

foreach ($crops as $crop_index => $crop) {
    [$y, $x, $plant_name] = $crop;
    if (!isset($rows[$y][$x])) continue;
    $neigbors_of_same_type = get_crops_in_cluster($crop);
    $neigbors_of_same_type = array_map(fn ($k) => [...explode(',', $k),$plant_name], array_keys($neigbors_of_same_type));
    $area = count($neigbors_of_same_type);
    $perimeter = get_perimeter($neigbors_of_same_type);
    $plants_prices[$plant_name] += $area * $perimeter;
    echo "$plant_name: $area*$perimeter\n";
    foreach ($neigbors_of_same_type as [$y, $x]) {
        unset($rows[$y][$x]);
    }
}

echo array_sum($plants_prices);
