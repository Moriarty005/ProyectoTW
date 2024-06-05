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

  public function read($table, $fields = "*", $condition = "") {
    $sql = "SELECT $fields FROM $table";
    if ($condition != "")
      $sql .= " WHERE $condition";
    return $this->db->query($sql);
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
    return $this->db->query($sql);
  }

  public function login(){
    //Iniciar sesión (comprobar que la sesión no está iniciada en el momento)
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      if(isset($_POST['submit']) && $_POST['submit'] == "Iniciar sesión"){  
        if(empty($_POST['email']) || empty($_POST['password'])){
          echo "No se han introducido todos los datos";
        }else{
          $email = $_POST['email'];
          $password = $_POST['password'];
          $q = $db->query("SELECT tipo FROM Usuario WHERE mail = '$email' AND passwd = '$password'");
          if(mysqli_num_rows($q) > 0){
            $row = mysqli_fetch_assoc($q);
            echo "El tipo de usuario es: " . $row['tipo'];
            $_SESSION['tipo'] = $row['tipo'];
          }else{
            echo "El email o la contraseña son incorrectos";
          }
        }
      }
      if(isset($_POST['submit']) && $_POST['submit'] == "Cerrar sesión"){
        echo "Sesión cerrada correctamente";
        unset($_SESSION['tipo']);
      }
    }
  }
}
?>