<?php

$errores = array(null, null, null, null, null, null, null);

$todo_correcto = array(false, false, false, false, false, false, false, false, false);

$idiomas = array("es" => "Español", "en" => "Inglés", "de" => "Alemán");

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
        case 'datos':
            $main = datos();
            break;
        case 'reservas':
            $main = reservas();
            break;
        case 'registro':
            $main = registro();
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
        <div class="contenedor-sesion">
            <a href="#" onclick="showPopup()">Iniciar sesión</a>
            <a href="index.php?p=registro">Registro</a>
    HTML;
        
        if($data['tipo'] != "anonimo"){
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

function datos(){
  
  $ret = <<<HTML
  <main class="formulario">
    <form action="procesar.php" method="get">
        
        <section><h2>Datos de usuario</h2>
            <div>
                <div>
                    <label>Nombre: </label><input type="text" name="nombre" size="15" maxlength="20" required placeholder="Campo obligatorio" pattern="^[A-ZÁÉÍÓÚÜÑ][a-záéíóúüñ]*">
                    <label>Apellidos: </label><input type="text" name="apellido" size="30" placeholder="Opcional" pattern="^[A-ZÁÉÍÓÚÜÑ][a-záéíóúüñ]*">               
                </div>
                <div>
                    <label>Clave: </label><input type="password" name="ctr" placeholder="Introduzca su contraseña"> 
                    <label>E-mail: </label><input type="email" name="email" placeholder="Con un formato correcto" required>
                </div>
                
            </div>
                <div>
                    <label>Nacionalidad: </label><input type="text" name="nacionalidad" value="España">
            
                    <label>Sexo:</label>
                    <select name="sexo">
                        <option>Masculino</option>
                        <option>Femenino</option>
                        <option selected>No deseo responder</option>
                    </select>
                </div>
        </section>
    
        <section><h2>Reserva</h2>
            <p>Idioma para comunicaciones:</p>
                <div>
                    <label> <input type="radio" name="idioma" value="Espaniol"> Español </label>
                    <label> <input type="radio" name="idioma" value="Ingles"> Inglés </label>
                    <label> <input type="radio" name="idioma" value="Frances"> Francés </label> 
                    <label> <input type="radio" name="idioma" value="Japones"> Japonés </label>
                </div>
    
            <p>Habitación:</p>
                <div>
                    <label> <input type="checkbox" name="habitacion[]" value="sanrio"> Modelo <em>Sanrio</em> </label>
                    <label> <input type="checkbox" name="habitacion[]" value="tradicional"> Modelo tradicional </label>
                    <label> <input type="checkbox" name="habitacion[]" value="pareja"> Habitación para parejas </label>
                    <label> <input type="checkbox" name="habitacion[]" value="suite"> Habitación suite </label>
                </div>
            <div>
                <div>
                    <label>Fecha nacimiento: <input type="date" name="nacimiento"> </label>
                </div>
                <div>
                    <label>Semana de visita: <input type="week" name="semanaEstancia"> </label>
                </div>
            </div>
        </section>
    
        <p>Tratamiento de datos: <select name="TyC">
            <option value="TOTAL">Acepta el almacenamiento de mis datos y el envío a terceros.</option>
            <option value="PARCIAL">Acepta el almacenamiento de mis datos pero no el envío a terceros.</option>
            <option value="NINGUNO">No acepta el almacenamiento ni el envío de datos a terceros.</option>
        </select></p>
        
        <input type="submit" value="Enviar datos">
    </form>
  </main>
  HTML;
  
  return $ret;
}

function registro(){

    global $todo_correcto;

    $main = HTMLmain();

    $ret = <<<HTML

    
    <main>
            <form action="" method="post" class="registro">

                $main
                
                <section class="agreedments">
                    <p>
                        <label>Tratamiento de datos:
                            <select name="agreeds" id="agreeds">
                                <option value="TOTAL" selected>Acepta el almacenamiento de mis datos y el envío a terceros</option>
                                <option value="PARCIAL">Acepta el almacenamiento de mis datos pero no el envío a terceros</option>
                                <option value="NINGUNO">No acepta el almacenamiento ni el envío de datos a terceros responder</option>
                            </select>
                        </label>
                    </p>
                </section>
                <div class="boton">
                    
    HTML;

    if($todo_correcto[8]){

        $ret .= <<<HTML
            <input type="submit" name='enviar' value="Confirmar datos" class="boton-enviar">
        HTML;
    }else{
        $ret .= <<<HTML
            <input type="submit" name='enviar' value="Enviar datos" class="boton-enviar">
        HTML;
    }

    $ret .= <<< HTML
    
            </div>
        </form>
    </main>
    
    HTML;
    
    return $ret;
}

function HTMLmain(){

    global $todo_correcto;

    popErrores();

    $dp = HTMLdatosPersonales();
    $da = HTMLdatosAcceso();
    $prefs = HTMLpreferencias();

    if($todo_correcto[3] && $todo_correcto[6] && $todo_correcto[7]){
        $todo_correcto[9] = true;
    }

    $ret = $dp . $da . $prefs;

    return $ret;
}

function popErrores(){
    global $errores;
    global $todo_correcto;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){ #Si se ha pulsado el boton de enviar
            
        comprobarNombre();
        comprobarDNI();
        comprobarFecha();

        #Si los tres campos de la seccion de datos personales estan correctos indicamos que esta seccion al completo esta correcta
        if($todo_correcto[0] && $todo_correcto[1] && $todo_correcto[2]){
            $todo_correcto[3] = true;
        }
        
        comprobarMail();
        comprobarPasswd();

        #Si los dos campos de la seccion de datos de acceso estan correctos indicamos que esta seccion al completo esta correcta
        if($todo_correcto[4] && $todo_correcto[5]){
            $todo_correcto[6] = true;
        }

        #Esta funcion calcula internamente si el bloque del idioma esta corrrecto
        comprobarPrefs();

        #En caso de que los tres bloques esten correctos cambiamos lo que habíamos almacenado en la variable global errores 
        #por sentencias HTML que no sean modificables
        if($todo_correcto[3] && $todo_correcto[6] && $todo_correcto[7]){
            $todo_correcto[8] = true;
            todo_correctoHTML();
        }
        
    }else{ #en caso de que se acabe de acceder a la pagina
        defaultHTML();
    }
}

