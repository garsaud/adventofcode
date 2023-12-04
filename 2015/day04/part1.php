<?php

$input = trim(stream_get_contents(STDIN));

for (
    $salt=0;
    !str_starts_with(md5($input.$salt), '00000');
    $salt++
);

echo $salt;
