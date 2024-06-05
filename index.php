<?php
require_once('html.php');
require_once('conexion.php');

session_start();
$db = new CRUD();
$db->login();

$data = getAction($_GET, $db);
if(!isset($_SESSION['tipo'])){
  $data['tipo'] = "anonimo"; //por defecto el usuario es anonimo
}else{
  $data['tipo'] = $_SESSION['tipo'];
}
var_dump($data);

function getAction($p, $db) {

    $r = [];
    if (!isset($p['p'])) {
      $r['controlador'] = 'bienvenida';
      //$r['metodo']='hello';
    } else{
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
        case 'registro': $r['controlador'] = 'registro';
                    //$r['metodo'] = 'hello';
                    break;
        case 'usuarios-list': $r['controlador'] = 'usuarios-list'; $r['usuarios'] = $db->requestUserList();
                    //$r['metodo'] = 'hello';
                    break;
        default: $r['controlador'] = 'error';
                //$r['metodo']='hello';
      }
    }

    return $r;
}

echo HTMLrenderWeb($data);
?>