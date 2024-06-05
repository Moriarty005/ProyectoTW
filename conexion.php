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

  public function login(){ //IMPORTANTE pasar los elementos del posts saneados en el index
    //Iniciar sesión (comprobar que la sesión no está iniciada en el momento)
    if(empty($_POST['email']) || empty($_POST['password'])){
      echo "No se han introducido todos los datos";
    }else{
      $email = $_POST['email'];
      $password = $_POST['password']; //IMPORTANTE al cifrar la contraseña, se debe cifrar también aquí
      $q = $this->db->query("SELECT tipo FROM Usuario WHERE mail = '$email' AND passwd = '$password'");
      if($q){
        $row = mysqli_fetch_assoc($q);
        $_SESSION['tipo'] = $row['tipo'];
      }else{
        echo "El email o la contraseña son incorrectos";
      }
    }
    return $q;
  }

  public function register(){ //IMPORTANTE pasar los elementos del posts saneados en el index
    $nombre = strip_tags($_POST['nombre']);
    //apellido no es obligatorio, por lo que tenemos que revisar que exista
    if(isset($_POST['apellidos'])){
        $apellidos = strip_tags($_POST['apellidos']);
    }else{
        $apellidos = '';
    }
    $dni = strip_tags($_POST['dni']); //en vez de strip_tags, en mysql debemos usar esta función para controlar caracteres especiales
    $mail = strip_tags($_POST['mail']);
    $nacionalidad = strip_tags($_POST['nacionalidad']);
    if(isset($_POST['tipo'])){
      $tipo = strip_tags($_POST['tipo']);
    }else{
      $tipo = 'cliente';
    }
    $passwd = strip_tags($_POST['passwd']);//IMPORTANTE password_hash($_POST['ctr'],PASSWORD_DEFAULT)
    if(isset($_POST['foto'])){
      $foto = $_POST['foto'];
    }else{
      $foto = '1'; //porque es un int
    }
    $tarjeta = "1234";  //IMPORTANTE: poner campo tarjeta, cifrar tarjeta
    
    $q = $this->db->query("INSERT INTO Usuario (nombre, apellidos, DNI, mail, nacionalidad, tipo, passwd, foto, tarjeta) VALUES ('$nombre', '$apellidos', '$dni', '$mail', '$nacionalidad', '$tipo', '$passwd', '$foto', '$tarjeta')");        
    return $q;
  }
}
?>