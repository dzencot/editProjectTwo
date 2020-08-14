<?php

namespace Dif\Dif\Render;

require_once __DIR__ . '/../vendor/autoload.php';

function render($array, $depth)
{
    //Делаем читаемый массив.
    $result = array_map(function ($child) use ($depth) {
        if ($child['type'] === '-') {
            if (!is_array($child['children'])) {
                return "{$child['type']}  {$child['name']}: {$child['children']}";
            } elseif (is_array($child['children'])) {
                return "{$child['type']} {$child['name']}: " . "{" . nl2br(PHP_EOL) . str_repeat('&nbsp;', $depth + 5 ) . implode(array_keys($child['children'])) . ": " . implode($child['children']) . nl2br(PHP_EOL) . str_repeat('&nbsp;', $depth ) . "}";
            }
        } elseif ($child['type'] === '+') {
            if (!is_array($child['children'])) {
                return "{$child['type']} {$child['name']}: {$child['children']}";

            } elseif (is_array($child['children'])) {
                return "{$child['type']} {$child['name']}: " . "{" . nl2br(PHP_EOL) . str_repeat('&nbsp;', $depth + 5 ) . implode(array_keys($child['children'])) . ": " . implode($child['children']) . nl2br(PHP_EOL) . str_repeat('&nbsp;', $depth ) . "}";
            }
        } elseif ($child['type'] === ' ') {
            if (!is_array($child['children'])) {
                return "{$child['type']} {$child['name']}: {$child['children']}";
            } elseif (is_array($child['children'])) {
                return "{$child['type']} {$child['name']}: " . "{" . nl2br(PHP_EOL) .  implode(array_keys($child['children'])) . ": " . implode($child['children']) . nl2br(PHP_EOL) . str_repeat('&nbsp;', $depth ) . "}";
            }
        } elseif ($child['type'] === 'changed') {
            if (!is_array($child['children'])) {
                return "{$child['children']}";
            } elseif (is_array($child['children'])) {
                return "{$child['type']} {$child['name']}: " . "{" . nl2br(PHP_EOL) . implode(array_keys($child['children'])) . ": " . implode($child['children']) . nl2br(PHP_EOL) . str_repeat('&nbsp;', $depth ) . "}";
            }
        } elseif ($child['type'] === 'nested') {
            unset($child['type']);

            $result = array_map(function ($subChild) use ($child, $depth) {
                if (is_array($subChild)) {
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
