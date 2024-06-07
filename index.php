<?php
require_once('html.php');
require_once('conexion.php');

//Pasos que tomamos cuando ejecutamos index.php
//1o: iniciamos la sesion
session_start();
//2o Manejamos todo lo que tenga que ver con el inicio de sesion
manejarSesion();
//3o metemos en data (que va a ser el array que le pasamos a renderHTML) toda la infromacion necesaria (donde vamos, que accion vamos a realizar, que usuario esta usando la pagina en este momento, etc)
$data = getAction($_GET);

//var_dump($data);//Simple debugeo del vector data
//4o Renderizamos la página con los datos que va a necesitar
echo HTMLrenderWeb($data);

//Metodo que va a meter la informacion en el vector que le pasamos a renderHTLM
function getAction($p) {

  //Creamos el vector con la accion que se va a reliazar (se hace comprobando que datos han llegado desde el POST)
  $accion = obtenerSiguienteAccion();
  //Creamos el vector con el destino de la pagina la que vamos (se calcula en base al boton del navegador que se ha pulsado)
  $destino = obtenerDestinoPagina($p);

  //Creamos el vector donde almacenaremos el tipo de usuario que esta visitando la pagina en este momento
  if(!isset($_SESSION['tipo'])){
    $usuario['tipo'] = "anonimo"; //por defecto el usuario es anonimo
  }else{
    $usuario['tipo'] = $_SESSION['tipo'];
    $usuario['nombre'] = $_SESSION['nombre'];
  }

  //Juntamos los vectores en uno solo y lo devolvemos
  $res = $destino + $accion + $usuario;
  return $res;
}

