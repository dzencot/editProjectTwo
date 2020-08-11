<?php
namespace Dif\Dif\DiffFiles;

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;


function getArrays()
{
    //$before = Yaml::parse(file_get_contents('./before.json'), Yaml::PARSE_OBJECT_FOR_MAP);
    $before = json_decode(file_get_contents(__DIR__ . '/../files/before.json'), true);
    $after = json_decode(file_get_contents(__DIR__ . '/../files/after.json'), true);

    return [$before, $after];
}
