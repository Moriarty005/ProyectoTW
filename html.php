<?php
// Esta función crea la web completa a partir de los datos que recibe en $data
// Es la única que debe utilizarse desde otras partes de la aplicación
function HTMLrenderWeb($data) {

  if($data['controlador'] == null){
    $main = bienvenida();
  }else{ //esto con un switch es más chulo
    if($data['controlador'] == 'bienvenida'){
      $main = bienvenida();
    }else if($data['controlador'] == 'habitaciones'){
      $main = habitaciones();
    }else if($data['controlador'] == 'servicios'){
      $main = servicios();
    }else if($data['controlador'] == 'datos'){
      $main = datos();
    }else if($data['controlador'] == 'reservas'){
      $main = reservas();
    }else if($data['controlador'] == 'error'){
      $main = error();
    }
  }

  return <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hotel O</title>
        <link rel="stylesheet" href="estilo.css" >

    </head>
    <body>
        <header>
            <img src="./img/icono.png" alt="Icon">
            <h1>HOTEL O</h1>
            <img src="./img/icono.png" alt="Icon">
        </header>
        <nav>
            <a href="index.php?p=bienvenida">Bienvenida</a>
            <a href="index.php?p=habitaciones">Habitaciones</a>
            <a href="index.php?p=servicios">Nuestros servicios</a>
            <a href="index.php?p=datos">Introduzca sus datos</a>
            <a href="index.php?p=reservas">Consultar reservas(para recepcionistas)</a>
        </nav>
        $main
        <footer>
            <p>Tel:957333333 Correo:hotelo@o.com Cabra,Córdoba(España)Av/Góngora</p>
            <a href="documentacion.php">Documentación</a>
        </footer>
    </body>
    </html>
    HTML;
}

function bienvenida(){
  $result = <<< HTML
    // hola uwu
    HTML;
  return $result;
}
function habitaciones(){
  $result = <<< HTML
    // habitaciones
    HTML;
  return $result;
}
function servicios(){
  $result = <<< HTML
    // servicios
    HTML;
  return $result;
}
function datos(){
  $result = <<< HTML
    // datos
    HTML;
  return $result;
}
function reservas(){
  $result = <<< HTML
    // reservas
    HTML;
  return $result;
}
function error(){
  $result = <<< HTML
    puta mierda
    HTML;
  return $result;
}

?>