function obtenerSiguienteAccion()
{
  $aux = [];

  //Si el metodo que se ha utilizado para enviar datos es POST
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Si es verdad que se ha pulsado algun boton de submit y que es el de borrar usuario
    if (isset($_POST['submit']) && $_POST['submit'] == "Añadir usuario") {
      //Añadimos el usuario de la base de datos
      $aux['accionUsuario'] = 'aniadirUsuario';
    }
    if (isset($_POST['submit']) && $_POST['submit'] == "Confirmar Nuevo Usuario") {

      $error = comprobarUsuarioCorrecto('nuevoUsuario');
      if ($error != null) {
        $aux['accionUsuario'] = 'aniadirUsuario';
        $aux['mensajeError'] = $error;
      } else {

        $formData = array(
            "nombre" => isset($_POST['nombre']) ? strip_tags($_POST['nombre']) : null,
            "apellidos" => isset($_POST['apellidos']) ? strip_tags($_POST['apellidos']) : null,
            "DNI" => isset($_POST['DNI']) ? strip_tags($_POST['DNI']) : null,
            "mail" => isset($_POST['mail']) ? strip_tags($_POST['mail']) : null,
            "nacionalidad" => isset($_POST['nacionalidad']) ? strip_tags($_POST['nacionalidad']) : null,
            "tipo" => isset($_POST['tipo']) ? strip_tags($_POST['tipo']) : "cliente",
            "passwd" => isset($_POST['passwd']) ? password_hash($_POST['passwd'], PASSWORD_DEFAULT) : null,
            "foto" => null,
            "tarjeta" => isset($_POST['tarjeta']) ? strip_tags($_POST['tarjeta']) : null
        );
        $db = new CRUD();
        $db->addUser($formData);
        $db->__destruct();
      }
    }

    //Si es verdad que se ha pulsado algun boton de submit y que es el de añadir habitacion
    if (isset($_POST['submit']) && $_POST['submit'] == "Añadir habitación") {
      //Añadimos el usuario de la base de datos
      $aux['accionHabitacion'] = 'aniadirHabitacion';
    }

    if (isset($_POST['submit']) && $_POST['submit'] == "Confirmar Nueva Habitación") {

      $error = comprobarHabitacionCorrecta();

      if ($error != null) {
        $aux['accionHabitacion'] = 'aniadirHabitacion';
        $aux['mensajeError'] = $error;
      } else {

        $formData = array(
            'id' => strip_tags(isset($_POST['id'])) ? $_POST['id'] : null,
            'capacidad' => strip_tags(isset($_POST['capacidad'])) ? $_POST['capacidad'] : null,
            'numero_fotografias' => strip_tags(isset($_POST['numero_fotografias'])) ? $_POST['numero_fotografias'] : null,
            'estado' => strip_tags(isset($_POST['estado'])) ? $_POST['estado'] : null,
            'descripcion' => strip_tags(isset($_POST['descripcion'])) ? $_POST['descripcion'] : null,
            'precio' => strip_tags(isset($_POST['precio'])) ? $_POST['precio'] : null
        );
        //var_dump($formData);
        $db = new CRUD();
        $db->addRoom($formData);
        //echo "DEBUG:: Valor del usuario que vamos a editar: {$aux['infoUsuarioEditable']}";
        $db->__destruct();
      }
    }

    //Si es verdad que se ha pulsado algun boton de submit y que es el de añadir habitacion
    if (isset($_POST['submit']) && $_POST['submit'] == "Añadir Reserva") {


      $db = new CRUD();
      //Añadimos el usuario de la base de datos
      $aux['accionReserva'] = 'aniadirReserva';
      if(isset($_SESSION['tipo']) && $_SESSION['tipo'] == "recepcionista"){
        $aux['infoNuevaReservaUsusarios'] = $db->requestUserId();
      }
      $aux['infoNuevaReservaHabitaciones'] = $db->requestRoomId();
      $db->__destruct();
    }

    if (isset($_POST['submit']) && $_POST['submit'] == "Confirmar Nueva Reserva") {

      $error = comprobarReservaCorrecta('aniadirReserva');

      if ($error != null) {
        //Hubo un error y tenemos que recargar la pagina avisando de los errores que han sucedido
        $aux['accionReserva'] = 'aniadirReserva';
        $db = new CRUD();
        if(isset($_SESSION['tipo']) && $_SESSION['tipo'] == "recepcionista"){
          $aux['infoNuevaReservaUsusarios'] = $db->requestUserId();
        }
        $aux['infoNuevaReservaHabitaciones'] = $db->requestRoomId();
        $db->__destruct();
        $aux['mensajeError'] = $error;
      } else {

        $formData = array(
            'dni_usuario' => strip_tags(isset($_POST['dni_usuario'])) ? $_POST['dni_usuario'] : null,
            'id_habitacion' => strip_tags(isset($_POST['id_habitacion'])) ? $_POST['id_habitacion'] : 0,
            'ocupacion' => strip_tags(isset($_POST['ocupacion'])) ? $_POST['ocupacion'] : null,
            'comentario' => strip_tags(isset($_POST['comentario'])) ? $_POST['comentario'] : null,
            'fecha_inicio' => strip_tags(isset($_POST['fecha_inicio'])) ? $_POST['fecha_inicio'] : null,
            'fecha_fin' => strip_tags(isset($_POST['fecha_fin'])) ? $_POST['fecha_fin'] : null
        );
        //var_dump($formData);
        //var_dump($formData);
        $db = new CRUD();
        $db->addReservation($formData);
        $db->__destruct();
      }
    }

    //Si es verdad que se ha pulsado algun boton de submit y que es el de borrar usuario
    if (isset($_POST['submit']) && $_POST['submit'] == "Borrar Usuario") {
      //Borramos el usuario de la base de datos
      $db = new CRUD();
      $db->deleteUser($_POST['id']);
      $db->__destruct();
    }

    //Si es verdad que se ha pulsado algun boton de submit y que es el de borrar habitacion
    if (isset($_POST['submit']) && $_POST['submit'] == "Borrar Habitación") {
      //Borramos la habiacion de la base de datos
      $db = new CRUD();
      $db->deleteRoom($_POST['id']);
      $db->__destruct();
    }

    if (isset($_POST['submit']) && $_POST['submit'] == "Borrar Reserva") {
      //Borramos la habiacion de la base de datos
      $db = new CRUD();
      $db->deleteReservation($_POST['id_reserva']);
      $db->__destruct();
    }

    //Si es verdad que se ha pulsado algun boton de submit y que es el de modificar usuario
    if (isset($_POST['submit']) && $_POST['submit'] == "Editar Usuario") {
      //Le indicamos que nos traiga la informacion del usuario para mostrarlo en el formulario
      $db = new CRUD();
      $aux['accionUsuario'] = 'modificarUSuario';
      $aux['infoUsuarioEditable'] = $db->requestUser($_POST['id'])->fetch_assoc();
      $db->__destruct();
    }

    if (isset($_POST['submit']) && $_POST['submit'] == "Confirmar Edición Usuario") {

      $error = comprobarUsuarioCorrecto('modificarUsuario');

      if ($error != null) {
        $db = new CRUD();
        $aux['accionUsuario'] = 'modificarUSuario';
        $aux['infoUsuarioEditable'] = $db->requestUser($_POST['DNI'])->fetch_assoc();
        $aux['mensajeError'] = $error;
        $db->__destruct();

      } else {
        $formData = array(
            'nombre' => isset($_POST['nombre']) ? strip_tags($_POST['nombre']) : null,
            'apellidos' => isset($_POST['apellidos']) ? strip_tags($_POST['apellidos']) : null,
            'DNI' => isset($_POST['DNI']) ? strip_tags($_POST['DNI']) : null,
            'mail' => isset($_POST['mail']) ? strip_tags($_POST['mail']) : null,
            'nacionalidad' => isset($_POST['nacionalidad']) ? strip_tags($_POST['nacionalidad']) : null,
            'tarjeta' => isset($_POST['tarjeta']) ? strip_tags($_POST['tarjeta']) : null
        );
        $db = new CRUD();
        $db->updateUser($formData);
        $db->__destruct();
      }
    }

    if (isset($_POST['submit']) && $_POST['submit'] == "Editar Habitación") {
      //Le indicamos que nos traiga la informacion del usuario para mostrarlo en el formulario
      $db = new CRUD();
      $aux['accionHabitacion'] = 'modificarHabitacion';
      $aux['infoHabEditable'] = $db->requestRoom($_POST['id'])->fetch_assoc();
      $db->__destruct();
    }

    if (isset($_POST['submit']) && $_POST['submit'] == "Confirmar Edición Habitación") {

      $errores = comprobarHabitacionCorrecta('');
      if ($errores != null) {
        $db = new CRUD();
        $aux['accionHabitacion'] = 'modificarHabitacion';
        $aux['infoHabEditable'] = $db->requestRoom($_POST['id'])->fetch_assoc();
        $db->__destruct();
        $aux['mensajeError'] = $errores;
      } else {
        $db = new CRUD();
        $formData = array(
            'id' => strip_tags(isset($_POST['id'])) ? $_POST['id'] : null,
            'capacidad' => strip_tags(isset($_POST['capacidad'])) ? $_POST['capacidad'] : null,
            'numero_fotografias' => strip_tags(isset($_POST['numero_fotografias'])) ? $_POST['numero_fotografias'] : 0,
            'estado' => strip_tags(isset($_POST['estado'])) ? $_POST['estado'] : null,
            'descripcion' => strip_tags(isset($_POST['descripcion'])) ? $_POST['descripcion'] : null,
            'precio' => strip_tags(isset($_POST['precio'])) ? $_POST['precio'] : null
        );
        //echo "DEBUG:: lo que enviamos a la base de datos para modificar la habitacion: ";
        //var_dump($formData);
        $db->updateRoom($formData);
        //echo "DEBUG:: Valor del usuario que vamos a editar: {$aux['infoUsuarioEditable']}";
        $db->__destruct();
      }
    }
      if (isset($_POST['submit']) && $_POST['submit'] == "Editar Reserva") {
        //Le indicamos que nos traiga la informacion del usuario para mostrarlo en el formulario
        $aux['accionReserva'] = 'modificarReserva';
        $db = new CRUD();
        $aux['infoReservaEditable'] = $db->requestReservation($_POST['id_reserva'])->fetch_assoc();
        $aux['infoNuevaReservaUsusarios'] = $db->requestUserId();
        $aux['infoNuevaReservaHabitaciones'] = $db->requestRoomId();
        $db->__destruct();
      }

      if (isset($_POST['submit']) && $_POST['submit'] == "Confirmar Edición Reserva") {

        $errores = comprobarReservaCorrecta('modificarReserva');
        if ($errores != null) {
          $aux['accionReserva'] = 'modificarReserva';
          $aux['mensajeError'] = $errores;
          $db = new CRUD();
          $aux['infoReservaEditable'] = $db->requestReservation($_POST['id_reserva'])->fetch_assoc();
          $aux['infoNuevaReservaUsusarios'] = $db->requestUserId();
          $aux['infoNuevaReservaHabitaciones'] = $db->requestRoomId();
          $db->__destruct();

        } else {
          $db = new CRUD();
          $formData = array(
              'id_reserva' => strip_tags(isset($_POST['id_reserva'])) ? $_POST['id_reserva'] : null,
              'dni_usuario' => strip_tags(isset($_POST['dni_usuario'])) ? $_POST['dni_usuario'] : null,
              'id_habitacion' => strip_tags(isset($_POST['id_habitacion'])) ? $_POST['id_habitacion'] : null,
              'fecha_inicio' => strip_tags(isset($_POST['fecha_inicio'])) ? $_POST['fecha_inicio'] : null,
              'fecha_fin' => strip_tags(isset($_POST['fecha_fin'])) ? $_POST['fecha_fin'] : null
          );
          $db->updateReservation($formData, $_POST['id_reserva']);
          $db->__destruct();
        }
      }
    }
    //Devolvemos el vector que puede o no tener cierta informacion
    return $aux;
}

