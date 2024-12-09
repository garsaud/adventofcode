<?php

$input = trim(stream_get_contents(STDIN));
$chars = str_split($input);
$disk = [];

$file_mode = true;
$current_file_id = 0;
$current_block_address = 0;
foreach ($chars as $blocks_count) {
    if ($file_mode) {
        $blocks = [];
        foreach (range($current_block_address, $current_block_address+$blocks_count-1) as $addr) {
            $current_block_address = $addr;
            $blocks[] = (string)$current_file_id; // there's a bug if i keep it as an int ¯\_(ツ)_/¯
        }
        $current_file_id++;
    } else {
        $blocks = array_fill(0, $blocks_count, null);
    }
    $disk = [...$disk, ...$blocks];
    $file_mode = !$file_mode;
}

$last_fragmented_file_block = function () use (&$disk) {
    $last_file_block_addr = null;
    for ($addr = count($disk)-1; $addr; $addr--) {
        if (is_null($disk[$addr])) continue; // skip empty blocks
        $last_file_block_addr ??= $addr;
        if (is_null($disk[$addr-1])) return $last_file_block_addr; // previous block is empty
    }
    return false;
};

while ($addr_file = $last_fragmented_file_block()) {
    $last_file_block = $disk[$addr_file];
    $addr_first_empty_block = array_search(null, $disk);
    $disk[$addr_first_empty_block] = $last_file_block;
    $disk[$addr_file] = null;
}

$checksum = array_reduce(array_keys($disk), function ($sum, $addr) use ($disk) {
    if (is_null($disk[$addr])) return $sum;
    return $sum + $addr * $disk[$addr];
}, 0);

echo $checksum;
