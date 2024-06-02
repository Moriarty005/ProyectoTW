<?php
// Esta función crea la web completa a partir de los datos que recibe en $data
// Es la única que debe utilizarse desde otras partes de la aplicación
<<<<<<< HEAD
function HTMLrenderWeb($html) {

  return <<<HTML
    <!DOCTYPE html>
    <html>
      <body>
        <h1>Aplicación web con arquitectura MVC<span><a class="copyright" href="../copyright.html">&copy;</a></span></h1>
        
        <main>
          
        </main>
        <section class="infocode">
          <p>Este ejemplo muestra una aplicación completa con una estructura MVC</p>
          <ul>
            <li>Añade funcionalidad para añadir nuevas ciudades a la BBDD e incorpora la validación y detección de errores en el formulario de entrada de datos.</li>
            <li>Añade funcionalidad para realizar mantenimiento de la BBDD:
              <ul>
                <li>Se añade un nuevo modelo (mbackup) y un nuevo controlador (cbackup) además de algunas funciones nuevas en la vista (html.php) y la correspondiente adaptación del front-controller (index.php).</li>
                <li>Borrar la información de la BBDD, restaurar la BBDD original, restaurar la BBDD a partir de un fichero SQL enviado por el usuario.</li>
                <li>Obtener una copia de seguridad de la BBDD: cabe destacar que en este caso el servidor devuelve un fichero de datos (SQL) en lugar de una página web. Se hace uso de headers especiales en el mensaje de respuesta.</li>
                <li>Por simplicidad, en este ejemplo no se pide confirmación de estas operaciones.</li>
              </ul>
            </li>
          </ul>
        </section>
      </body>
    </html>
    HTML;
}
=======
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
            <a href="habitaciones.html">Habitaciones</a>
            <a href="servicios.html">Nuestros servicios</a>
            <a href="formulario.html">Introduzca sus datos</a>
            <a href="reservas.html">Consultar reservas(para recepcionistas)</a>
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

>>>>>>> 91a3a1dac8e7506f416327a7ea0ae2925bfaaeaf
?>