function todo_correctoHTML(){

    global $errores;
    global $idiomas;

    $valor = $_POST['nombre'];
    $errores[0] = <<< HTML
        <input type="text" name="nombre" value="$valor" readonly>
    HTML;

    $valor = $_POST['dni'];
    $errores[1] = <<< HTML
        <input type="text" name="dni" value="$valor" readonly>
    HTML;

    $valor = $_POST['fecha-nacimiento'];
    $errores[2] = <<< HTML
        <input type="date" name="fecha-nacimiento" value="$valor" readonly>
    HTML;

    $valor = $_POST['mail'];
    $errores[3] = <<< HTML
        <input type="email" name='mail' value="$valor" novalidate readonly>
    HTML;

    $valor = $_POST['passwd'];
    $errores[4] = <<< HTML
        <input type="password" value="$valor" name='passwd' readonly>
    HTML;
    $errores[5] = <<< HTML
        <input type="password" value="$valor" name='passwdRepeat' readonly>
    HTML;

    $post = $_POST['language'];
    $errores[6] = <<< HTML
    HTML;
    foreach ($idiomas as $clave => $valor) {
        if($post == $clave){
            $errores[6] .= <<< HTML
                <label><input type="radio" name="language" value="$clave" checked>$valor</label>
            HTML;
        }else{
            $errores[6] .= <<< HTML
                <label><input type="radio" name="language" value="$clave">$valor</label>
            HTML;
        }
    }
}

function defaultHTML(){

    global $errores;
    global $idiomas;

    $errores[0] = <<< HTML
        <input type="text" name="nombre">
    HTML;
    $errores[1] = <<< HTML
        <input type="text" name="dni">
    HTML;
    $errores[2] = <<< HTML
        <input type="date" name="fecha-nacimiento">
    HTML;
    $errores[3] = <<< HTML
    <input type="email" id="mail" name='mail' novalidate>
    HTML;
    $errores[4] = <<< HTML
    <input type="password" placeholder="Escriba la clave" name='passwd'>
    HTML;
    $errores[5] = <<< HTML
    <input type="password" placeholder="Escriba la misma clave" name='passwdRepeat'>
    HTML;
    foreach ($idiomas as $clave => $valor) {
        $errores[6] .= <<< HTML
        <label><input type="radio" name="language" value="$clave">$valor</label>
        HTML;
    }
}

function comprobarNombre(){

    global $errores;
    global $todo_correcto;

    if(empty($_POST['nombre'])){
        $errores[0] = <<< HTML
            <input type="text" name="nombre">
            <p class="errorNombre">Error en el nombre</p>
        HTML;
    }else{
        $valor = $_POST['nombre'];
        $errores[0] = <<< HTML
            <input type="text" name="nombre" value="$valor">
        HTML;
        $todo_correcto[0] = true;
    }
}

