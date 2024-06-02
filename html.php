<?php
// Esta función crea la web completa a partir de los datos que recibe en $data
// Es la única que debe utilizarse desde otras partes de la aplicación
function HTMLrenderWeb() {

  return <<<HTML
    <!DOCTYPE html>
    <html>
      casasdfasdf
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


function alvaro() {
    return <<<HTML
    <h1>arbaro</h1>
    HTML;
  }

?>