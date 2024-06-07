<?php

require_once('htmlRegistrarUsuario.php');

// Esta función crea la web completa a partir de los datos que recibe en $data
// Es la única que debe utilizarse desde otras partes de la aplicación
function HTMLrenderWeb($data) {

  $ret = '';
  $header = renderHeader($data);

    $nav = nav($data['tipo']);

  if($data['controlador'] == null){
    $main = bienvenida($data);
  }else{
    switch ($data['controlador']) {
        case 'bienvenida': $main = bienvenida($data); break;
        case 'habitaciones': $main = habitaciones(); break;
        case 'servicios': $main = servicios(); break;
        case 'registro': $main = registro($data['tipo']); break;
        case 'usuarios-list':
          $main = listadoUsuarios($data['tipo'],
              $data['usuarios'],
              (isset($data['accionUsuario'])?$data['accionUsuario']:null),
              (isset($data['accionUsuario'])&&$data['accionUsuario']=='modificarUSuario' ? $data['infoUsuarioEditable'] : null),
              (isset($data['mensajeError'])?$data['mensajeError']:null));
            break;
        case 'habitaciones-list':
            $main = listadoHabitaciones($data['tipo'],
            $data['habitaciones'],
            (isset($data['accionHabitacion'])?$data['accionHabitacion']:null),
            (isset($data['accionHabitacion'])&&$data['accionHabitacion']=='modificarHabitacion' ? $data['infoHabEditable'] : null),
            (isset($data['mensajeError'])?$data['mensajeError']:null));
            break;
      case 'reservas-list':
        $main = listadoReservas($data['tipo'],
            $data['reservas'],
            (isset($data['accionReserva'])?$data['accionReserva']:null),
            (isset($data['accionReserva'])&&$data['accionReserva']=='modificarReserva' ? $data['infoReservaEditable'] : null),
            (isset($data['mensajeError'])?$data['mensajeError']:null),
            (isset($data['infoNuevaReservaUsusarios'])? $data['infoNuevaReservaUsusarios'] : null),
            (isset($data['infoNuevaReservaHabitaciones'])? $data['infoNuevaReservaHabitaciones'] : null));
            break;
      case 'ed-perf':
        $main = editarPerfil($data['perfilUsuario']);
        break;
      case 'backup':
        $main = backup();
        break;
      case 'logs':
        $main = logs($data);
        break;
      default:
        $main = '<main><h1>Por implementar</h1></main>'; break;
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
            <form action="{$_SERVER['PHP_SELF']}" method="post">
                <input  type="submit" name="submit" value="Reseteo BD">
            </form>
            <p>Proyecto realizado por Alejandro Muñoz Gutiérrez y Álvaro González Luque.</p>
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
            <a href="#" onclick="showPopup()">Iniciar sesión</a>
            <a href="index.php?p=registro">Registro</a>
            HTML;
        }else{
            //necesita decir el nombre del usuario y permitir abrir una ventana de edición de sus datos 
            $dev .= <<<HTML
            <div class="contenedor-sesion">
              <form action="{$_SERVER['PHP_SELF']}" method="post">
                  <p> Bienvenido, {$data['nombre']}</p>
                  <a href="index.php?p=ed-perf">Editar perfil</a>
                  <input  type="submit" name="submit" value="Cerrar sesión">
              </form>
            HTML;
        }

    $dev .= <<<HTML
        </div>
        <script>
            function showPopup() {
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
      <a href="index.php?p=reservas-list">Consultar reservas</a>
HTML;
  }
  if($tipo_usuario == 'recepcionista'){
    $ret .= <<<HTML
      <a href="index.php?p=reservas-list">Consultar reservas(para recepcionistas)</a>
      <a href="index.php?p=habitaciones-list">Consultar habitaciones (para recepcionistas)</a>
      <a href="index.php?p=usuarios-list">Administrar clientes (para recepcionistas)</a>
HTML;
  }else if($tipo_usuario == 'admin'){
    $ret .= <<<HTML
      <a href="index.php?p=usuarios-list">Administrar usuarios (solo admins)</a>
      <a href="index.php?p=backup">Control de backups (solo admins)</a>
      <a href="index.php?p=logs">Ver logs</a>
HTML;
  }
  $ret .= <<<HTML
  </nav>
HTML;
  
  return $ret;

}



function editarPerfil($perfilUsuario){
  $ret = <<<HTML
  <div class="editar">
       <h2>Editar Usuario</h2>
      <form action="index.php?p=ed-perf" method="POST" novalidate class="editar">
        <label>Nombre:<input type="text" name="nombre" value="{$perfilUsuario["nombre"]}"></label>
        <label>Apellidos:<input type="text" name="apellidos" value="{$perfilUsuario["apellidos"]}"></label>
        <label>DNI (no editable):<input type="text" name="DNI" value="{$perfilUsuario["DNI"]}" readonly></label>
        <label>E-mail:<input type="email" name="mail" value="{$perfilUsuario["mail"]}"></label>
        <label>Nacionalidad:<input type="text" name="nacionalidad" value="{$perfilUsuario["nacionalidad"]}"></label>
        <label>Tarjeta:<input type="text" name="tarjeta" value="{$perfilUsuario["tarjeta"]}"></label>
        <input type="submit" name="submit" value="Confirmar Edición Usuario">
      </form>
  </div>
  HTML;
  return $ret;
}

function bienvenida($data){
  $aside = aside($data);

  $ret = <<<HTML
  <main>
    <menu>
        <img src="./img/hotel2.jpg" alt="Fotografía hotel O">
        <img src="./img/hotel.jpg" alt="Fotografía festival Japón">
        <p class="remarcar">En Hotel O encontrarás todas las comodidades para una feliz estancia en Japón, sea cual sea el motivo de su viaje: trabajo, turismo o 
            simplemente despejarte de la rutina del día a día.
        </p>
    </menu>
        
    $aside
       
  </main>
  HTML;
  
  return $ret;
}

function aside($data){
  //Número total de habitaciones del hotel.
  //Número de habitaciones libres.
  //Capacidad (nº de huéspedes) total del hotel.
  //Número de huéspedes alojados en el hotel*/.
  $ret = <<<HTML
  <aside>
    <section class="ocupacion">
      <p>Total de habitaciones: {$data['ocupacion']['hab_tot']}</p>
      <p>Habitaciones libres: {$data['ocupacion']['hab_lib']}</p>
      <p>Capacidad total: {$data['ocupacion']['capacidad_tot']}</p>
      <p>Huéspedes alojados: {$data['ocupacion']['ocupacion']}</p>
    </section>
      <p class="remarcar">Enlaces de interés: </p>
      <p><a href="https://japonismo.com/blog/sapporo-yuki-matsuri-festival-de-la-nieve-de-sapporo">Festival de nieve de Sapporo</a></p>
      <p><a href="https://www.japan.travel/es/spot/1466/">Festival de Ciruelos en Flor de Mito</a></p>
      <p><a href="https://es.wikipedia.org/wiki/Tanabata">Festival de las estrellas (tanabata)</a></p>
      <p><a href="https://youtu.be/OIAUBYb3ET4?si=F7qBS8sP0TstGY67&t=843">Más...</a></p>
  </aside>
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
        <p>Descripción: una habitación única. Cuenta con las mejores estancias y acceso a servicios especiales.</p>
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
          <p>En Hotel O también promocionamos las actividades culturales más indispensables de la zona y alrededores. Ofrecemos ofertas
          en entradas a diversos centros y recorridos por los parajes que pueden ser de mayor interés para todo tipo de turistas.</p>
      </div>
      <div>
          <h2>Transporte</h2>
          <p>Si no te gusta tanto el viaje guiado y tienes otros destinos en mente, en Hotel O disponemos de servicio de taxi ininterrumpido
          todo el día. Una llamada y estarán a tu disposición en pocos minutos.</p>
      </div>
      <div>
          <h2>Lavandería</h2>
          <p>Entre nuestras instalaciones podrás encontrar provechosa nuestra sala de lavandería con lavadora, secadora y plancha.</p>
      </div>
  </main>
  HTML;

  return $ret;
}

function listadoUsuarios($tipo_usuario, $lista_usuarios, $accion, $usuarioModificar, $errores){

    $ret = '';

    if($tipo_usuario != 'admin' && $tipo_usuario != 'recepcionista'){
        $ret = <<<HTML
            <main>
                <p>Esta sección no debería de aparecer. En este caso porque el usuario no es un recepcionista o admin</p>
            </main>
        HTML;
    }else{

        $ret = <<<HTML
          <main class="lista">
        HTML;
        if($accion == 'modificarUSuario'){
          if($usuarioModificar != null){
            $ret .= <<<HTML
              <div class="editar">
                   <h2>Editar Usuario</h2>
                  <form action="index.php?p=usuarios-list" method="POST" novalidate class="editar">
                    <label>Nombre:<input type="text" name="nombre" value="{$usuarioModificar["nombre"]}"></label>
                    <label>Apellidos:<input type="text" name="apellidos" value="{$usuarioModificar["apellidos"]}"></label>
                    <label>DNI (no editable):<input type="text" name="DNI" value="{$usuarioModificar["DNI"]}" readonly></label>
                    <label>E-mail:<input type="email" name="mail" value="{$usuarioModificar["mail"]}"></label>
                    <label>Nacionalidad:<input type="text" name="nacionalidad" value="{$usuarioModificar["nacionalidad"]}"></label>
                    <label>Tarjeta:<input type="text" name="tarjeta" value="{$usuarioModificar["tarjeta"]}"></label>
                    <input type="submit" name="submit" value="Confirmar Edición Usuario">
                  </form>
              </div>
            HTML;
          }
        }else if($accion == 'aniadirUsuario'){
          $ret .= <<<HTML
              <div class="editar">
                 <h2>Editar Usuario</h2>
                  <form action="index.php?p=usuarios-list" method="POST" novalidate class="editar">
                    <label>Nombre:<input type="text" name="nombre"></label>
                    <label>Apellidos:<input type="text" name="apellidos"></label>
                    <label>DNI:<input type="text" name="DNI"></label>
                    <label>E-mail:<input type="email" name="mail"></label>
                    <label>Nacionalidad:<input type="text" name="nacionalidad"></label>
                    <label>Contraseña:<input type="text" name="passwd"></label>
          HTML;
          //En caso de que el que el que añade al usuario sea un admin le permitimos determinar que tipo de usuario va a ser
          if($tipo_usuario == 'admin'){
            $ret .= <<<HTML
                  <label>Tipo:<select name="tipo" id="tipo">
                        <option value="cliente" selected>Cliente</option>
                        <option value="recepcionista">Recepcionista</option>
                        <option value="admin">Administrador</option>
                        </select></label>
            HTML;
          }

          $ret .= <<<HTML
                    <label>Tarjeta:<input type="text" name="tarjeta"></label>
                    <input type="submit" name="submit" value="Confirmar Nuevo Usuario">
                  </form>
              </div>  
          HTML;
        }else{
          $ret .= <<<HTML
          <form action="" method="POST" novalidate>
              <input type="submit" name="submit" value="Añadir usuario">
          </form>
          HTML;
        }
        if($errores != null){
            $ret .= <<<HTML
              <p>Error: {$errores}</p>
            HTML;
        }


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
                                    <input type="submit" name="submit" value="Editar Usuario">
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
                                <input type="submit" name="submit" value="Editar Usuario">
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
            </main>
        HTML;
    }

    return $ret;
}

function listadoHabitaciones($tipo_usuario, $lista_habitaciones, $accion, $habitacionModificar, $errores){

  if($tipo_usuario != 'recepcionista'){
    $ret = <<<HTML
            <main>
                <p>Esta sección no debería de aparecer. En este caso porque el usuario no es recepcionista</p>
            </main>
        HTML;
  }else{

    $ret = <<<HTML
      <main class="lista">
    HTML;

    if($accion == 'modificarHabitacion'){
      $fotos = isset($habitacionModificar["fotos"])?$habitacionModificar["fotos"]:0;
      $ret .= <<<HTML
              <div class="editar">
                   <h2>Editar habitación</h2>
                  <form action="index.php?p=habitaciones-list" method="POST" novalidate class="editar">
                    <label>Número de habitación (no editable):<input type="text" name="id" value="{$habitacionModificar["id"]}" readonly></label>
                    <label>Estado:<select name="estado" id="estado">
                              <option value="libre" selected>Libre</option>
                              <option value="reservada">Reservada</option>
                              <option selected value="en proceso">En proceso</option>
                              </select></label>
                    <label>Capacidad:<input type="number" name="capacidad" value="{$habitacionModificar["capacidad"]}"></label>
                    <label>Precio:<input type="number" name="precio" value="{$habitacionModificar["precio"]}"></label>
                    <label>Numero de fotos:<input type="number" name="numero_fotografias" value="{$fotos}"></label>
                    <label>Descripción:<input type="text" name="descripcion" value="{$habitacionModificar["descripcion"]}"></label>
                    
                    <input type="submit" name="submit" value="Confirmar Edición Habitación">
                  </form>
              </div>
      HTML;
    }else if($accion == 'aniadirHabitacion'){
      $ret .= <<<HTML
              <div class="editar">
                   <h2>Añadir habitación</h2>
                  <form action="index.php?p=habitaciones-list" method="POST" novalidate class="editar">
                    <label>Número de habitación:<input type="text" name="id"></label>
                    <label>Estado:<select name="estado" id="estado">
                              <option value="libre" selected>Libre</option>
                              <option value="reservada">Reservada</option>
                              <option value="en proceso">En proceso</option>
                              </select></label>
                    <label>Capacidad:<input type="number" name="capacidad"></label>
                    <label>Precio:<input type="number" name="precio"></label>
                    <label>Numero de fotos:<input type="number" name="numero_fotografias"></label>
                    <label>Descripción:<input type="text" name="descripcion"></label>
                    
                    <input type="submit" name="submit" value="Confirmar Nueva Habitación">
                  </form>
              </div>
      HTML;
    }else{
      $ret .= <<<HTML
          <form action="" method="POST" novalidate>
              <input type="submit" name="submit" value="Añadir habitación">
          </form>
          HTML;
    }
    //En caso de que haya errores  los muestro
    if($errores != null){
      $ret .= <<<HTML
              <p>Error: {$errores}</p>
            HTML;
    }

    //Headers de la lista
    $ret .= <<<HTML
              <div>
                  <table>
                      <tr>
                          <th>Número de habitacion</th>
                          <th>Estado</th>
                          <th>Capacidad</th>
                          <th>Precio</th>
                          <th>Número de fotos</th>
                          <th>Descripción</th>
                          <th>Acción</th>
                      </tr>
          HTML;
    //Elementos de la lista
    foreach ($lista_habitaciones as $tupla){
      $ret .= <<<HTML
                    <tr><td>{$tupla['id']}</td>
                        <td>{$tupla['estado']}</td>
                        <td>{$tupla['capacidad']}</td>
                        <td>{$tupla['precio']}</td>
                        <td>{$tupla['numero_fotografias']}</td>
                        <td>{$tupla['descripcion']}</td>
                        <td><form action="" method="POST">
                                <input type="hidden" name="id" value="{$tupla['id']}">
                                <input type="submit" name="submit" value="Editar Habitación">
                                <input type="submit" name="submit" value="Borrar Habitación">
                            </form>
                        </td>
                    </tr>
    HTML;
    }
    //Formulario del filtro de habitaciones
    $ret .= <<<HTML
                  </table>
                  <form method="post" action="">
                      <div>
                          <label> <input type="checkbox" name="roomType[]" value="libre"> Libre </label>
                          <label> <input type="checkbox" name="roomType[]" value="reservada"> Reservada </label>
                          <label> <input type="checkbox" name="roomType[]" value="en proceso"> En proceso </label>
                      </div>
                      <input type="submit" name='roomFilterListApply' value="Aplicar filtro">
                  </form>
              </div>
          HTML;

    $ret .= <<<HTML
      </main>
    HTML;
  }

  return $ret;
}

function listadoReservas($tipo_usuario, $lista_reservas, $accion, $reservaModificar, $errores, $userIds, $roomIds){
  if($tipo_usuario == 'cliente'){
    //Headers de la lista
    $ret = <<<HTML
            <main class="lista">
    HTML;
    if($accion == 'aniadirReserva'){
      $ret .= <<<HTML
                <div class="editar">
                     <h2>Añadir reserva</h2>
                     <form action="index.php?p=reservas-list" method="POST" novalidate class="editar">
                        <label>Número de habitación:<select name="id_habitacion" id="id_habitacion">
      HTML;
      if($roomIds != null){
        while ($row = $roomIds->fetch_assoc()) {
          $ret .= <<<HTML
                <option value="{$row['id']}" selected>{$row['id']}</option>
            HTML;
        }
      }
      $ret .= <<<HTML
                        </select></label>
                        <label>Ocupación:<input type="number" name="ocupacion"></label>
                        <label>Comentario:<input type="text" name="comentario"></label>
                        <label>Fecha de inicio:<input type="date" name="fecha_inicio"></label>
                        <label>Fecha de fin:<input type="date" name="fecha_fin"></label>
                        <input type="hidden" name="dni_usuario" value="{$_SESSION['dni']}">
                        <input type="submit" name="submit" value="Confirmar Nueva Reserva">
                      </form>
                  </div>
        HTML;
    }else{
      $ret .= <<<HTML
                    <form action="" method="POST" novalidate>
                        <input type="submit" name="submit" value="Añadir Reserva">
                    </form>
      HTML;
    }
    $ret .= <<<HTML
                    <table>
                        <tr>
                            <th>DNI usuario</th>
                            <th>Habitación</th>
                            <th>Número de clientes</th>
                            <th>Comentario</th>
                            <th>Fecha de inicio</th>
                            <th>Fecha de fin</th>
                        </tr>
            HTML;
    //Elementos de la lista
    foreach ($lista_reservas as $tupla) {
      if($tupla['dni_usuario']==$_SESSION['dni']){
        $ret .= <<<HTML
                      <tr><td>{$tupla['dni_usuario']}</td>
                          <td>{$tupla['id_habitacion']}</td>
                          <td>{$tupla['ocupacion']}</td>
                          <td>{$tupla['comentario']}</td>
                          <td>{$tupla['fecha_inicio']}</td>
                          <td>{$tupla['fecha_fin']}</td>
                      </tr>
        HTML;
      }
    }
    $ret .= <<<HTML
            </table>
        </main>
      HTML;
  }else if($tipo_usuario == 'recepcionista'){
    if($lista_reservas == null && !isset($lista_reservas)){

      $ret = <<<HTML
                <main>
                    <p>Actualmente no hay reservas en la base de datos</p>
                </main>
            HTML;
    }else{

      $ret = <<<HTML
        <main class="lista">
      HTML;

      if($accion == 'modificarReserva'){
        $ret .= <<<HTML
                <div class="editar">
                     <h2>Editar reserva</h2>
                    <form action="index.php?p=reservas-list" method="POST" novalidate class="editar">
                      <label>Cliente:<select name="dni_usuario" id="dni_usuario">
        HTML;
        if($userIds != null){
          while ($row = $userIds->fetch_assoc()) {
            //echo "DEBUG:: fila ";
            //var_dump($row);
            if($reservaModificar['dni_usuario'] == $row['DNI']){
              $ret .= <<<HTML
                
                <option value="{$row['DNI']}" selected>{$row['DNI']} </option>
            HTML;
            }else{
              $ret .= <<<HTML
                
                <option value="{$row['DNI']}">{$row['DNI']}</option>
            HTML;
            }
          }
        }
        $ret.= <<<HTML
                      </select></label>
                      <label>Número de habitación:<select name="id_habitacion" id="id_habitacion">
                      HTML;
        if($roomIds != null){
          while ($row = $roomIds->fetch_assoc()) {
            //echo "DEBUG:: fila ";
            //var_dump($row);
            if($reservaModificar['id_habitacion'] == $row['id']){
              $ret .= <<<HTML
                
                <option value="{$row['id']}" selected>{$row['id']} </option>
            HTML;
            }else{
              $ret .= <<<HTML
                
                <option value="{$row['id']}">{$row['id']}</option>
            HTML;
            }

          }
        }
        $fecha_ini = DateTime::createFromFormat('Y-m-d H:i:s', $reservaModificar['fecha_inicio'])->format('Y-m-d');
        $fecha_fin = DateTime::createFromFormat('Y-m-d H:i:s', $reservaModificar['fecha_fin'])->format('Y-m-d');
        $ret .= <<<HTML
                      </select></label>
                      <label>Fecha de inicio:<input type="date" name="fecha_inicio" value="{$fecha_ini}"></label>
                      <label>Fecha de fin:<input type="date" name="fecha_fin" value="{$fecha_fin}"></label>
                      <input type="hidden" name="id_reserva" value="{$reservaModificar['id_reserva']}">
                      <input type="submit" name="submit" value="Confirmar Edición Reserva">
                    </form>
                </div>
        HTML;
      }
      else if($accion == 'aniadirReserva'){
        $ret .= <<<HTML
                <div class="editar">
                     <h2>Añadir reserva</h2>
                    <form action="index.php?p=reservas-list" method="POST" novalidate class="editar">
                      <label>Cliente:<select name="dni_usuario" id="dni_usuario">
        HTML;
        if($userIds != null){
          while ($row = $userIds->fetch_assoc()) {
            $ret .= <<<HTML
                <option value="{$row['DNI']}" selected>{$row['DNI']}</option>
            HTML;
          }
        }
        $ret.= <<<HTML
                      </select></label>
                      <label>Número de habitación:<select name="id_habitacion" id="id_habitacion">
                      HTML;
        if($roomIds != null){
          while ($row = $roomIds->fetch_assoc()) {
            $ret .= <<<HTML
                <option value="{$row['id']}" selected>{$row['id']}</option>
            HTML;
          }
        }
        $ret .= <<<HTML
                      </select></label>
                      <label>Ocupación:<input type="number" name="ocupacion"></label>
                      <label>Comentario:<input type="text" name="comentario"></label>
                      <label>Fecha de inicio:<input type="date" name="fecha_inicio"></label>
                      <label>Fecha de fin:<input type="date" name="fecha_fin"></label>
                      
                      <input type="submit" name="submit" value="Confirmar Nueva Reserva">
                    </form>
                </div>
        HTML;
      }
      else{
        $ret .= <<<HTML
            <form action="" method="POST" novalidate>
                <input type="submit" name="submit" value="Añadir Reserva">
            </form>
            HTML;
      }
      //En caso de que haya errores  los muestro
      if($errores != null){
        $ret .= <<<HTML
                <p>Error: {$errores}</p>
              HTML;
      }

      //Headers de la lista
      $ret .= <<<HTML
                <div>
                    <table>
                        <tr>
                            <th>DNI usuario</th>
                            <th>Habitación</th>
                            <th>Número de clientes</th>
                            <th>Comentario</th>
                            <th>Fecha de inicio</th>
                            <th>Fecha de fin</th>
                            <th>Acción</th>
                        </tr>
            HTML;
      //Elementos de la lista
      foreach ($lista_reservas as $tupla){
        $ret .= <<<HTML
                      <tr><td>{$tupla['dni_usuario']}</td>
                          <td>{$tupla['id_habitacion']}</td>
                          <td>{$tupla['ocupacion']}</td>
                          <td>{$tupla['comentario']}</td>
                          <td>{$tupla['fecha_inicio']}</td>
                          <td>{$tupla['fecha_fin']}</td>
                          <td><form action="" method="POST">
                                  <input type="hidden" name="id_reserva" value="{$tupla['id_reserva']}">
                                  <input type="submit" name="submit" value="Editar Reserva">
                                  <input type="submit" name="submit" value="Borrar Reserva">
                              </form>
                          </td>
                      </tr>
        HTML;
      }
      //Formulario del filtro de reservas
      $ret .= <<<HTML
                    </table>
                    <form method="post" action="" >
                        <label> DNI de usuario a buscar:<input type="text" name="userIdFilter" >  </label>
                        <label> Número de habitación a buscar:<input type="text" name="roomNameFilter" >  </label>
                        <input type="submit" name='reservationFilterListApply' value="Aplicar filtro">
                    </form>
                </div>
            HTML;

      $ret .= <<<HTML
        </main>
      HTML;
    }
  }else{
    $ret = <<<HTML
            <main>
                <p>No tienes permisos para acceder a esta página.</p>
            </main>
        HTML;
  }

  return $ret;
}

function backup(){
  $errorArchivo = '';
  if(isset($_POST['backup_file']) && empty($_POST['backup_file'])){$errorArchivo = "<p class='errorNombre'>Suba un archivo de backup (.sql)</p>";}
  $ret = <<<HTML
  <main>
    <form class="backup" method="post" enctype="multipart/form-data" novalidate>
    <div>
        <label> Descargar en tu carpeta "Descargas" el archivo .sql backup: </label><input type="submit" name="submit" value="Descargar backup">
    </div>
    <div>
        <label> Utilizar un archivo .sql para recuperar la base de datos: </label><input type="file" name="file" accept=".sql">
        $errorArchivo
        <input type="submit" name="submit" value="Cargar backup">
    </div>
    </form>
  </main>
  HTML;
  return $ret;
}

function logs($data){
  $lista = $data['logs'];
  if($_SESSION['tipo'] == 'admin'){
    //Headers de la lista
    $ret = <<<HTML
            <main>
                <table>
                    <tr>
                        <th>Id</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>
            HTML;
    //Elementos de la lista
    foreach ($lista as $tupla) {
      $ret .= <<<HTML
                  <tr><td>{$tupla['id']}</td>
                      <td>{$tupla['fecha']}</td>
                      <td>{$tupla['accion']}</td>
                  </tr>
      HTML;
    }
    $ret .= <<<HTML
            </table>
        </main>
      HTML;
  }else{
    $ret = <<<HTML
        <main>
          <p>Acceso restringido</p>
        </main>
    HTML;
  }
    return $ret;
}

?>