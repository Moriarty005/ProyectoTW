<?php
require_once('html.php');
<<<<<<< HEAD
if (isset($_GET['controller'])) {
  $controller = $_GET['controller'];
} else {
  $controller = 'habitaciones';
}

<<<<<<< HEAD
<<<<<<< HEAD
echo HTMLrenderWeb($controller);
=======
echo HTMLrenderWeb();
>>>>>>> 91a3a1dac8e7506f416327a7ea0ae2925bfaaeaf
=======
echo HTMLrenderWeb($controller);
>>>>>>> alex
=======

$data = getAction($_GET);

echo HTMLrenderWeb($data);

function getAction($p) {
    $r = [];
    if (!isset($p['p'])) {
      $r['controlador'] = 'bienvenida';
      //$r['metodo']='hello';
    } else
    switch ($p['p']) {
        case 'bienvenida': $r['controlador'] = 'bienvenida';
                    //$r['metodo'] = 'hello';
                    break;
        case 'habitaciones': $r['controlador'] = 'habitaciones';
                    //$r['metodo'] = 'hello';
                    break;
        case 'servicios': $r['controlador'] = 'servicios';
                    //$r['metodo'] = 'hello';
                    break;
        case 'datos': $r['controlador'] = 'datos';
                    //$r['metodo'] = 'hello';
                    break;
        case 'reservas': $r['controlador'] = 'reservas';
                    //$r['metodo'] = 'hello';
                    break;
        default: $r['controlador'] = 'error';
                //$r['metodo']='hello';
    }
    return $r;
}
>>>>>>> 94f69e46d96430880d4412dff56aedf07e9de447
?>