function comprobarUsuarioCorrecto($accion)
{
  $ret = null;
  if (!isset($_POST['nombre']) || empty($_POST['nombre'])) {
    $ret .= "Error con el nombre del usuario, no puede estar vacío\n";
  }
  if (!isset($_POST['DNI']) || empty($_POST['DNI'])) {
    $ret .= "Error con el DNI del usuario, no puede estar vacío\n";
  }
  if (!isset($_POST['mail']) || empty($_POST['mail'])) {
    $ret .= "Error con el Email del usuario, no puede estar vacío\n";
  }
  if ($accion == 'nuevoUsuario') {
    if (!isset($_POST['passwd']) || empty($_POST['passwd'])) {
      $ret .= "Error con la contraseña del usuario, no puede estar vacío\n";
    }
  }
  return $ret;
}

function comprobarHabitacionCorrecta()
{
  $ret = null;
  if (!isset($_POST['id']) || empty($_POST['id'])) {
    $ret .= "Error con el número de habitación, no puede estar vacío\n";
  }
  if (!isset($_POST['capacidad']) || empty($_POST['capacidad'])) {
    $ret .= "Error con la capacidad de la habitación, no puede estar vacía\n";
  }
  if (!isset($_POST['estado']) || empty($_POST['estado'])) {
    $ret .= "Error con el estado de la habitación, no puede estar vacío\n";
  }
  return $ret;
}