function comprobarDNI(){

    global $errores;
    global $todo_correcto;

    if(empty($_POST['dni'])){
        $errores[1] = <<< HTML
        <input type="text" name="dni">
        <p class="errorDNI">Error en el DNI</p>
        HTML;
    }else{
        $valor = $_POST['dni'];
        if(!esDniValido($valor)){
            $errores[1] = <<< HTML
            <input type="text" name="dni">
            <p class="errorDNI">El DNI no es válido</p>
            HTML;
        }else{
            $errores[1] = <<< HTML
            <input type="text" name="dni" value="$valor">
            HTML;
            $todo_correcto[1] = true;
        }   
    }
}

function comprobarFecha(){

    global $errores;
    global $todo_correcto;

    if(empty($_POST['fecha-nacimiento'])){
        $errores[2] = <<< HTML
        <input type="date" name="fecha-nacimiento">
        <p class="errorEdad">Debe rellenar el campo</p>
        HTML;
    }else{

        $fecha = $_POST['fecha-nacimiento'];
        if(!esMayorDeEdad($fecha)){
            $errores[2] = <<< HTML
            <input type="date" name="fecha-nacimiento">
            <p class="errorEdad">Debe ser mayor de edad</p>
            HTML;
        }else{
            $errores[2] = <<< HTML
            <input type="date" name="fecha-nacimiento" value="$fecha">
            HTML;
            $todo_correcto[2] = true;
        }
    }
}

function comprobarMail(){

    global $errores;
    global $todo_correcto;

    #Aunque ponga novalidate el navegador lo comprueba igual
    if(empty($_POST['mail'])){
        $errores[3] = <<< HTML
        <input type="email" id="mail" name='mail' novalidate>
        <p class="errorMail">Error en el mail</p>
        HTML;
        
    }else {
        if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){
            $errores[3] = <<< HTML
            <input type="email" id="mail" name='mail' novalidate>
            <p class="errorMail">El email no tiene un formato correcto</p>
            HTML;
        }else{
            $valor = $_POST['mail'];
            $errores[3] = <<< HTML
            <input type="email" name='mail' value="$valor" novalidate>
            HTML;
            $todo_correcto[4] = true;
        }
    }
}

function comprobarPasswd(){

    global $errores;
    global $todo_correcto;

    if(empty($_POST['passwd'])){
    
        $errores[4] = <<< HTML
        <input type="password" placeholder="Escriba la clave" name='passwd'>
        <p class="errorPasswd">Rellene la contraseña</p>
        HTML;
        $errores[5] = <<< HTML
        <input type="password" placeholder="Escriba la misma clave" name='passwdRepeat'>
        HTML;
    }else{
        if($_POST['passwd'] != $_POST['passwdRepeat']){
            $errores[4] = <<< HTML
            <input type="password" placeholder="Escriba la clave" name='passwd'>
            <p class="errorPasswd">Las contraseñas con coinciden</p>
            HTML;
            $errores[5] = <<< HTML
            <input type="password" placeholder="Escriba la misma clave" name='passwdRepeat'>
            HTML;
        }else{
            $valor = $_POST['passwd'];
            $errores[4] = <<< HTML
            <input type="password" value="$valor" name='passwd'>
            HTML;
            $errores[5] = <<< HTML
            <input type="password" value="$valor" name='passwdRepeat'>
            HTML;
            $todo_correcto[5] = true;
        }
    }
}

function comprobarPrefs(){

    global $idiomas;
    global $errores;
    global $todo_correcto;

    if(!isset($_POST['language'])){
        foreach ($idiomas as $clave => $valor) {
            
            $errores[6] .= <<< HTML
            <label><input type="radio" name="language" value="$clave">$valor</label>
            HTML;
        }
        $errores[6] .= <<< HTML
        <p class="errorPrefs">Error en el idioma</p>
        HTML;
    }else{
        $post = $_POST['language'];
        $errores[6] = <<< HTML
        HTML;
        foreach ($idiomas as $clave => $valor) {
            if($post == $clave){
                $errores[6] .= <<< HTML
                <label><input type="radio" name="language" value="$clave" checked>$valor</label>
                HTML;
            }else{
                $errores[6] .= <<< HTML
                <label><input type="radio" name="language" value="$clave">$valor</label>
                HTML;
            }
        }
        $todo_correcto[7] = true;
    }
}

