<?php
require_once('html.php');
require_once('conexion.php');

session_start();
if($_SERVER["REQUEST_METHOD"] == "POST"){
  //inicio de sesion
  if(isset($_POST['submit']) && $_POST['submit'] == "Iniciar sesión"){  
    if(empty($_POST['email']) || empty($_POST['password'])){
      echo "No se han introducido todos los datos";
    }else{
      $email = strip_tags($_POST['email']);
      $password = strip_tags($_POST['password']);
      $db = new CRUD();
      $q = $db->login($email, $password);
      $db->__destruct();
    }
  }
  //registro de usuario
  if(isset($_POST['submit']) && $_POST['submit'] == "Confirmar datos"){
    $nombre = strip_tags($_POST['nombre']);
    if(isset($_POST['apellidos'])){
      $apellidos = strip_tags($_POST['apellidos']);
    }else{
        $apellidos = '';
    }
    $dni = strip_tags($_POST['dni']);
    $mail = strip_tags($_POST['mail']);
    $nacionalidad = strip_tags($_POST['nacionalidad']);
    if(isset($_POST['tipo'])){
      $tipo = strip_tags($_POST['tipo']);
    }else{
      $tipo = 'cliente';
    }
    $passwd = password_hash($_POST['passwd'],PASSWORD_DEFAULT);
    if(isset($_POST['foto'])){
      $foto = $_POST['foto'];
    }else{
      $foto = '1'; //porque es un int
    }
    if(isset($_POST['tarjeta'])){
      $tarjeta = $_POST['tarjeta'];
    }else{
      $tarjeta = '1'; //porque es un int también codificar
    }

    echo "Registrando datos";
    $db = new CRUD();
    $q = $db->register($nombre, $apellidos, $dni, $mail, $nacionalidad, $tipo, $passwd, $foto, $tarjeta);
    $db->__destruct();
    if($q){
      echo "Registrado correctamente";
    }
  }
  //cerrar sesión
  if(isset($_POST['submit']) && $_POST['submit'] == "Cerrar sesión"){
    echo "Sesión cerrada correctamente";
    unset($_SESSION['tipo']);
    unset($_SESSION['nombre']);
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