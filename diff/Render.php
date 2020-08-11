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
            $result = array_map(function ($subChild) {
                if (is_array($subChild)) {
                    return [render($subChild)];
                }
            }, $child);
            return $result;
        }

        //print_r($child);
       // print_r(nl2br(PHP_EOL));


    }, $array);

    return $result;
}
