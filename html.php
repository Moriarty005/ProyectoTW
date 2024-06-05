<?php

require_once('htmlRegistrarUsuario.php');

// Esta función crea la web completa a partir de los datos que recibe en $data
// Es la única que debe utilizarse desde otras partes de la aplicación
function HTMLrenderWeb($data) {

  $ret = '';
  $header = renderHeader($data);

    //TODO: cambiar la barra de navegación en base al usuario
    $nav = nav($data['tipo']);

  if($data['controlador'] == null){
    $main = bienvenida();
  }else{
    switch ($data['controlador']) {
        case 'bienvenida':
            $main = bienvenida();
            break;
        case 'habitaciones':
            $main = habitaciones();
            break;
        case 'servicios':
            $main = servicios();
            break;
        case 'reservas':
            $main = reservas();
            break;
        case 'registro':
            $main = registro($data['tipo']);
            break;
        case 'usuarios-list':
            $main = listadoUsuarios($data['tipo'], $data['usuarios']);
            break;
        default:
            $main = '<main><h1>Por implementar</h1></main>';
            break;
    }
  }

  
  $ret .= <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hotel O</title>
        <link rel="stylesheet" href="estilo.css">
    </head>
    <body>
        $header
        $nav
        $main
        <footer>
            <p>Tel:957333333 Correo:hotelo@o.com Cabra,Córdoba(España)Av/Góngora</p>
            <a href="documentacion.php">Documentación</a>
        </footer>
    </body>
    </html>
    HTML;

    return $ret;
}

//Funciones específicas de escritura en el HTML dependiendo de lo requerido
function renderHeader($data){
    $dev = <<<HTML
    <header>
        <div class="logo">
            <img src="./img/icono.png" alt="Icon">
            <h1>HOTEL O</h1>
            <img src="./img/icono.png" alt="Icon">
        </div>
    HTML;
        //Si el usuario estña registrado, podrá ver su perfil o cerrar sesión, si no, tendrá la posibilidad de iniciar sesión o registrarse
        if($data['tipo'] == "anonimo"){
            $dev .= <<<HTML
            <div class="contenedor-sesion">
            <a href="#" onclick="show()">Iniciar sesión</a>
            <a href="index.php?p=registro">Registro</a>
            HTML;
        }else{
            //necesita decir el nombre del usuario y permitir abrir una ventana de edición de sus datos 
            $dev .= <<<HTML
            <form id="logout-form" method="post" novalidate>
                <input type="submit" name="submit" value="Cerrar sesión">
            </form>
            HTML;
        }

    $dev .= <<<HTML
        </div>
        <script>
            function showPopup(type) {
                var popup = document.getElementById('popup');
                popup.style.display = 'flex';

                document.getElementById('login-form').style.display = 'flex';
            }

            function closePopup() {
                var popup = document.getElementById('popup');
                popup.style.display = 'none';
            }
        </script>
        <div id="popup" class="popup">
            <div class="popup-contenido">
                <span class="popup-cerrar" onclick="closePopup()">X</span>
                <h2>Iniciar sesión</h2>
                <form id="login-form" method="post" novalidate>
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" name="email">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password">
                    <input type="submit" name="submit" value="Iniciar sesión"> 
                </form>
            </div>
        </div>
        </header>
    HTML;

    return $dev;
}

function nav($tipo_usuario){
  $ret = <<<HTML
  <nav>
    <a href="index.php?p=bienvenida">Bienvenida</a>
    <a href="index.php?p=servicios">Nuestros servicios</a>
    <a href="index.php?p=habitaciones">Habitaciones</a>
  HTML;
  if($tipo_usuario == 'cliente'){
    $ret .= <<<HTML
      <a href="index.php?p=consultar-reservas">Consultar reservas</a>
    HTML;
  }
  if($tipo_usuario == 'recepcionista'){
    $ret .= <<<HTML
      <a href="index.php?p=consultar-reservas">Consultar reservas(para recepcionistas)</a>
      <a href="index.php?p=habitaciones-list">Consultar habitaciones (para recepcionistas)</a>
      <a href="index.php?p=usuarios-list">Administrar clientes (para recepcionistas)</a>
    HTML;
  }else if($tipo_usuario == 'admin'){
    $ret .= <<<HTML
      <a href="index.php?p=usuarios-list">Administrar usuarios (solo admins)</a>
    HTML;
  }
  $ret .= <<<HTML
  </nav>
  HTML;
  
  return $ret;

}

