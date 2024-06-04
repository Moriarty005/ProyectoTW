<?php
require_once('html.php');
require_once('conexion.php');

  session_start();
  $db = Database::getInstance();


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
        case 'datos': $r['controlador'] = 'datos';
                    //$r['metodo'] = 'hello';
                    break;
        case 'reservas': $r['controlador'] = 'reservas';
                    //$r['metodo'] = 'hello';
                    break;
        default: $r['controlador'] = 'error';
                //$r['metodo']='hello';
      }
    }

    return $r;
}

if(isset($_GET['submit']) && $_GET['submit'] == "Enviar datos"){
  if(empty($_GET['email']) || emp($_GET['ctr'])){
    echo "No se han introducido todos los datos";
  }else{
    $email = $_GET['email'];
    $password = $_GET['ctr'];
    $q = $db->query("SELECT tipo FROM Usuario WHERE mail = '$email' AND passwd = '$password'");
    if(mysqli_num_rows($q) > 0){
      $row = mysqli_fetch_assoc($q);
      echo "El tipo de usuario es: " . $row['tipo'];
    }else{
      echo "El email o la contraseña son incorrectos";
    }
  }
}

if(isset($_GET['submit']) && $_GET['submit'] == "Registrarse"){
  if(empty($_GET['email']) || empty($_GET['passwd']) || empty($_GET['nombre']) || empty($_GET['apellidos']) || empty($_GET['dni']) || empty($_GET['tarjeta']) || empty($_GET['nacionalidad'])){
    echo "No se han introducido todos los datos";
  }else{
    $email = $_GET['email'];
    $password = $_GET['passwd'];
    $nombre = $_GET['nombre'];
    $apellidos = $_GET['apellidos'];
    $dni = $_GET['dni'];
    $tarjeta = $_GET['tarjeta']; 
    $nacionalidad = $_GET['nacionalidad']; 
    $q = $db->query("INSERT INTO usuarios (nombre, apellido, dni, email, nacionalidad, tipo, passwd, tarjeta)
                    VALUES ('$nombre', '$apellidos', '$dni', '$email', '$nacionalidad', 'cliente', '$passwd', '$tarjeta');");
    if(mysqli_affected_rows($db) > 0){
      echo "Usuario registrado";
    }else{
      echo "Error al registrar el usuario";
    }
  }
}


$data = getAction($_GET);

var_dump($data);

echo HTMLrenderWeb($data);
?>