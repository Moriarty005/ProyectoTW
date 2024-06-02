<?php
// Esta función crea la web completa a partir de los datos que recibe en $data
// Es la única que debe utilizarse desde otras partes de la aplicación
function HTMLrenderWeb($data) {

  if($data['controlador'] == null){
    $main = bienvenida();
  }else{ //esto con un switch es más chulo
    if($data['controlador'] == 'bienvenida'){
      $main = bienvenida();
    }else if($data['controlador'] == 'habitaciones'){
      $main = habitaciones();
    }else if($data['controlador'] == 'servicios'){
      $main = servicios();
    }else if($data['controlador'] == 'datos'){
      $main = datos();
    }else if($data['controlador'] == 'reservas'){
      $main = reservas();
    }else if($data['controlador'] == 'error'){
      $main = error();
    }
  }

  return <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hotel O</title>
        <link rel="stylesheet" href="estilo.css" >

    </head>
    <body>
        <header>
            <img src="./img/icono.png" alt="Icon">
            <h1>HOTEL O</h1>
            <img src="./img/icono.png" alt="Icon">
        </header>
        <nav>
            <a href="index.php?p=bienvenida">Bienvenida</a>
            <a href="index.php?p=habitaciones">Habitaciones</a>
            <a href="index.php?p=servicios">Nuestros servicios</a>
            <a href="index.php?p=datos">Introduzca sus datos</a>
            <a href="index.php?p=reservas">Consultar reservas(para recepcionistas)</a>
        </nav>
        $main
        <footer>
            <p>Tel:957333333 Correo:hotelo@o.com Cabra,Córdoba(España)Av/Góngora</p>
            <a href="documentacion.php">Documentación</a>
        </footer>
    </body>
    </html>
    HTML;
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
}

function hihi(){

  $ret = "hello";

  return null;
}

function hihi2(){

  return null;
}

?>