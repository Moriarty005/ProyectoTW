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
        case 'registro': $r['controlador'] = 'registro';
                    //$r['metodo'] = 'hello';
                    break;
        default: $r['controlador'] = 'error';
                //$r['metodo']='hello';
      }
    }

    return $r;
}

//Iniciar sesión (comprobar que la sesión no está iniciada en el momento)
if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['submit']) && $_POST['submit'] == "Iniciar sesión"){  
    if(empty($_POST['email']) || empty($_POST['password'])){
      echo "No se han introducido todos los datos";
    }else{
      $email = $_POST['email'];
      $password = $_POST['password'];
      $q = $db->query("SELECT tipo FROM Usuario WHERE mail = '$email' AND passwd = '$password'");
      if(mysqli_num_rows($q) > 0){
        $row = mysqli_fetch_assoc($q);
        echo "El tipo de usuario es: " . $row['tipo'];
        $_SESSION['tipo'] = $row['tipo'];
      }else{
        echo "El email o la contraseña son incorrectos";
      }
    }
  }
}

$data = getAction($_GET);
$data['tipo'] = $_SESSION['tipo'];
var_dump($data);


echo HTMLrenderWeb($data);
?>