function comprobarReservaCorrecta($accion)
{

  $ret = null;

  if(existeReserva($_POST['id_habitacion'])){
    $ret .= "La habitacion está ocupada en esta fecha\n";
  }

  $ret = null;
  if (!isset($_POST['dni_usuario']) || empty($_POST['dni_usuario'])) {
    $ret .= "Error con el usuario, debe especificar un usuario\n";
  }
  if (!isset($_POST['id_habitacion']) || empty($_POST['id_habitacion'])) {
    $ret .= "Error con la habitación, debe especificar una\n";
  }
  if($accion == 'aniadirReserva'){
    if (!isset($_POST['ocupacion']) || empty($_POST['ocupacion'])) {
      $ret .= "Error con la ocupación, debe especificar un número de huéspedes\n";
    }
  }
  if (!isset($_POST['fecha_inicio']) || empty($_POST['fecha_inicio'])) {
    $ret .= "Error con la fecha de inicio, debe especificar ser especificada\n";
  }
  if (!isset($_POST['fecha_fin']) || empty($_POST['fecha_fin'])) {
    $ret .= "Error con la fecha de fin, debe especificar ser especificada\n";
  }else{
    $fecha_ini = DateTime::createFromFormat('Y-m-d', $_POST['fecha_inicio']);
    $fecha_fin = DateTime::createFromFormat('Y-m-d', $_POST['fecha_fin']);
    if($fecha_ini>$fecha_fin){
      $ret .= "No puede ser que la fecha de inicio sea posterior a la fecha de fin de la reserva";
    }
  }
  return $ret;
}

