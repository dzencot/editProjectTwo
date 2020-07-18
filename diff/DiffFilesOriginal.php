<?php
namespace Dif\Dif\DiffFiles;

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

function getArrays()
{
    //$before = Yaml::parse(file_get_contents('./before.json'), Yaml::PARSE_OBJECT_FOR_MAP);
    $before= json_decode(file_get_contents(__DIR__ . '/../files/before.json'), true);
    $after = json_decode(file_get_contents(__DIR__ . '/../files/after.json'), true);

    $collectionBefore = collect($before);
    $collectionAfter = collect($after);

    //оставляем тока не массивы и применяем map.
    $array1 = $collectionBefore->filter(function ($value) {
        return !is_array($value);
    })->map(function ($item, $key) {
        return $item ;
    })
        ->all();
    // var_dump($array1);
    // print_r(nl2br(PHP_EOL));

    $array2 = $collectionAfter->filter(function ($value) {
        return !is_array($value);
    })->map(function ($item, $key) {
        return $item ;
    })
        ->all();
    //var_dump($array2);
    //return [$array1, $array2];
    //---------------------------------------------------------------

    //оставляем тока массивы и применяем map.
    $array1 = $collectionBefore->filter(function ($value) {
        return is_array($value);
    })->map(function ($item, $key) {
        return $item ;
    })
        ->all();

    $array2 = $collectionBefore->filter(function ($value) {
        return is_array($value);
    })->map(function ($item, $key) {
        return $item ;
    })
        ->all();

    return [$array1, $array2];


    //var_dump($before);
    //var_dump($array3);

    //print_r(nl2br(PHP_EOL));
}






