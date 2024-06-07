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

  public function addUser($data){

    $fields = "(";
    $values = "(";
    foreach ($data as $key => $value) {
      if($value != null){
        $values .= "'$value', ";
      }else{
        $values .= "NULL, ";
      }

      $fields .= "$key, ";
    }
    $fields = substr($fields, 0, -2);
    $fields .= ")";
    $values = substr($values, 0, -2);
    $values .= ")";
    $sql = "INSERT INTO `Usuario` $fields VALUES $values;";
    echo "DEBUG:: query que enviamos para añadir usuario: ".$sql;
    $this->log("Se añade el usuario " .$data['DNI']);
    try {
      $this->db->query($sql);
    }catch (\mysql_xdevapi\Exception $exception){
      $this->log("Exception: ".$exception->getMessage());
    }
  }

  public function addRoom($data){

    $fields = "(";
    $values = "(";
    foreach ($data as $key => $value) {
      if($value != null){
        $values .= "'$value', ";
      }else{
        $values .= "NULL, ";
      }

      $fields .= "$key, ";
    }
    $fields = substr($fields, 0, -2);
    $fields .= ")";
    $values = substr($values, 0, -2);
    $values .= ")";
    $sql = "INSERT INTO `Habitacion` $fields VALUES $values;";
    //echo "DEBUG:: query que enviamos para añadir habitacion: ".$sql;
    $this->log("Se añade el usuario " .$data['id']);
    try {
      $this->db->query($sql);
    }catch (\mysql_xdevapi\Exception $exception){
      $this->log("Exception: ".$exception->getMessage());
    }
  }

  public function addReservation($data){

    $fields = "(";
    $values = "(";
    foreach ($data as $key => $value) {
      if($value != null){
        $values .= "'$value', ";
      }else{
        $values .= "NULL, ";
      }

      $fields .= "$key, ";
    }
    $fields = substr($fields, 0, -2);
    $fields .= ")";
    $values = substr($values, 0, -2);
    $values .= ")";
    $sql = "INSERT INTO `Reserva` $fields VALUES $values;";
    $this->log("Se añade la reserva por parte del usuario " .$data['dni_usuario']." para la habitación ".$data['id_habitacion']);
    try {
      $this->db->query($sql);
    }catch (\mysql_xdevapi\Exception $exception){
      $this->log("Exception: ".$exception->getMessage());
    }
  }

  public function requestLogsAdmin(){
    try {
      return $this->db->query("SELECT * FROM `logs`;");
    }catch (\mysql_xdevapi\Exception $exception){
      $this->log("Exception: ".$exception->getMessage());
    }
  }

  public function requestUser($dni) {

    if($dni === null){
      return null;
    }else{
      try {

        $sql = "SELECT * FROM `Usuario` WHERE DNI='$dni'";
        return $this->db->query($sql);
      }catch (\mysql_xdevapi\Exception $exception){
        $this->log("Exception: ".$exception->getMessage());
      }
    }
  }


  public function requestUserId() {
      $sql = "SELECT DNI FROM `Usuario` WHERE tipo='cliente'";
      return $this->db->query($sql);
  }

  public function requestRoomId() {
    try {
      $sql = "SELECT id FROM `Habitacion`";
      return $this->db->query($sql);
    }catch (\mysql_xdevapi\Exception $exception){
      $this->log("Exception: ".$exception->getMessage());
    }
  }


  public function requestRoom($id) {

    if($id === null){
      return null;
    }else{
      try {
        $sql = "SELECT * FROM `Habitacion` WHERE id='$id'";
        return $this->db->query($sql);
      }catch (\mysql_xdevapi\Exception $exception){
        $this->log("Exception: ".$exception->getMessage());
      }
    }
  }

  public function requestReservation($id) {

    if($id === null){
      return null;
    }else{
      try {

        $sql = "SELECT * FROM `Reserva` WHERE id_reserva='$id'";
        return $this->db->query($sql);
      }catch (\mysql_xdevapi\Exception $exception){
        $this->log("Exception: ".$exception->getMessage());
      }
    }
  }

  public function requestUserList($filtros = null) {
    
    if($filtros == null){
      $sql = "SELECT * FROM `Usuario`";
      return $this->db->query($sql);
    }else{
      $sql = "SELECT * FROM `Usuario` WHERE ";
      $conditions = [];
      
      foreach($filtros as $key) {
          $conditions[] = "tipo = '$key'";
      }
      
      $sql .= implode(' OR ', $conditions);

      return $this->db->query($sql);
    }
  }

  public function requestRoomList($filtros = null) {

    if($filtros == null){
      $sql = "SELECT * FROM `Habitacion`";
      return $this->db->query($sql);
    }else{
      $sql = "SELECT * FROM `Habitacion` WHERE ";
      $conditions = [];

      foreach($filtros as $key) {
        $conditions[] = "estado = '$key'";
      }

      $sql .= implode(' OR ', $conditions);
      return $this->db->query($sql);
    }
  }

  public function requestReservationList($filtros = null) {

    if($filtros == null){
      $sql = "SELECT * FROM `Reserva`";
      return $this->db->query($sql);
    }else{
      $sql = "SELECT * FROM `Reserva` WHERE ";
      $conditions = [];

      foreach($filtros as $key => $value) {
        $conditions[] = "$key = '$value' OR ";
      }

      $conditions = substr($conditions, 0, -4);

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
    $this->log("Se modifica el usuario " .$data['DNI']);
    try {
      $this->db->query($sql);
    }catch (mysqli_sql_exception $e){
      $this->log("Exception: ".$e->getMessage());
    }
  }

  public function updateRoom($data) {
    $fields = "";
    foreach ($data as $key => $value)
      $fields .= "$key = '$value', ";
    $fields = substr($fields, 0, -2);
    $sql = "UPDATE `Habitacion` SET $fields WHERE id='{$data['id']}'";
    $this->log("Se modifica la habitación " .$data['id']);
    try {
      $this->db->query($sql);
    }catch (mysqli_sql_exception $e){
      $this->log("Exception: ".$e->getMessage());
    }
  }

  public function updateReservation($data) {
    $fields = "";
    foreach ($data as $key => $value)
      if($key != 'id_reserva'){
        $fields .= "$key = '$value', ";
      }
    $fields = substr($fields, 0, -2);
    $sql = "UPDATE `Reserva` SET $fields WHERE id_reserva='{$data['id_reserva']}'";
    $this->log("Se modifica la reserva " .$data['id_reserva']);
    try {
      $this->db->query($sql);
    }catch (mysqli_sql_exception $e){
      $this->log("Exception: ".$e->getMessage());
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
      $this->log("Se elimina el usuario " .$dni);
    } catch (mysqli_sql_exception $e) {
      $this->log("Exception: ".$e->getMessage());
    }
  }

  public function deleteRoom($id) {
    try {
      $this->db->query("DELETE FROM Habitacion WHERE id='$id'");
      $this->log("Se elimina la habitación " .$id);
    } catch (mysqli_sql_exception $e) {
      $this->log("Exception: ".$e->getMessage());
    }
  }

  public function deleteReservation($id) {
    try {
      $this->db->query("DELETE FROM Reserva WHERE id_reserva='$id'");
      $this->log("Se elimina la reserva " .$id);
    } catch (mysqli_sql_exception $e) {
      $this->log("Exception: ".$e->getMessage());
    }
  }

  public function login($email, $password){ //IMPORTANTE pasar los elementos del posts saneados en el index
    try{
      $q = $this->db->query("SELECT tipo, nombre, passwd, DNI FROM Usuario WHERE mail = '$email'"); //revisar que en la base de datos email sea unique
      if ($q) {
        $row = mysqli_fetch_assoc($q);
        if(password_verify($password, $row['passwd'])){ //con contraseña cifrada
          $_SESSION['tipo'] = $row['tipo'];
          $_SESSION['nombre'] = $row['nombre'];
          $_SESSION['dni'] = $row['DNI'];
          //inserción en la tabla de logs
          $this->log("Inicio sesión del usuario " . $_SESSION['dni']);
        } else {
          $this->log("Fallo al ingresar clave de " . $row['DNI']);
        }
      } else {
        $this->log("Fallo al iniciar sesión, " . $row['DNI'] . "no reconocido");
      }
      return $q;
    }catch(mysqli_sql_exception $e){
      $this->log("Exception: ".$e->getMessage());
      return $q;
    }
  }

  public function register($nombre, $apellidos, $dni, $email, $nacionalidad, $tipo, $passwd, $foto, $tarjeta){ //IMPORTANTE pasar los elementos del posts saneados en el index

    try{
      $q = $this->db->query("INSERT INTO Usuario (nombre, apellidos, DNI, mail, nacionalidad, tipo, passwd, foto, tarjeta) 
            VALUES ('$nombre', '$apellidos', '$dni', '$email', '$nacionalidad', '$tipo', '$passwd', '$foto', '$tarjeta')");
      if ($q) {
        $_SESSION['tipo'] = $tipo;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['dni'] = $dni;
        //inserción en la tabla de logs
        $this->log("Registro del usuario " . $_SESSION['dni']);
      } else {
        $this->log("No se ha podido registrar el usuario");
      }
      return $q;
    }catch(mysqli_sql_exception $e){
      $this->log("Exception: ".$e->getMessage());
      return $q;
    }
  }

  public function log($comentario){
    $fecha = date('Y-m-d H:i:s');
    $q = $this->db->query("INSERT INTO logs (fecha, accion) VALUES ('$fecha', '$comentario')");
    return $q;
  }

  // Esta función nos sirve tanto para resetear de cero, como para hacerlo a partir de un backup
  public function reset($archivoReseteo){

    $query = file_get_contents($archivoReseteo);
    // Verificar si se pudo leer el archivo
    if ($query === false) {
      die("Error al leer el archivo.");
    }

    try {
      if ($this->db->multi_query($query)) {
        do {
          // Store first result set
          if ($result = $this->db->store_result()) {
            // Free result set
            $result->free();
          }
          // If there are more result-sets, the loop will continue
        } while ($this->db->more_results() && $this->db->next_result());
      }
      $this->log("Reseteo exitoso.");
    } catch (mysqli_sql_exception $e) {
      $this->log("Exception: " . $e->getMessage());
    }
  }

  function crearBackup()
  {
    $backup_file = 'backup_' . date('Ymd_His') . '.sql';

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $backup_file);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

    // Inicializar el contenido del backup
    $backup_content = "";

    // Obtener todas las tablas
    $tables = $this->db->query("SHOW TABLES");
    if ($tables) {
      while ($row = $tables->fetch_row()) {
        $table = $row[0];
        $drop_tables_queries[$table] = "DROP TABLE IF EXISTS `$table`;\n\n";

        $table = $row[0];
        $create_tables_queries[$table] = "";
        // Obtener la estructura de la tabla
        $q = $this->db->query("SHOW CREATE TABLE `$table`");
        if ($q) {
          $create_table_row = $q->fetch_row();// Almacenar la consulta de creación de la tabla
          $create_tables_queries[$table] .= $create_table_row[1] . ";\n\n";
        }

        // Escribir los datos de la tabla
        $result = $this->db->query("SELECT * FROM $table");
        $num_fields = $result->field_count;

        while ($row = $result->fetch_row()) {
          $insert_data_queries[$table][] = "INSERT INTO $table VALUES(";
          for ($i = 0; $i < $num_fields; $i++) {
            if (isset($row[$i])) {
              $row[$i] = $this->db->real_escape_string($row[$i]);
              $insert_data_queries[$table][] .= '"' . $row[$i] . '"';
            } else {
              $insert_data_queries[$table][] .= 'NULL';
            }
            if ($i < ($num_fields - 1)) {
              $insert_data_queries[$table][] .= ',';
            }
          }
          $insert_data_queries[$table][] .= ");\n\n";
        }
      }
    } else {
      die("Error al obtener las tablas: " . $this->db->error);
    }

    // Dropear las tablas en el orden correcto
    if (isset($drop_tables_queries['logs'])) {
      $backup_content .= $drop_tables_queries['logs'];
    }
    if (isset($drop_tables_queries['Reserva'])) {
      $backup_content .= $drop_tables_queries['Reserva'];
    }
    if (isset($drop_tables_queries['Usuario'])) {
      $backup_content .= $drop_tables_queries['Usuario'];
    }
    if (isset($drop_tables_queries['Habitacion'])) {
      $backup_content .= $drop_tables_queries['Habitacion'];
    }

    // Crear las tablas en el orden correcto
    if (isset($create_tables_queries['logs'])) {
      $backup_content .= $create_tables_queries['logs'];
    }
    if (isset($create_tables_queries['Usuario'])) {
      $backup_content .= $create_tables_queries['Usuario'];
    }
    if (isset($create_tables_queries['Habitacion'])) {
      $backup_content .= $create_tables_queries['Habitacion'];
    }
    if (isset($create_tables_queries['Reserva'])) {
      $backup_content .= $create_tables_queries['Reserva'];
    }

    // Insertar los datos en el orden correcto
    if (isset($insert_data_queries['logs'])) {
      foreach ($insert_data_queries['logs'] as $query) {
        $backup_content .= $query;
      }
    }
    if (isset($insert_data_queries['Usuario'])) {
      foreach ($insert_data_queries['Usuario'] as $query) {
        $backup_content .= $query;
      }
    }
    if (isset($insert_data_queries['Habitacion'])) {
      foreach ($insert_data_queries['Habitacion'] as $query) {
        $backup_content .= $query;
      }
    }
    if (isset($insert_data_queries['Reserva'])) {
      foreach ($insert_data_queries['Reserva'] as $query) {
        $backup_content .= $query;
      }
    }

    $this->log("Backup".$backup_content);
    exit;
  }

  function obtenerDatosOcupación(){

    $q = $this->db->query("SELECT COUNT(*) AS hab_tot FROM Habitacion;");
    if ($q) {
      $hab_tot = $q->fetch_assoc();
    }
    $q->free();
    $q = $this->db->query("SELECT COUNT(*) AS hab_lib FROM Habitacion WHERE estado = 'libre'");
    if ($q) {
      $hab_lib = $q->fetch_assoc();
    }
    $q->free();
    $q = $this->db->query("SELECT SUM(capacidad) AS capacidad_tot FROM Habitacion");
    if ($q) {
      $capacidad_tot = $q->fetch_assoc();
    }
    $q = $this->db->query("SELECT COUNT(*) AS ocupacion FROM Reserva");
    if ($q) {
      $ocupacion = $q->fetch_assoc();
    }
    $q->free();
    $ret = array(
        "hab_tot" => $hab_tot['hab_tot'],
        "hab_lib" => $hab_lib['hab_lib'],
        "capacidad_tot" => $capacidad_tot['capacidad_tot'],
        "ocupacion" => $ocupacion['ocupacion']
    );
    return $ret;
  }

  function existsReservation($hab){

    $sql = "SELECT fecha_fin FROM Reserva WHERE id_habitacion='$hab'";
    //var_dump($sql);
    try {
      return $this->db->query($sql);
    } catch (mysqli_sql_exception $e) {
      $this->log("Exception: ".$e->getMessage());
    }
  }
}
?>