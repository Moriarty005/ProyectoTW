<?php
require_once('html.php');
require_once('conexion.php');

session_start();
if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['submit']) && $_POST['submit'] == "Iniciar sesión"){  
    $db = new CRUD();
    $q = $db->login();
    $db->__destruct();

    /*Perdon Alvaro es que me estoy liando con los debug */
    /*if($q){
      echo "El tipo de usuario es: " . $_SESSION['tipo'];
    }*/
  }
  if(isset($_POST['submit']) && $_POST['submit'] == "Confirmar datos"){ //he cambiado los botones enviar por submit
    echo "Registrando datos";
    $db = new CRUD();
    $q = $db->register();
    $db->__destruct();
    if($q){
      echo "Registrado correctamente";
    }
  }
  if(isset($_POST['submit']) && $_POST['submit'] == "Cerrar sesión"){
    echo "Sesión cerrada correctamente";
    unset($_SESSION['tipo']);
  }
  echo <<<HTML
    <p>Que se ha pulsado: {$_POST['submit']}</p>
  HTML;

  /*Aqui controlamos si se ha eliminado un usuario desde la lista del administrador*/
  if(isset($_POST['submit']) && $_POST['submit'] == "Borrar Usuario"){
    echo "Vamos a borrar un usuario";
    $db = new CRUD();
    $db->deleteUser($_POST['id']);
    $db->__destruct();
  }
}

$data = getAction($_GET);


if(!isset($_SESSION['tipo'])){
  $data['tipo'] = "anonimo"; //por defecto el usuario es anonimo
}else{
  $data['tipo'] = $_SESSION['tipo'];
  $data['nombre'] = $_SESSION['nombre'];
}
var_dump($data);

function getAction($p) {

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
        case 'reservas': $r['controlador'] = 'reservas';
                    //$r['metodo'] = 'hello';
                    break;
        case 'registro': $r['controlador'] = 'registro';
                    //$r['metodo'] = 'hello';
                    break;
        case 'usuarios-list': $r['controlador'] = 'usuarios-list'; $r['usuarios'] = requestUserListFiltered();
                    //$r['metodo'] = 'hello';
                    break;
        default: $r['controlador'] = 'error';
                //$r['metodo']='hello';
      }
    }

    return $r;
}

function requestUserListFiltered(){

  $db = new CRUD();
  $selectedTypes = null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userFilterListApply'])) {
    if (!empty($_POST['userType'])) {
        $selectedTypes = $_POST['userType'];
    }
  }

  $ret = $db->requestUserList($selectedTypes);
  $db->__destruct();

  return $ret;
}

echo HTMLrenderWeb($data);
?>