function bienvenida(){
  
  $ret = <<<HTML
  <main>
    <menu>
        <img src="./img/hotel2.jpg" alt="Fotografía hotel O">
        <img src="./img/hotel.jpg" alt="Fotografía festival Japón">
        <p class="remarcar">En Hotel O encontrarás todas las comodidades para una feliz estancia en Japón, sea cual sea el motivo de su viaje: trabajo, turismo o 
            simplemente despejarte de la rutina del día a día.
        </p>
    </menu>
    <aside>
        <p class="remarcar">Enlaces de interés: </p>
            <p><a href="https://japonismo.com/blog/sapporo-yuki-matsuri-festival-de-la-nieve-de-sapporo">Festival de nieve de Sapporo</a></p>
            <p><a href="https://www.japan.travel/es/spot/1466/">Festival de Ciruelos en Flor de Mito</a></p>
            <p><a href="https://es.wikipedia.org/wiki/Tanabata">Festival de las estrellas (tanabata)</a></p>
            <p><a href="https://youtu.be/OIAUBYb3ET4?si=F7qBS8sP0TstGY67&t=843">Más...</a></p>
        
    </aside>
  </main>
  HTML;
  
  return $ret;
}

function habitaciones(){
  $ret = <<<HTML
  <main>
    <div>
      <p class="remarcar">Modelo habitación "Sanrio characters":</p>
      <p>Capacidad: de 2 a 4 personas</p>
      <p>Descripción: esta habitación es ideal para los pequeños o los amantes de los adorables personajes de Shintaro Tsuji.
          Comparte dormitorio con Hello Kitty, My Melody, Keroppi ¡y muchos más!</p>
      <figure class="imagen">
          <img class="galeria" src="./img/sanrio.jpg" alt="Habitación sanrio">
          <figcaption>Habitación sanrio</figcaption>
      </figure>
    </div>
    <div>
        <p class="remarcar">Modelo habitación tradicional japonesa:</p>
        <p>Capacidad: de 2 a 6 personas</p>
        <p>Descripción: sumérgete de lleno en la experiencia tradicional japonesa. Contempla la finura de los muebles, cerámicas
            y hábitos hogareños japoneses.</p>
        <figure class="imagen">
            <img class="galeria" src="./img/tradicional.jpg" alt="Habitación tradicional">
            <figcaption>Habitación tradicional</figcaption>
        </figure>
    </div>
    <div>
        <p class="remarcar">Modelo de habitación para parejas:</p>
        <p>Capacidad: 2 personas</p>
        <p>Descripción: disfruta de la estancia en pareja en nuestras habitaciones especiales con un aura tan íntima como ninguna.</p>
        <figure class="imagen">
            <img class="galeria" src="./img/parejas.jpg" alt="Habitación parejas">
            <figcaption>Habitación parejas</figcaption>
        </figure>
    </div>
    <div>
        <p class="remarcar">Modelo de habitación suite:</p>
        <p>Capacidad: 2 personas</p>
        <p>Descripción: una habitación única. Cuenta con las mejores estancias y acceso a servicios esoeciales.</p>
        <figure class="imagen">
            <img class="galeria" src="./img/suite.jpeg" alt="Habitación suite">
            <figcaption>Habitación parejas</figcaption>
        </figure>
    </div>
  </main>  
  HTML;
  
  return $ret;
}