function existeReserva($id_habitacion){

  $ret = false;
  $db = new CRUD();
  $q = $db->existsReservation($id_habitacion);
  $fecha_ini = DateTime::createFromFormat('Y-m-d', $_POST['fecha_inicio'])->format('Y-m-d');
  while ($row = $q->fetch_assoc()) {
    $fecha_fin = DateTime::createFromFormat('Y-m-d H:i:s', $row['fecha_fin'])->format('Y-m-d');
    /*echo ".................DEBUG:: fechas que comparamos: ";
    var_dump($fecha_ini);
    echo " y ";
    var_dump($fecha_fin);
    echo ".................";*/
    if($fecha_ini < $fecha_fin){
      echo ".-.-.-.-.-.-.-.-.-.-.----.--.-.-.-.-.-.-.-.-.-..-.-.--..-.-.-.-.-";
      $ret = true;
    }
  }
  $db->__destruct();

  return $ret;
}

//Metodo que maneja el inicio y cierre de sesion
function manejarSesion(){
  //Si el metodo que se ha utilizado para enviar datos es POST
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //control de inicio sesión
    if (isset($_POST['submit']) && $_POST['submit'] == "Iniciar sesión") {
      if (empty($_POST['email']) || empty($_POST['password'])) {
        echo "No se han introducido todos los datos";
      } else {
        $email = strip_tags($_POST['email']);
        $password = $_POST['password']; //IMPORTANTE al cifrar la contraseña, se debe cifrar también aquí
        $db = new CRUD();
        $q = $db->login($email, $password);
        $db->__destruct();
        /*if ($q) {
          if (isset($_SESSION['tipo'])) echo "El tipo de usuario es: " . $_SESSION['tipo'];
        }*/
      }
    }
    //control de registro
    if (isset($_POST['submit']) && $_POST['submit'] == "Confirmar datos") {
      $formData = array(
          "nombre" => isset($_POST['nombre']) ? strip_tags($_POST['nombre']) : null,
          "apellidos" => isset($_POST['apellidos']) ? strip_tags($_POST['apellidos']) : null,
          "DNI" => isset($_POST['DNI']) ? strip_tags($_POST['DNI']) : null,
          "mail" => isset($_POST['mail']) ? strip_tags($_POST['mail']) : null,
          "nacionalidad" => isset($_POST['nacionalidad']) ? strip_tags($_POST['nacionalidad']) : null,
          "tipo" => isset($_POST['tipo']) ? strip_tags($_POST['tipo']) : "cliente",
          "passwd" => isset($_POST['passwd']) ? password_hash($_POST['passwd'], PASSWORD_DEFAULT) : null,
          "foto" => null,
          "tarjeta" => isset($_POST['tarjeta']) ? strip_tags($_POST['tarjeta']) : null
      );
      $db = new CRUD();
      $db->addUser($formData);
      $db->__destruct();
      /*if ($q) {
        echo "El registrado es" . $_SESSION['tipo'];
      }*/
    }
    //cierre de sesión
    if (isset($_POST['submit']) && $_POST['submit'] == "Cerrar sesión") {
      unset($_SESSION['tipo']);
      $usr = ($_SESSION['nombre']);
      unset($_SESSION['nombre']);
      $db = new CRUD();
      $db->log("Cierre sesión de: " . $_SESSION['dni']);
      $db->__destruct();
      unset($_SESSION['nombre']);
    }
    //CUIDADO: reseteo de la base de datos
    if (isset($_POST['submit']) && $_POST['submit'] == "Reseteo BD") {
      $db = new CRUD();
      $db->reset('sentenciaReseteo.txt');
      $db->__destruct();
    }
    //Descargar backup
    if (isset($_POST['submit']) && $_POST['submit'] == "Descargar backup") {
      $db = new CRUD();
      $db->crearBackup();
      $db->__destruct();
    }
    //Cargar backup
    if (isset($_POST['submit']) && $_POST['submit'] == "Cargar backup") {
      if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        // Ruta temporal donde se guarda el archivo subido
        $tmp_file = $_FILES['file']['tmp_name'];
        $db = new CRUD();
        $db->reset($tmp_file);
        $db->__destruct();
      } /*else {
        echo "No se ha enviado el backup";
      }*/
    }
  }
  /*if (isset($_POST['submit'])) {
    echo <<<HTML
  <p>Que se ha pulsado: {$_POST['submit']}</p>
  HTML;
  } else {
    echo <<<HTML
  <p>No se ha pulsado nada</p>
  HTML;
  }*/
}


