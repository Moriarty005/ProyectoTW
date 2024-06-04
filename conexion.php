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
?>