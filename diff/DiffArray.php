<?php
namespace Dif\Dif\DiffArray;

require_once __DIR__ . '/../vendor/autoload.php';

use function Dif\Dif\DiffFiles\getArrays;
use function Funct\null;

function diffArray ($array1, $array2, $option = null)
{
    $keys1 = array_keys($array1);
    $keys2 = array_keys($array2);
    $fish = array_unique(array_merge($keys1, $keys2));

    $result = array_map(function ($key) use ($array1, $array2, $keys1, $keys2, $option) {
        //ключ есть только в before.
        if (!in_array($key, $keys2)) {
            return ['name' => $key, 'type' => '-'];
        //ключ есть только в after.
        } elseif (!in_array($key, $keys1)) {
            return ['name' => $key, 'type' => '+'];
        //одинаковые ключи.
        } elseif (in_array($key, $keys1) and in_array($key, $keys2)) {
            //значения НЕ объекты.
            if (!is_array($array1[$key]) and !is_array($array2[$key])) {
                if ($array1[$key] === $array2[$key]) {
                    return ['name' => $key, 'type' => 'has not changed'];
                } elseif ($array1[$key] !== $array2[$key]) {
                    return ['name' => $key, 'type' => '-' . $array1[$key] . ' +' . $array2[$key]];
                }
             //значения объекты.
            } elseif (is_array($array1[$key]) and is_array($array2[$key])) {
                return [$key => diffArray($array1[$key], $array2[$key]), 'type' => 'object'];
            }
        }
    }, $fish);
    return $result;
}





