<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentación página hotel</title>
</head>
<body>
    <h1>Documentación</h1>
    <p>Proyecto realizado por Alejandro Muñoz Gutiérrez y Álvaro González Luque </p>

    <h2>Datos de prueba</h2>
    <ul>
        <li>Administrador: email( admin@admin.com ) contraseña( admin )</li>
        <li>Recepcionista: email( recepcionista@recepcionista.com ) contraseña( recepcionista )</li>
        <li>Cliente: email( cliente@cliente.com ) contraseña( cliente )</li>
    </ul>
    <h2>Fichero de reseteo</h2>
    <p>Las sentencias de reseteo de la base de datos se encuentran en el archivo sentenciaReseteo.txt</p>
    <a href="sentenciaReseteo.txt">Pulsa aquí para aceder al txt.</a>
    <h2>Mockups</h2>
    <img src="">
    <h2>La base de datos</h2>
    <p>Modelo E-R:</p>
    <img src="./img/e-r.png">
    <ul>
        <li>Usuario: entidad usuario, se identifica por su email y su dni (únicos), su tipo puede ser cliente, recepcionista o administrador (el anónimo no tiene tipo).</li>
        <li>Habitacion: entidad habitación, se identifica por su id, debe tener una capacidad, número de fotos, estado y descripción.</li>
        <li>Reserva: relación entre usuario y habitación, identificada por un id, con clave extranjera dni del usuario e id de la habitación.</li>
    </ul>
    <h2>Tiempo límite reserva</h2>
    <p>Todavía sin aplicar</p>
    <p>Por defecto 30 segundos, se edita en la constante ...</p>
    <h2>Uso de javascript</h2>
    <p>Para desplegar el diálogo de inicio de sesión como un pop-up lo hemos realizado mediante funciones javascript.</p>
</body>
</html>