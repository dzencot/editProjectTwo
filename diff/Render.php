<?php

namespace Dif\Dif\Render;

require_once __DIR__ . '/../vendor/autoload.php';

function render($array)
{
    $result = array_map(function ($child) {
        if ($child['type'] === '-') {
           // var_dump($child["children"]);
            return [$child['type'], $child['name'], $child['children']];
        } elseif ($child['type'] === '+') {
            return [$child['type'], $child['name'], $child['children']];
        } elseif ($child['type'] === 'has not changed') {
            return [$child['type'], $child['name'], $child['children']];
        } elseif ($child['type'] === 'changed') {
            return [$child['type'], $child['name'], $child['children']];
        } elseif ($child['type'] === 'nested') {
            //var_dump($child);
            return [$child['type'], array_map(function ($subChild) {
                print_r($subChild);
                print_r(nl2br(PHP_EOL));
               print_r(nl2br(PHP_EOL));
            }, $child)];
        }

        //print_r($child);
       // print_r(nl2br(PHP_EOL));


    }, $array);

    return $result;
}
