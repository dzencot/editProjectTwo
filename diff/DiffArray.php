<?php
namespace Dif\Dif\DiffArray;

require_once __DIR__ . '/../vendor/autoload.php';

use function Dif\Dif\DiffFiles\getArrays;
use function Funct\null;

function diffArray ($array1, $array2, $depth)
{
    $keys1 = array_keys($array1);
    $keys2 = array_keys($array2);
    $fish = array_unique(array_merge($keys1, $keys2));

    $result = array_map(function ($key) use ($array1, $array2, $keys1, $keys2, $depth) {

        // Удалённый, ключ есть только в before
        if (!in_array($key, $keys2)) {
            if (is_array($array1[$key])) {

                //return ['name' => $key, 'type' => 'removed', 'value' => array_keys($array1[$key])['0'] . ':' . dfs($array1[$key], $depth)];
                return ['name' => $key, 'type' => 'removed', 'value' => dfs($array1[$key], $depth)];
            }
            return ['name' => $key, 'type' => 'removed', 'value' => $array1[$key]];

        // Добавленный, ключ есть только в after
        } elseif (!in_array($key, $keys1)) {
            if (is_array($array2[$key])) {
                return ['name' => $key, 'type' => 'add', 'value' => dfs($array2[$key], $depth)];
            }
            return ['name' => $key, 'type' => 'add', 'value' => $array2[$key]];

        // Одинаковые ключи
        } else {

            // Значения объекты
            if (is_array($array1[$key]) and is_array($array2[$key])) {
                // В depth закрывающая скобка
                return [$key => diffArray($array1[$key], $array2[$key], $depth),  'type' => 'nested'];

            // Значения объект и строка
            } elseif (is_array($array1[$key]) and !is_array($array2[$key]) or !is_array($array1[$key]) and is_array($array2[$key])) {
                if (is_array($array1[$key]) and !is_array($array2[$key])) {
                    // В depth закрывающая скобка
                    return ['name' => $key, 'type' => 'changed', 'oldValue' =>  $key . ': ' .  dfs($array1[$key], $depth), 'newValue' => $key . ': ' . $array2[$key]];
                } else {
                    // В depth закрывающая скобка
                    return ['name' => $key, 'type' => 'changed', 'oldValue' => $key . ': ' . $array1[$key], 'newValue' => $key . ': ' .  dfs($array2[$key], $depth)];
                }

            // Значения строки
            } else {
                if ($array1[$key] === $array2[$key]) {
                    return ['name' => $key, 'type' => 'unchanged', 'value' => $array1[$key]];
                }
                return ['name' => $key, 'type' => 'changed', 'oldValue' => $key . ': ' . $array1[$key], 'newValue' =>$key . ': ' . $array2[$key]];

            }
        }
    }, $fish);
    return $result;
}

// Вложенный массив в строку.
function dfs($subArray, $depth) {
    if (!is_array($subArray)) {
        return $subArray;
    }

    $result = array_map(function ($key) use ($subArray, $depth) {
        // Нормуль для всего, кроме объектов удаленных или добавленных
        // В depth закрывающая скобка
        return "{" . nl2br(PHP_EOL) . str_repeat('&nbsp;', $depth) . "{$key}: " .  dfs($subArray[$key], 15) . nl2br(PHP_EOL) . str_repeat('&nbsp;', 8) . "}";

        // Делаю для объектов удаленных или добавленных
       //return str_repeat('&nbsp;', $depth) . "{$key}: " . "{" .  dfs($subArray[$key], $depth) .  nl2br(PHP_EOL) . str_repeat('&nbsp;', $depth)  ;
    }, array_keys($subArray));

    return implode($result);
}








