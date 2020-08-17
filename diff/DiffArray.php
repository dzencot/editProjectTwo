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

            if (is_array($array1[$key])) {
                return ['name' => $key, 'type' => '-', 'children' => array_keys($array1[$key])['0'] . ':' . dfs($key, $array1[$key])];
            }

            // Временно закрыл
            //return ['name' => $key, 'type' => '-', 'children' => $array1[$key]];

        //ключ есть только в after.
        } elseif (!in_array($key, $keys1)) {
            if (is_array($array2[$key])) {
                return ['name' => $key, 'type' => '-', 'children' => array_keys($array2[$key])['0'] . ':' . dfs($key, $array2[$key])];
            }

            return ['name' => $key, 'type' => '+', 'children' => $array2[$key]];

        // Одинаковые ключи
        } elseif (in_array($key, $keys1) and in_array($key, $keys2)) {
            // Значения строки
            if (!is_array($array1[$key]) and !is_array($array2[$key])) {
                if ($array1[$key] === $array2[$key]) {
                    return ['name' => $key, 'type' => ' ', 'children' => $array1[$key]];
                    //РЫБА--------------------------------------
                } elseif ($array1[$key] !== $array2[$key]) {
                    return ['name' => $key, 'type' => 'changed', 'childrenMinus' => '- ' . $key . ': ' . $array1[$key], 'childrenPlus' => '+ ' . $key . ': ' . $array2[$key]];
                }
            // Значения объекты
            } elseif (is_array($array1[$key]) and is_array($array2[$key])) {
                return [$key => diffArray($array1[$key], $array2[$key]), 'type' => 'nested'];
            // Значения объект и строка
            } elseif (is_array($array1[$key]) and !is_array($array2[$key]) or !is_array($array1[$key]) and is_array($array2[$key])) {
                if (is_array($array1[$key]) and !is_array($array2[$key])) {
                    return ['name' => $key, 'type' => 'changed', 'childrenMinus' => '- ' . $key . ': ' . array_keys($array1[$key])['0'] . ':' . dfs($key, $array1[$key]), 'childrenPlus' => '+ ' . $key . ': ' . $array2[$key]];
                } elseif (!is_array($array1[$key]) and is_array($array2[$key])) {
                    return ['name' => $key, 'type' => 'changed', 'childrenMinus' => '- ' . $key . ': ' . $array1[$key], 'childrenPlus' => '+ ' . $key . ': ' . array_keys($array2[$key])['0'] . ':' . dfs($key, $array2[$key])];
                }
            }
        }
    }, $fish);
    return $result;
}

// Вложенный массив в строку.
function dfs($key, $subArray) {
    $keys = array_keys($subArray);

    $resultRender = array_reduce($subArray, function ($acc, $child) use ($key, $keys) {

        // Пробую пробежаться по ключам
      /*  array_map(function ($subKeys) use ($child, $key) {
            //if ($child) {
            print_r($subKeys);
            //print_r(nl2br(PHP_EOL));
            //print_r($subKeys);

            print_r(nl2br(PHP_EOL));
        //}
        }, $keys);*/

        if (is_array($child)) {
            $acc .= array_keys($child)['0'] . ': {' . nl2br(PHP_EOL) . dfs($key, $child)  .  '}' . nl2br(PHP_EOL);
            return $acc;
        }
        $acc .=  $child . nl2br(PHP_EOL);
        return $acc;
    }, '');


    return $resultRender;

}








