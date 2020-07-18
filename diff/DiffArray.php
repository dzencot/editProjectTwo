<?php
namespace Dif\Dif\DiffArray;

require_once __DIR__ . '/../vendor/autoload.php';

use function Dif\Dif\DiffFiles\getArrays;
use function Funct\null;

function diffArray ($array1, $array2, $options = null)
{
    $collection1 = collect($array1);
    $collection2 = collect($array2);

    $result = [];

//одинаковые ключи и структура объект.
    $diff0 = $collection1->intersectByKeys($array2);
    $multiplied0 = $diff0->map(function ($item, $key) use ($diff0, $collection2) {
        if (is_array($item)) {
           return ['name' => $key, 'type' => 'children', 'data' => diffArray($diff0[$key], $collection2[$key])];
        }
    })
        //->values()
         ->diff([null])
        ->all();
   // var_dump($multiplied0);



//одинаковые ключи и значения.
    $diff1 = $collection1->intersectByKeys($array2)->intersect($array2);
    $multiplied1 = $diff1->map(function ($item, $key) {
        if (!is_array($item)) {
            return ['name' => $key, 'status' => '!', 'type' => 'node', 'data' => $item];
        }

    })
       // ->values()
        ->diff([null])
        ->all();
   // var_dump($multiplied1);


//after при одинаковых ключах, но разных значениях
    $diff2 = $collection2->intersectByKeys($array1)->diffAssoc($array1);
    $multiplied2 = $diff2->map(function ($item, $key) {
        if (!is_array($item)) {
            return ['name' => $key, 'status' => '+', 'type' => 'node', 'data' => $item];
        }
    })
        //->values()
        ->diff([null])
        ->all();
    //var_dump($multiplied2);

//before при одинаковых ключах, но разных значениях
    $diff3 = $collection1->intersectByKeys($array2)->diffAssoc($array2);
    $multiplied3 = $diff3->map(function ($item, $key) {
        if (!is_array($item)) {
            return ['name' => $key, 'status' => '-', 'type' => 'node', 'data' => $item];
        }
    })
        //->values()
        ->diff([null])
        ->all();

//есть в before, но нет в after (node).
    $diff4 = $collection1->diffKeys($array2);
    $multiplied4 = $diff4->map(function ($item, $key) {
        if (!is_array($item)) {
            return ['name' => $key, 'status' => '-', 'type' => 'node', 'data' => $item];
        }
    })
        //->values()
        ->diff([null])
        ->all();

//есть в before, но нет в after (children).
    $diff6 = $collection1->diffKeys($array2);
    $multiplied6 = $diff6->map(function ($item, $key) {
        if (is_array($item)) {
            return ['name' => $key, 'status' => '-', 'type' => 'children', 'data' => $item];
        }
    })
        //->values()
        ->diff([null])
        ->all();

//нет в before, но есть в after.
    $diff5 = $collection2->diffKeys($array1);
    $multiplied5 = $diff5->map(function ($item, $key) {
        if (!is_array($item)) {
            return ['name' => $key, 'status' => '+', 'type' => 'node', 'data' => $item];
        }
    })
        //->values()
        ->diff([null])
        ->all();

    //ПИПЕЦ, НО РАБОТАЕТ.
     if (!empty($multiplied0)) {
         $resultMultiplied[] = $multiplied0;
     }
    if (!empty($multiplied1)) {
        $resultMultiplied[] = $multiplied1;
    }
    if (!empty($multiplied2)) {
        $resultMultiplied[] = $multiplied2;
    }
    if (!empty($multiplied3)) {
        $resultMultiplied[] = $multiplied3;
    }
    if (!empty($multiplied4)) {
        $resultMultiplied[] = $multiplied4;
    }
    if (!empty($multiplied5)) {
        $resultMultiplied[] = $multiplied5;
    }
    if (!empty($multiplied6)) {
        $resultMultiplied[] = $multiplied6;
    }
   /* $resultMultiplied[] = $multiplied0;
    $resultMultiplied[] = $multiplied1;
    $resultMultiplied[] = $multiplied2;
    $resultMultiplied[] = $multiplied3;
    $resultMultiplied[] = $multiplied4;
    $resultMultiplied[] = $multiplied5;
    $resultMultiplied[] = $multiplied6;*/


    return $resultMultiplied;


    //убираем null значения из конечного массива.
    //return ArrayCleaner($resultMultiplied);

//собираем все массивы и преобразуем в строку.
   // $result1 = collect($multiplied0);
   // $result2 = $result1->merge($multiplied1)->merge($multiplied2)->merge($multiplied3)->merge($multiplied4)->merge($multiplied5)->implode("\n");

//var_dump($result2);
    //return $result;

}
//убирает значения null, пусто
   /* function ArrayCleaner($input) {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = ArrayCleaner($value);
            }
        }

        return array_filter($input, function($item){
            return $item !== null && $item !== '' && !empty($item);
        });
    }*/




