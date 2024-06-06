<?php
require_once('dbcredentials.php');

/* Gestión de la conexión a la BBDD con un patrón "singleton" */
final class Database {
  private static $instance;
  private static $lasterr = null;

  private function __construct() {  }

  function __destruct() {
    if (self::$instance != null)
      self::$instance = null;
  }

  private static function connect() {
    self::$lasterr=null;

    // Crear conexión
    $conn = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

    // Verificar conexión
    if ($conn->connect_error) {
      $lasterr = $conn->connect_error; //se almacena el mensaje de error
      die("Conexión fallida: " . $conn->connect_error);
    }
    
    return $conn;
  }

  public static function getInstance() {
    if (!self::$instance)
      self::$instance = self::connect();
    return self::$instance;
  }

  public static function getError() {
    return self::$lasterr;
  }

  
}

class CRUD {
  private $db;

  function __construct() {
    $this->db = Database::getInstance();
  }

  function __destruct() {
    $this->db = null;
  }

  public function requestUser($dni) {

    if($dni === null){
      return null;
    }else{
      try {

        $sql = "SELECT * FROM Usuario WHERE DNI='$dni'";
        return $this->db->query($sql);
      }catch (\mysql_xdevapi\Exception $exception){
        echo "Exception: ".$exception->getMessage();
      }
    }
  }

  public function requestRoom($id) {

    if($id === null){
      return null;
    }else{
      try {

        $sql = "SELECT * FROM Habitacion WHERE id='$id'";
        return $this->db->query($sql);
      }catch (\mysql_xdevapi\Exception $exception){
        echo "Exception: ".$exception->getMessage();
      }
    }
  }

  public function requestUserList($filtros = null) {
    
    if($filtros == null){
      $sql = "SELECT * FROM Usuario";
      return $this->db->query($sql);
    }else{
      $sql = "SELECT * FROM Usuario WHERE ";
      $conditions = [];
      
      foreach($filtros as $key) {
          $conditions[] = "tipo = '$key'";
      }
      
      $sql .= implode(' OR ', $conditions);
      
      //echo "DEBUG QUERY BD:: $sql";
      return $this->db->query($sql);
    }
  }

  public function requestRoomList($filtros = null) {

    if($filtros == null){
      $sql = "SELECT * FROM Habitacion";
      return $this->db->query($sql);
    }else{
      $sql = "SELECT * FROM Habitacion WHERE ";
      $conditions = [];

      foreach($filtros as $key) {
        $conditions[] = "estado = '$key'";
      }

      $sql .= implode(' OR ', $conditions);

      //echo "DEBUG QUERY BD:: $sql";
      return $this->db->query($sql);
    }
  }

  public function update($table, $data, $condition) {
    $fields = "";
    foreach ($data as $key => $value)
      $fields .= "$key = '$value', ";
    $fields = substr($fields, 0, -2);
    $sql = "UPDATE $table SET $fields WHERE $condition";
    return $this->db->query($sql);
  }

  public function updateUser($data) {
    $fields = "";
    foreach ($data as $key => $value)
      $fields .= "$key = '$value', ";
    $fields = substr($fields, 0, -2);
    $sql = "UPDATE Usuario SET $fields WHERE DNI='{$data['DNI']}'";
    try {
      $this->db->query($sql);
    }catch (\mysql_xdevapi\Exception $exception){
      echo "Exception: ".$exception->getMessage();
    }
  }

  public function updateRoom($data) {
    $fields = "";
    foreach ($data as $key => $value)
      $fields .= "$key = '$value', ";
    $fields = substr($fields, 0, -2);
    $sql = "UPDATE Habitacion SET $fields WHERE id='{$data['id']}'";
    try {
      $this->db->query($sql);
    }catch (\mysql_xdevapi\Exception $exception){
      echo "Exception: ".$exception->getMessage();
    }
  }

  public function delete($table, $condition) {
    $sql = "DELETE FROM $table WHERE $condition";
    if(mysqli_num_rows($sql)){
      return $this->db->query($sql);
    }else{
      return null;
    }
  }

  public function deleteUser($dni) {
    try {
      $this->db->query("DELETE FROM Usuario WHERE DNI='$dni'");
      //esto es una nota para que si está esto funcionando pongas los siguientes logs (poniendo un comentario tipo "usuario borrado" y tal)
      //inserción en la tabla de logs
      //      $this->log("Registro del usuario " .$email);
    } catch (PDOException $e) {
      throw $e;
    }
  }

  public function login($email, $password){ //IMPORTANTE pasar los elementos del posts saneados en el index
    try{
      $q = $this->db->query("SELECT tipo, nombre, passwd FROM Usuario WHERE mail = '$email'"); //revisar que en la base de datos email sea unique
      if ($q) {
        $row = mysqli_fetch_assoc($q);
        //if(password_verify($password, $row['passwd'])){ //para cuando esté cifrada la contraseña
        if ($password == $row['passwd']) {
          $_SESSION['tipo'] = $row['tipo'];
          $_SESSION['nombre'] = $row['nombre'];
          //inserción en la tabla de logs
          $this->log("Inicio sesión del usuario " . $email);
        } else {
          echo "La contraseña es incorrecta";
          $this->log("Fallo al ingresar clave de " . $email);
        }
      } else {
        echo "El email es incorrecto o no existe en la base de datos";
        $this->log("Fallo al iniciar sesión, " . $email . "no reconocido");
      }
      return $q;
    }catch(mysqli_sql_exception $e){
      echo "Error: ".$e->getMessage();
      return $q;
    }
  }

  public function register($nombre, $apellidos, $dni, $email, $nacionalidad, $tipo, $passwd, $foto, $tarjeta){ //IMPORTANTE pasar los elementos del posts saneados en el index

    try{
      $q = $this->db->query("INSERT INTO Usuario (nombre, apellidos, DNI, mail, nacionalidad, tipo, passwd, foto, tarjeta) VALUES ('$nombre', '$apellidos', '$dni', '$email', '$nacionalidad', '$tipo', '$passwd', '$foto', '$tarjeta')");
      if ($q) {
        $_SESSION['tipo'] = $tipo;
        $_SESSION['nombre'] = $nombre;
        //inserción en la tabla de logs
        $this->log("Registro del usuario " . $email);
      } else {
        echo "No se ha podido registrar el usuario";
      }
      return $q;
    }catch(mysqli_sql_exception $e){
      echo "Error: ".$e->getMessage();
      return $q;
    }
  }

  public function log($comentario){
    $fecha = date('Y-m-d H:i:s');
    $q = $this->db->query("INSERT INTO logs (fecha, accion) VALUES ('$fecha', '$comentario')");
    return $q;
  }

}
?>