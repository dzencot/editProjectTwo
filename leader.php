<?php

require_once __DIR__ . '/vendor/autoload.php';

use function Dif\Dif\DiffFiles\getArrays;
use function Dif\Dif\DiffArray\diffArray;
use function Dif\Dif\Render\render;

[$array1, $array2] = getArrays();

$tree = diffArray($array1, $array2);

$rend = render($tree);

var_dump($rend);



