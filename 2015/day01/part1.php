<?php

$input = stream_get_contents(STDIN);

$operation = str_replace(['(',')'], ['+1', '-1'], $input);

echo eval("return {$operation};");