function servicios(){
   
  $ret = <<<HTML
  <main>
      <div>
          <h2>Recepción</h2>
          <p>Recepcionista 24h. a su disposición en caso de extravío de llaves, atención al cliente...</p>
      </div>
      <div>
          <h2>Catering</h2>
          <p>Servicio de catering para desayunos y almuerzos, con gran variedad de platos regionales.</p>
      </div>
      <div>
          <h2>Excursiones, venta de entradas</h2>
          <p>En hotel O también promocionamos las actividades culturales más indispensables de la zona y alrededores. Ofrecemos ofertas
          en entradas a diversos centros y recorridos por los parajes que pueden ser de mayor interés para todo tipo de turistas.</p>
      </div>
  </main>
  HTML;

  return $ret;
}

function listadoUsuarios($tipo_usuario, $lista_usuarios){

    $ret = '';
    $datosModificar = null;

    if($tipo_usuario != 'admin' && $tipo_usuario != 'recepcionista'){
        $ret = <<<HTML
            <main>
                <p>Esta sección no debería de aparecer. En este caso porque el usuario es un cliente o un usuario anónimo</p>
            </main>
        HTML;
    }else{
        if($lista_usuarios == null){

            $ret = <<<HTML
                <main>
                    <p>Actualmente no hay usuarios en la base de datos</p>
                </main>
            HTML;
        }else{
            $ret = <<<HTML
            <main class="lista">
            HTML;
        
            $ret .= <<<HTML
                <div>
                    <table>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>DNI</th>
                            <th>Email</th>
                            <th>Nacionalidad</th>
                            <th>Tarjeta</th>
                            <th>Acción</th>
                        </tr>
            HTML;
    
            if($tipo_usuario == 'recepcionista'){
                foreach ($lista_usuarios as $tupla){ 
                    if($tupla['tipo'] == 'cliente'){
                        $ret .= <<<HTML
                            <tr><td>{$tupla['nombre']}</td>
                                <td>{$tupla['apellidos']}</td>
                                <td>{$tupla['DNI']}</td>
                                <td>{$tupla['mail']}</td>
                                <td>{$tupla['nacionalidad']}</td>
                                <td>{$tupla['tarjeta']}</td>
                                <td><form action="" method="POST">
                                        <input type="hidden" name="id" value="{$tupla['DNI']}">
                                        <input type="button" name="submit" value="Modificar Usuario" onclick="showPopupModificarUsuario()">
                                        <input type="submit" name="submit" value="Borrar Usuario">
                                    </form>
                                </td>
                            </tr>
                        HTML;
                    }
                }
            }else if($tipo_usuario == 'admin'){
                foreach ($lista_usuarios as $tupla){ 
                    $ret .= <<<HTML
                        <tr><td>{$tupla['nombre']}</td>
                            <td>{$tupla['apellidos']}</td>
                            <td>{$tupla['DNI']}</td>
                            <td>{$tupla['mail']}</td>
                            <td>{$tupla['nacionalidad']}</td>
                            <td>{$tupla['tarjeta']}</td>
                            <td><form action="" method="POST">
                                    <input type="hidden" name="id" value="{$tupla['DNI']}">
                                    <input type="button" name="submit" value="Modificar Usuario" onclick="showPopupModificarUsuario()">
                                    <input type="submit" name="submit" value="Borrar Usuario">
                                </form>
                            </td>
                        </tr>
                    HTML;
                }
            }
        
            $ret .= <<<HTML
                    </table>
                </div>
            HTML;
        
            if($tipo_usuario == 'admin'){
                $ret .= <<<HTML
                    <form method="post" action="">
                        <div>
                            <label> <input type="checkbox" name="userType[]" value="cliente"> Cliente </label>
                            <label> <input type="checkbox" name="userType[]" value="recepcionista"> Recepcionista </label>
                            <label> <input type="checkbox" name="userType[]" value="admin"> Administrador </label>
                        </div>
                        <input type="submit" name='userFilterListApply' value="Aplicar filtro">
                    </form>
                HTML;
            }
        
            $ret .= <<<HTML
                    <script>
                    function showPopupModificarUsuario(type) {
                        var popup = document.getElementById('popupModificarUsuario');
                        popup.style.display = 'flex';

                        document.getElementById('login-form').style.display = 'flex';
                    }

                    function closePopupModificarUsuario() {
                        var popup = document.getElementById('popupModificarUsuario');
                        popup.style.display = 'none';
                    }
                    </script>
                    <div id="popupModificarUsuario" class="popupModificarUsuario">
                        <div class="popup-contenido">
                            <span class="popup-cerrar" onclick="closePopupModificarUsuario()">X</span>
                            <h2>Modificar Usuario</h2>
            HTML;

            if($datosModificar == null){
                $ret .= <<<HTML
                    <p>Error al traer el usuario de la base de datos</p>
                HTML;
            }else{
                $ret .= <<<HTML
                    <form id="modificar-usuario-form" method="post" novalidate>
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="modificarNombre" name="nombre">
                        <label for="apellidos">Apellidos:</label>
                        <input type="text" id="modificarApellidos" name="apellidos">
                        <label for="email">Correo electrónico:</label>
                        <input type="email" id="modificarEmail" name="email">
                        <input type="submit" name="submit" value="Modificar usuario"> 
                    </form>
                HTML;
            }

            $ret .= <<<HTML
                            
                        </div>
                    </div>
                </main>
            HTML;
        }
    }

    return $ret;
}