//Metodo que registra a que seccion de la pagina web quiere ir el usuario
function obtenerDestinoPagina($p)
{

  $r = [];

  //Si no se ha pulsado ningun boton del navegador (se entra a la pagina web por primera vez)
  if (!isset($p['p'])) {
    //Indicamos que el destino es la pagina de bienvenida
    $r['controlador'] = 'bienvenida';
    $db = new CRUD();
    $r['ocupacion'] = $db->obtenerDatosOcupación();
    $db->__destruct();
  } else { //En caso contrario
    //Indicamos hacia donde ir en base al boton que se ha pulsado
    switch ($p['p']) {
      case 'bienvenida':
        $r['controlador'] = 'bienvenida';
        $db = new CRUD();
        $r['ocupacion'] = $db->obtenerDatosOcupación();
        $db->__destruct();
        break;
      case 'habitaciones':
        $r['controlador'] = 'habitaciones';
        break;
      case 'servicios':
        $r['controlador'] = 'servicios';
        break;
      case 'registro':
        $r['controlador'] = 'registro';
        break;
      case 'usuarios-list':
        $r['controlador'] = 'usuarios-list';
        $r['usuarios'] = requestUserListFiltered();
        break;
      case 'habitaciones-list':
        $r['controlador'] = 'habitaciones-list';
        $r['habitaciones'] = requestRoomsListFiltered();
        break;
      case 'reservas-list':
        $r['controlador'] = 'reservas-list';
        $r['reservas'] = requestReservationListFiltered();
        break;
      case 'backup':
        $r['controlador'] = 'backup';
        break;
      case 'ed-perf':
        $r['controlador'] = 'ed-perf';
        $r['perfilUsuario'] = requestUserProfile($_SESSION['dni']);
        break;
      case 'logs':
        $r['controlador'] = 'logs';
        $r['logs'] = requestLogs();
        break;
      default:
        $r['controlador'] = 'error';
        break;
    }
  }

  return $r;
}

function requestLogs(){
  $db = new CRUD();
  $ret = $db->requestLogsAdmin();
  $db->__destruct();
  return $ret;
}

function requestUserProfile($dni){

  $db = new CRUD();
  $ret = $db->requestUser($dni)->fetch_assoc();
  $db->__destruct();

  return $ret;
}

//Metodo que trae los datos de las habitaciones
function requestRoomsListFiltered()
{

  $db = new CRUD();
  $selectedTypes = null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['roomFilterListApply'])) {
    if (!empty($_POST['roomType'])) {
      $selectedTypes = $_POST['roomType'];
    }
  }

  $ret = $db->requestRoomList($selectedTypes);
  $db->__destruct();

  return $ret;
}

//Metodo que trae los datos de los usuarios que cierto tipo
//Se utiliza cuando un admin o un recepcionista pulsa el boton del navegador que muestra la lista de usuarios en base a quien lo ha pulsado
function requestUserListFiltered()
{

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


function requestReservationListFiltered()
{
  $db = new CRUD();
  $selectedTypes = null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservationFilterListApply'])) {
    if (!empty($_POST['userIdFilter'])) {
      $selectedTypes['dni_usuario'] = $_POST['userIdFilter'];
    }
    if (!empty($_POST['habNumFilter'])) {
      $selectedTypes['id_habitacion'] = $_POST['roomNameFilter'];
    }
  }
  $ret = $db->requestReservationList($selectedTypes);
  $db->__destruct();

  return $ret;
}

?>