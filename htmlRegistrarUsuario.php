<?php

$errores = array(null, null, null, null, null, null, null);

$todo_correcto = array(false, false, false, false, false, false, false, false, false);

$idiomas = array("es" => "Español", "en" => "Inglés", "de" => "Alemán");


function registro($tipo_usuario){

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
    HTML;

    if($tipo_usuario == "admin"){
        $ret .= <<<HTML
            <section class="privilegios">
                <p>
                    <label>Privilegios del usuario:
                        <select name="privileges" id="privileges">
                            <option value="cliente">Cliente</option>
                            <option value="recepcionista">Recepcionista</option>
                            <option value="admin" selected>Administrador</option>
                        </select>
                    </label>
                </p>
            </section>
        HTML;
    }

    if($todo_correcto[8]){

        $ret .= <<<HTML
            <div class="boton">
            <input type="submit" name='enviar' value="Confirmar datos" class="boton-enviar">
        HTML;
    }else{
        $ret .= <<<HTML
            <div class="boton">
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
?>