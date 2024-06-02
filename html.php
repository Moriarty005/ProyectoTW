<?php
// Esta función crea la web completa a partir de los datos que recibe en $data
// Es la única que debe utilizarse desde otras partes de la aplicación
function HTMLrenderWeb($data) {

  if($data == 'habitaciones'){
    $main = habitaciones();
  }else if($data == 'servicios'){
    $main = servicios();
  }else if($data == 'datos'){
    $main = datos();
  }else if($data == 'reservas'){
    $main = reservas();
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
            <a href="habitaciones.html">Habitaciones</a>
            <a href="servicios.html">Nuestros servicios</a>
            <a href="formulario.html">Introduzca sus datos</a>
            <a href="reservas.html">Consultar reservas(para recepcionistas)</a>
        </nav>
        $main
        <footer>
            <p>Tel:957333333 Correo:hotelo@o.com Cabra,Córdoba(España)Av/Góngora</p>
            <a href="caracteristicas_css.html">Comprobar características CSS (querys, responsive...)</a>
        </footer>
    </body>
    </html>
    HTML;
}

function habitaciones(){
  return "asdf";
}
function servicios(){
  return "asdf";
}
function datos(){
  return "asdf";
}
function reservas(){
  return "asdf";
}

?>