function esDniValido($dni) {

    if (!preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
        return false;
    }

    $numero = substr($dni, 0, 8);
    $letra = substr($dni, -1);

    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';

    $letraCalculada = $letras[intval($numero) % 23];

    return $letraCalculada === $letra;
}

function esMayorDeEdad($fechaNacimiento) {

    $ret = false;

    $fechaActual = new DateTime();
    $fechaNacimiento = new DateTime($fechaNacimiento); 
    $diferencia = $fechaActual->diff($fechaNacimiento);

    if ($diferencia->y >= 18) {
        $ret = true;
    }

    return $ret;
}

function HTMLdatosPersonales() {
    global $errores;

    $ret = <<<HTML
        <section class="pers-cont">
            <h2 class="registro">Datos personales</h2>
            <div class="pers-cont">
                <div class="upper-cont">
                    <div id="nombre-apellidos">
                        <div id="nombre">
                            <div class="uno"><label>Nombre:</label></div>
                            <div class="dos">
    HTML;
    if (isset($errores[0])) {
        $ret .= $errores[0];
    }
    $ret .= <<<HTML
                            </div>
                        </div>
                        <div id="apellidos">
                            <div class="uno"><label>Apellidos:</label></div>
                            <div class="dos"><input type="text" name="apellidos"></div>
                        </div>
                    </div>
                    <div id="foto">
                        <label>Fotografía: <p><input type="file" name="fotografia"></p></label>
                    </div>
                </div>
                <div class="lower-cont">
                    <div class="left-cont">
                        <div id="dni">
                            <div class="uno"><label>DNI:</label></div>
                            <div class="dos">
    HTML;
    if (isset($errores[1])) {
        $ret .= $errores[1];
    }
    $ret .= <<<HTML
                            </div>
                        </div>
                        <div id="fnac">
                            <div class="uno"><label>F. nacimiento:</label></div>
                            <div class="dos">
    HTML;
    if (isset($errores[2])) {
        $ret .= $errores[2];
    }
    $ret .= <<<HTML
                            </div>
                        </div>
                    </div>
                    <div class="right-cont">
                        <div id="etiquetas">
                            <p><label>Nacionalidad:</label></p>
                            <p><label>Sexo:</label></p>
                        </div>
                        <div id="entradas">
                            <p><input type="text" value="España"></p>
                            <p><select name="genero" id="genero">
                                <option value="MASC">Masculino</option>
                                <option value="FEM">Femenino</option>
                                <option selected value="N/S">No deseo responder</option>
                            </select></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    HTML;

    return $ret;
}


function HTMLdatosAcceso() {

    global $errores;

    $ret = <<<HTML
    <section class="access">
        <h2 class="registro">Datos de acceso</h2>
        <div class="access">
            <div class="upper-cont">
                <div class="uno"><label>Email:</label></div>
                <div class="dos">
HTML;
    if (isset($errores[3])) {
        $ret .= $errores[3];
    }
    $ret .= <<<HTML
                </div>
            </div>
            <div class="lower-cont">
                <div class="uno">
                    <div class="uno-arriba"><label>Clave:</label></div>
                    <div class="uno-abajo">
HTML;
    if (isset($errores[4])) {
        $ret .= $errores[4];
    }
    $ret .= <<<HTML
                    </div>
                </div>
                <div class="dos">
                    <div class="dos-arriba"><label>Repita la clave:</label></div>
                    <div class="dos-abajo">
    HTML;
    if (isset($errores[5])) {
        $ret .= $errores[5];
    }
    $ret .= <<<HTML
                    </div>
                </div>
            </div>
        </div>
    </section>
HTML;

    return $ret;
}

function HTMLpreferencias() {

    global $errores;

    $ret = <<<HTML
    <section class="prefs">
        <h2 class="registro">Preferencias</h2>
        <div class="prefs">
            <div class="left-cont">
                <label>Idioma para comunicaciones:</label>
HTML;
        
    if (isset($errores[6])) {
        $ret .= $errores[6];
    }
    $ret .= <<<HTML
            </div>
            <div class="right-cont">
                <label>Preferencias de habitación:</label>
                <label><input type="checkbox" name="preference[]" value="smoking">Para fumadores</label>
                <label><input type="checkbox" name="preference[]" value="pets">Que permita mascotas</label>
                <label><input type="checkbox" name="preference[]" value="view">Con vistas</label>
                <label><input type="checkbox" name="preference[]" value="carpet">Con moqueta</label>
            </div>
        </div>
    </section>
HTML;

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