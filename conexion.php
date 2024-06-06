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

  public function update($table, $data, $condition) {
    $fields = "";
    foreach ($data as $key => $value)
      $fields .= "$key = '$value', ";
    $fields = substr($fields, 0, -2);
    $sql = "UPDATE $table SET $fields WHERE $condition";
    return $this->db->query($sql);
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
    } catch (PDOException $e) {
      throw $e;
    }
  }

  public function deleteUser($dni) {
    try {
      $this->db->query("DELETE FROM Usuario WHERE DNI='$dni'");
    } catch (PDOException $e) {
      throw $e;
    }
  }

  public function login($email, $password){ //IMPORTANTE pasar los elementos del posts saneados en el index
    //Iniciar sesión (comprobar que la sesión no está iniciada en el momento)
    $q = $this->db->query("SELECT tipo, nombre, passwd FROM Usuario WHERE mail = '$email'");

    //guardar variables de sesión del ingresado
    if($q){
      $row = mysqli_fetch_assoc($q);
      //if(password_verify($password, $row['passwd'])){
      if($password == $row['passwd']){
        $_SESSION['tipo'] = $row['tipo'];
        $_SESSION['nombre'] = $row['nombre'];
      }else{
        echo "Contraseña incorrecta";
      }
    }else{
      echo "Email incorrecto";
    }
    return $q;
  }

  public function register($nombre, $apellidos, $dni, $mail, $nacionalidad, $tipo, $passwd, $foto, $tarjeta){ //IMPORTANTE pasar los elementos del posts saneados en el index
    $q = $this->db->query("INSERT INTO Usuario (nombre, apellidos, DNI, mail, nacionalidad, tipo, passwd, foto, tarjeta) VALUES ('$nombre', '$apellidos', '$dni', '$mail', '$nacionalidad', '$tipo', '$passwd', '$foto', '$tarjeta')");        
    //guardar variables de sesión del ingresado
    if($q){
      $_SESSION['tipo'] = $tipo;
      $_SESSION['nombre'] = $nombre;
    }else{
      echo "No se ha podido registrar el usuario";
    }
    return $q;
  }
}
?>