function reservas(){
  $ret = <<<HTML
  <main>
      <div class="table">
      
          <span> Habitaciones </span>
          <span> Reservas </span> 
          <span> Nº Hab.</span> 
              <span>Cap.</span>
              <span>Hoy</span>
              <span>-1d</span>
              <span>+1d</span>
              <span>+2d</span>
              <span>+3d</span>
              <span>+4d</span>
              <span>+5d</span>
              <span>+6d</span>
              <span>+1d</span>
          <span>101</span> 
              <span>2</span>
              <span>R</span>
              <span>R</span>
              <span>R</span>
              <span> </span>
              <span> </span>
              <span> </span>
              <span> </span>
          <span>102</span> 
              <span>2</span>
              <span>R</span>
              <span>R</span>
              <span> </span>
              <span> </span>
              <span>P</span>
              <span>P</span>
              <span></span>
          <span>103</span> 
              <span>3</span>
              <span>M</span>
              <span>M</span>
              <span>M</span>
              <span>M</span>
              <span>M</span>
              <span> </span>
              <span> </span>
          <span>201</span> 
              <span>2</span>
              <span> </span>
              <span> </span>
              <span> </span>
              <span>R</span>
              <span>R</span>
              <span> </span>
              <span> </span>
          <span>202</span> 
              <span>4</span>
              <span>R</span>
              <span> </span>
              <span> </span>
              <span> </span>
              <span> </span>
              <span> </span>
              <span> </span>
          <span>203</span> 
              <span>2</span>
              <span> </span>
              <span> </span>
              <span>R</span>
              <span>R</span>
              <span>R</span>
              <span>R</span>
              <span> </span>
          <span>204</span> 
              <span>4</span>
              <span>R</span>
              <span> </span>
              <span>M</span>
              <span>M</span>
              <span>M</span>
              <span>M</span>
              <span>M</span>
          <span>301</span> 
              <span>3</span>
              <span> </span>
              <span>R</span>
              <span>P</span>
              <span>P</span>
              <span>R</span>
              <span>R</span>
              <span> </span>
          <span>302</span> 
              <span>6</span>
              <span> </span>
              <span>M</span>
              <span>M</span>
              <span> </span>
              <span>R</span>
              <span>R</span>
              <span>R</span>
          <span> Plazas </span> 
          <span>Total</span> 
              <span> 25 </span>
              <span> </span>
              <span>19</span>
              <span>15</span>
              <span>21</span>
              <span>21</span>
              <span>24</span>
              <span>24</span>
              <span > </span>
          <span >Usadas</span> 
              <span> 12 </span> </span>
              <span>7</span>
              <span>4</span>
              <span>4</span>
              <span>13</span>
              <span>11</span>
              <span>6</span> </span>
          <span>Libres</span> 
              <span> 13 </span>
              <span>12</span>
              <span>8</span>
              <span>14</span>
              <span>6</span>
              <span>11</span>
              <span>18</span>
      </div>
  </main>
  HTML;

  return $ret;
}

?>