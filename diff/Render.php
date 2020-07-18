<?php

namespace Dif\Dif\Render;

require_once __DIR__ . '/../vendor/autoload.php';


function render($tree)
{
   // print_r($tree);

    // проходим по корню.
    if (empty($tree['type'])) {
        $result3 = array_map(function ($child) {
            //print_r($child);

            // print_r($child['name'] . ' ' . $child['status']);
            //print_r(nl2br(PHP_EOL));
            render($child);

        }, $tree);

        return $result3;
    }

   // если листья.
  /* if ($tree['type'] === 'node') {
      /* if ($tree['type'] === 'children') {
           render($tree['data']);
           return $tree['name'] .  ' ' . $tree['status'];
           //print_r($tree['name'] .  ' ' . $tree['status']);
           //print_r(nl2br(PHP_EOL));
       } elseif ($tree['type'] === 'node') {*/
          // return $tree['name'] .  ' ' . $tree['status'];
          // print_r($tree['name'] . ' ' . $tree['status']);

      // }
       //return;
  // }


    //если children.
    if ($tree['type'] === 'children' ) {

        //берем тока массивы
            $filter = array_filter($tree, fn($child) => is_array($child));
           // print_r($filter);

            $result = '';
            $result1 = array_map(function ($child1) use ($result) {
                $result2 = array_map(function ($child2) use ($result) {
                   $result3 = array_map(function ($child3) use ($result) {
                      // print_r($child3);
                       print_r(nl2br(PHP_EOL));
                       print_r(nl2br(PHP_EOL));
                       return $child3['status'];
                   }, $child2);
                    print_r($result3);
                    //см что отдается ы rsult
                }, $child1);

            }, $filter);


              /* $result = array_reduce($child[0], function ($acc, $child1) use ($child)  {
                        $acc[] = $child1['name'];
                        $acc[] = $child1['status'];
                        return $acc;
                }, []);*/
                  //  print_r($result);
           // return $result;

return $result1;
         // print_r($result1);

    }



}



