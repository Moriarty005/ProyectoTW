<?php
require_once('html.php');

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
?>