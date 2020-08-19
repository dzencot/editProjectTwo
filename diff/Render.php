<?php

namespace Dif\Dif\Render;

require_once __DIR__ . '/../vendor/autoload.php';


function render($array, $depth)
{
    //Делаем читаемый массив.
    $result = array_map(function ($child) use ($depth) {
        $formattedValue = getFormattedValue($child, $depth);
        if ($child['type'] === 'removed') {
           return "- " . $formattedValue;
        } elseif ($child['type'] === 'add') {
           return "+ " . $formattedValue;
        // Одинаковые значения
        } elseif ($child['type'] === 'unchanged') {
           return str_repeat('&nbsp;', 3) . $formattedValue;
        // Разные значения
        } elseif ($child['type'] === 'changed') {
            // Объекты
            if (is_array($child['value'])) {
                return "{$child['type']} {$child['name']}: " . "{" . nl2br(PHP_EOL) . implode(array_keys($child['value'])) . ": " . implode($child['value']) . nl2br(PHP_EOL) . str_repeat('&nbsp;', $depth ) . "}";
            }
            // Строки
            return "- {$child['oldValue']}" . nl2br(PHP_EOL) . str_repeat('&nbsp;', $depth) . "+ {$child['newValue']}";
        // Объект и строка
        } elseif ($child['type'] === 'nested') {
            unset($child['type']);

            $result = array_map(function ($subChild) use ($child, $depth) {
                if (is_array($subChild)) {
                    //Пробел для Settinga6 НО при этом и других берет
                    return render($subChild, $depth + 5);
                }
            }, $child);
            return $result;
        }
       // print_r(nl2br(PHP_EOL));
    }, $array);

    //Массив переводим в строку.
    $resultRender = array_reduce($result, function ($acc, $child) use ($depth) {
            if (is_array($child)) {
                $acc .= str_repeat('&nbsp;', $depth) .  implode(array_keys($child)) . ': {' . nl2br(PHP_EOL) . implode($child) . str_repeat('&nbsp;', $depth) .  '}' . nl2br(PHP_EOL);
                return $acc;
            }
            $acc .= str_repeat('&nbsp;', $depth) .  $child . nl2br(PHP_EOL);
            return $acc;
    }, '');

    return $resultRender;
}

function renderWithDepth($array) {
    // Начинаем с глубины 0
    return render($array, 0);
}

function getFormattedValue($child, $depth) {
    if (is_array($child['value'])) {
        return "{$child['value']}: " . "{" . nl2br(PHP_EOL) . str_repeat('&nbsp;', $depth + 5 ) . implode(array_keys($child['value'])) . ": " . implode($child['value']) . nl2br(PHP_EOL) . str_repeat('&nbsp;', $depth ) . "}";
    }
    return "{$child['name']}:" . " {$child['value']}" ;
}

function makeIndent($depth) {
    return str_repeat('&nbsp;', $depth);
}




