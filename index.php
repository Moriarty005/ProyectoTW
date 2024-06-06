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

var_dump($data);//Simple debugeo del vector data
//4o Renderizamos la página con los datos que va a necesitar
echo HTMLrenderWeb($data);

//Metodo que va a meter la informacion en el vector que le pasamos a renderHTLM
function getAction($p) {

  //Creamos el vector con el destino de la pagina la que vamos (se calcula en base al boton del navegador que se ha pulsado)
  $destino = obtenerDestinoPagina($p);
  //Creamos el vector con la accion que se va a reliazar (se hace comprobando que datos han llegado desde el POST)
  $accion = obtenerSiguienteAccion();

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

function obtenerSiguienteAccion(){

  $aux = [];

  //Si el metodo que se ha utilizado para enviar datos es POST
  if($_SERVER["REQUEST_METHOD"] == "POST"){

    //DEBUG::
    if(isset($_POST['submit'])){
      echo <<<HTML
      <p>Que se ha pulsado: {$_POST['submit']}</p>
      HTML;
    }else{
      echo <<<HTML
      <p>No se ha pulsado nada</p>
      HTML;
    }

    //Si es verdad que se ha pulsado algun boton de submit y que es el de borrar usuario
    if(isset($_POST['submit']) && $_POST['submit'] == "Borrar Usuario"){
      //Borramos el usuario de la base de datos
      $db = new CRUD();
      $db->deleteUser($_POST['id']);
      $db->__destruct();
    }

    //Si es verdad que se ha pulsado algun boton de submit y que es el de modificar usuario
    if(isset($_POST['submit']) && $_POST['submit'] == "Editar Usuario"){
      //Le indicamos que nos traiga la informacion del usuario para mostrarlo en el formulario
      $db = new CRUD();
      $aux['infoUsuarioEditable'] = $db->requestUser($_POST['id'])->fetch_assoc();
      //echo "DEBUG:: Valor del usuario que vamos a editar: {$aux['infoUsuarioEditable']}";
      $db->__destruct();
    }

    if(isset($_POST['submit']) && $_POST['submit'] == "Confirmar Edición Usuario"){
      $db = new CRUD();
      $formData = array(
          'nombre' => isset($_POST['nombre']) ? $_POST['nombre'] : null,
          'apellidos'   => isset($_POST['apellidos']) ? $_POST['apellidos'] : null,
          'DNI'   => isset($_POST['DNI']) ? $_POST['DNI'] : null,
          'mail'  => isset($_POST['mail']) ? $_POST['mail'] : null,
          'nacionalidad'   => isset($_POST['nacionalidad']) ? $_POST['nacionalidad'] : null,
          'tarjeta'   => isset($_POST['tarjeta']) ? $_POST['tarjeta'] : null
      );
      echo "DEBUG:: lo que enviamos a la base de datos para modificar el usuario: ";
      var_dump($formData);
      $db->updateUser($formData);
      //echo "DEBUG:: Valor del usuario que vamos a editar: {$aux['infoUsuarioEditable']}";
      $db->__destruct();

    }

    if(isset($_POST['submit']) && $_POST['submit'] == "Editar Habitación"){
      //Le indicamos que nos traiga la informacion del usuario para mostrarlo en el formulario
      $db = new CRUD();
      $aux['infoHabEditable'] = $db->requestRoom($_POST['id'])->fetch_assoc();
      //echo "DEBUG:: Valor del usuario que vamos a editar: {$aux['infoUsuarioEditable']}";
      $db->__destruct();
    }

    if(isset($_POST['submit']) && $_POST['submit'] == "Confirmar Edición Habitación"){
      $db = new CRUD();
      $formData = array(
          'id' => isset($_POST['id']) ? $_POST['id'] : null,
          'capacidad'   => isset($_POST['capacidad']) ? $_POST['capacidad'] : null,
          'numero_fotografias'   => isset($_POST['numero_fotografias']) ? $_POST['numero_fotografias'] : null,
          'estado'  => isset($_POST['estado']) ? $_POST['estado'] : null,
          'descripcion'   => isset($_POST['descripcion']) ? $_POST['descripcion'] : null,
          'precio'   => isset($_POST['precio']) ? $_POST['precio'] : null
      );
      echo "DEBUG:: lo que enviamos a la base de datos para modificar la habitacion: ";
      var_dump($formData);
      $db->updateRoom($formData);
      //echo "DEBUG:: Valor del usuario que vamos a editar: {$aux['infoUsuarioEditable']}";
      $db->__destruct();

    }
  }
  //Devolvemos el vector que puede o no tener cierta informacion
  return $aux;
}

//Metodo que maneja el inicio y cierre de sesion
function manejarSesion(){
  //Si el metodo que se ha utilizado para enviar datos es POST
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //control de inicio sesión
    if(isset($_POST['submit']) && $_POST['submit'] == "Iniciar sesión"){
      if(empty($_POST['email']) || empty($_POST['password'])){
        echo "No se han introducido todos los datos";
      }else{
        $email = strip_tags($_POST['email']);
        $password = $_POST['password']; //IMPORTANTE al cifrar la contraseña, se debe cifrar también aquí
        $db = new CRUD();
        $q = $db->login($email, $password);
        if($q){
          if(isset($_SESSION['tipo']))echo "El tipo de usuario es: " . $_SESSION['tipo'];
        }
      }
    }
    //control de registro
    if(isset($_POST['submit']) && $_POST['submit'] == "Confirmar datos"){
      $nombre = strip_tags($_POST['nombre']);
      //apellido no es obligatorio, por lo que tenemos que revisar que exista
      if(isset($_POST['apellidos'])){
        $apellidos = strip_tags($_POST['apellidos']);
      }else{
        $apellidos = '';
      }
      $dni = strip_tags($_POST['dni']);
      $email = strip_tags($_POST['mail']);
      $nacionalidad = strip_tags($_POST['nacionalidad']);
      if(isset($_POST['tipo'])){
        $tipo = strip_tags($_POST['tipo']);
      }else{
        $tipo = 'cliente';
      }
      $passwd = $_POST['passwd'];//password_hash($_POST['passwd'],PASSWORD_DEFAULT); //cifrar contraseña
      if(isset($_POST['foto'])){
        $foto = $_POST['foto'];
      }else{
        $foto = '1'; //porque es un int
      }
      $tarjeta = "1234";  //IMPORTANTE: poner campo tarjeta, cifrar tarjeta

      $db = new CRUD();
      $q = $db->register($nombre, $apellidos, $dni, $email, $nacionalidad, $tipo, $passwd, $foto, $tarjeta);
      if($q){
        echo "El registrado es" . $_SESSION['tipo'];
      }
    }
    //cierre de sesión
    if(isset($_POST['submit']) && $_POST['submit'] == "Cerrar sesión"){
      unset($_SESSION['tipo']);
      $usr = ($_SESSION['nombre']);
      unset($_SESSION['nombre']);
      $db = new CRUD();
      $db->log("Cierre sesión de" .$usr);
    }
  }
  //DEBUG::
  if(isset($_POST['submit'])){
    echo <<<HTML
    <p>Que se ha pulsado: {$_POST['submit']}</p>
    HTML;
  }else{
    echo <<<HTML
    <p>No se ha pulsado nada</p>
    HTML;
  }

}


//Metodo que registra a que seccion de la pagina web quiere ir el usuario
function obtenerDestinoPagina($p){

  $r = [];

  //Si no se ha pulsado ningun boton del navegador (se entra a la pagina web por primera vez)
  if (!isset($p['p'])) {
    //Indicamos que el destino es la pagina de bienvenida
    $r['controlador'] = 'bienvenida';

  } else{ //En caso contrario
    //Indicamos hacia donde ir en base al boton que se ha pulsado
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
      case 'habitaciones-list': $r['controlador'] = 'habitaciones-list'; $r['habitaciones'] = requestRoomsListFiltered();
        //$r['metodo'] = 'hello';
        break;
      default: $r['controlador'] = 'error';
      //$r['metodo']='hello';
    }
  }

  return $r;
}

//Metodo que trae los datos de las habitaciones
function requestRoomsListFiltered(){

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


?>