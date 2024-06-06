<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentación</title>
</head>
<body>
    <h1>Documentación</h1>
    <p> Proyecto por: Alejandro Muñoz Gutiérrez y Álvaro González Luque.</p>
    <h2>Estructura del código</h2>
    <p>Hemos procurado mantener la esctructura mvc y modularizar las funciones y contenidos del sitio web.
        En concreto hemos diferenciado entre:
            <ul>
                <li>html.php: Contiene las funciones que generan el contenido html de las páginas</li>
                <li>conexion.php: Contiene las funciones que gestionan la conexión con la base de datos</li>
                <li>index.php: Contiene el código php que gestiona las peticiones del usuario y llama a las funciones de html.php y conexion.php</li>
                ...
            </ul>
    </p>
    <h2>Usuarios por defecto en la BD</h2>
    <ul>
        <li>Nombre: recepcionista, Email: recepcionista@recepcionista.com, Contraseña: recepcionista, Tipo: recepcionista</li>
        <li>Nombre: admin, Email: admin@admin.com, Contraseña: admin, Tipo: admin</li>
        <li>Nombre: cliente, Email: cliente@cliente.com, Contraseña: cliente, Tipo: cliente</li>
    </ul>
    <h2>La base de datos</h2>
    <p>Está compuesta por las siguientes tablas:
        <ul>
            <li>Usuario: Contiene los datos de los usuarios</li>
            <li>Habitacion: Contiene los datos de las habitaciones</li>
            <li>Reserva: Contiene los datos de las reservas</li>
            <li>...</li>
        </ul>
    </p>
    <em>Debe incluir en la entrega un único fichero en formato pdf que incluya, al menos, los siguientes
elementos:
• Identificación de el/los alumno/s que ha/n realizado la práctica.
• Nombre de usuario y clave de los usuarios registrados en la aplicación web para poder
probarla (indicando para cada uno el usuario, la clave y el rol).
• Nombre del fichero de restauración de la BBDD con datos de prueba.
• Diseño previo de la aplicación (mockups, wireframe, etc)
• Documentación sobre la BBDD (ver subsección anterior).
• Explicaciones técnicas que considere relevantes para la evaluación de la práctica o que
quiera poner en valor por algún motivo.
• Listado de los items opcionales que haya incluido en su proyecto, en particular sobre
JavaScript y AJAX.
Además, el código desarrollado (HTML, CSS, PHP, JavaScript) debe estar convenientemente
comentado.
En el pie de página del sitio web incluirá un enlace a este fichero de documentación</em>

</body>
</html>