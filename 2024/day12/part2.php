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

function get_corners($blocks): int
{
    global $rows;
    $tl = [-1, -1];
    $t =  [-1,  0];
    $tr = [-1,  1];
    $l =  [ 0, -1];
    $r =  [ 0,  1];
    $bl = [ 1, -1];
    $b =  [ 1,  0];
    $br = [ 1,  1];
    
    $blocks_of_cluster = [];
    foreach ($blocks as [$y,$x]) {
        $blocks_of_cluster["$y,$x"] = true;
    }
    
    $corners = 0;
    
    $has_wall = function($block, $direction) use ($rows, $blocks_of_cluster) {
        [$y, $x, $plant_name] = $block;
        [$y0, $x0] = $direction;
        $y1 = $y+$y0;
        $x1 = $x+$x0;
        echo "$plant_name $y,$x -> $y1,$x1 ";
        if (!isset($rows[$y1][$x1])) {
             echo "wall out of boundaries\n";
            return true;
        }
        if (!isset($blocks_of_cluster["$y1,$x1"])) {
            echo "wall from other cluster\n";
            return true;
        }
        $other_name = $rows[$y1][$x1];
        if ($other_name !== $plant_name) {
            echo "wall other type\n";
            return true;
        }
        echo "no wall.\n";
        return false;
    };

    $same_shape = function($block, $direction) use ($rows, $blocks_of_cluster) {
        [$y, $x, $plant_name] = $block;
        [$y0, $x0] = $direction;
        $y1 = $y+$y0;
        $x1 = $x+$x0;
        echo "$plant_name $y,$x -> $y1,$x1 ";
        if (!isset($rows[$y1][$x1])) {
            echo "not same. out of boundaries\n";
            return false;
        }
        if (!isset($blocks_of_cluster["$y1,$x1"])) {
            echo "not same. from other cluster\n";
            return false;
        }
        $other_name = $rows[$y1][$x1];
        if ($other_name === $plant_name) {
            echo "same\n";
            return true;
        }
        echo "not same.\n";
        return false;
    };
    
    
    foreach ($blocks as $block) {
        [$y, $x, $plant_name] = $block;
        
        echo "finding classic corners for $plant_name ($y,$x)...\n";
        if ($has_wall($block,$tl) && $has_wall($block,$l) && $has_wall($block,$t)) {
            echo "corner tl found !\n";
            $corners++;
        }
        if ($has_wall($block,$bl) && $has_wall($block,$b) && $has_wall($block,$l)) {
            echo "corner bl found !\n";
            $corners++;
        }
        if ($has_wall($block,$br) && $has_wall($block,$r) && $has_wall($block,$b)) {
            echo "corner br found !\n";
            $corners++;
        }
        if ($has_wall($block,$tr) && $has_wall($block,$r) && $has_wall($block,$t)) {
            echo "corner tr found !\n";
            $corners++;
        }
        
        echo "finding inset corners for $plant_name ($y,$x)...\n";
        if ($has_wall($block, $br) && $same_shape($block,$r) && $same_shape($block, $b)) {
            echo "inset corner br found !\n";
            $corners++;
        }
        if ($has_wall($block, $bl) && $same_shape($block,$b) && $same_shape($block, $l)) {
            echo "inset corner bl found !\n";
            $corners++;
        }
        if ($has_wall($block, $tl) && $same_shape($block,$t) && $same_shape($block, $l)) {
            echo "inset corner tl found !\n";
            $corners++;
        }
        if ($has_wall($block, $tr) && $same_shape($block,$t) && $same_shape($block, $r)) {
            echo "inset corner tr found !\n";
            $corners++;
        }
        
        echo "finding cross corners for $plant_name ($y,$x)...\n";
        if ($same_shape($block, $br) && $has_wall($block, $r) && $has_wall($block,$b)) {
            echo "cross corner br found !\n";
            $corners++;
        }
        if ($same_shape($block, $tl) && $has_wall($block, $t) && $has_wall($block,$l)) {
            echo "cross corner tl found !\n";
            $corners++;
        }
        if ($same_shape($block, $bl) && $has_wall($block, $l) && $has_wall($block,$b)) {
            echo "cross corner bl found !\n";
            $corners++;
        }
        if ($same_shape($block, $tr) && $has_wall($block, $t) && $has_wall($block,$r)) {
            echo "cross corner tr found !\n";
            $corners++;
        }
    }
    return $corners;
}
// 938985 too low
// 939220 too low
$discounted=[];
foreach ($crops as $crop_index => $crop) {
    [$y, $x, $plant_name] = $crop;
    if (isset($discounted["$y,$x"])) continue;
    $neigbors_of_same_type = get_crops_in_cluster($crop);
    $neigbors_of_same_type = array_map(fn ($k) => [...explode(',', $k),$plant_name], array_keys($neigbors_of_same_type));
    $area = count($neigbors_of_same_type);
    $perimeter = get_corners($neigbors_of_same_type);
    $plants_prices[$plant_name] += $area * $perimeter;
    echo "$plant_name: $area*$perimeter\n";
    foreach ($neigbors_of_same_type as [$y, $x]) {
        $discounted["$y,$x"] = true;
    }
}

echo array_sum($plants_prices);
