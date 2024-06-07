<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentación página hotel</title>
</head>
<body>
    <h1>Documentación</h1>
        <p>Proyecto realizado por Alejandro Muñoz Gutiérrez y Álvaro González Luque.</p>
        <p>La práctica se puede encontrar tanto en el servidor SFTP conjunto (alejandroalvaro2324@void.ugr.es) como en el servidor SFTP personal de Alejandro</p>
        <p>Remarcar que la base de datos que se ha utilizado ha sido la proporcionada por el profesor para ambos estudiantes</p>

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
        <a href="./mockups/mockups.php">Acceder a los mockups</a>
    <h2>La base de datos</h2>
        <p>Modelo E-R:</p>
        <img src="./img/e-r.png">
        <ul>
            <li>Usuario: entidad usuario, se identifica por su email y su dni (únicos), su tipo puede ser cliente, recepcionista o administrador (el anónimo no tiene tipo).</li>
            <li>Habitacion: entidad habitación, se identifica por su id, debe tener una capacidad, número de fotos, estado y descripción.</li>
            <li>Reserva: relación entre usuario y habitación, identificada por un id, con clave extranjera dni del usuario e id de la habitación.</li>
        </ul>
    <h2>Tiempo límite reserva</h2>
        <p>Sin aplicar. Base de datos adecuada, pero lógica sin implementar.</p>
    <h2>Uso de javascript</h2>
        <p>Para desplegar el diálogo de inicio de sesión como un pop-up lo hemos realizado mediante funciones javascript.</p>
    <h2>Usuarios</h2>
        <p>Tenemos implementado CRUD con todo tipo de usuarios si se es un admin, sólo con clientes si se es un recepcionista, y que el usuario pueda modificar su información más relevante
        en el sistema.</p>
        <p>Además un cliente tendrá su propia pestaña de información donde podrá realizar los cambios ante smencionados y otra pestaña donde podrá reservar habitaciones</p>
    <h2>Habitaciones.</h2>
        <p>Se ha implementado CRUD para las habitaciones sólo si se es recepcionista.</p>
    <h2>Reservas</h2>
        <p>En cuanto a las reservas un recepcionista pueder hacer operaciones CRUD sobre todas las reservas registradas en el sistema, mientras que un cliente sólo podrá crearlas desde
        la pestaña correspondiente, mencionada en el apartado de Usuarios.</p>
        <p>No hemos conseguido hacer un sistema de reservas totalmente funcional. No se filtra en base a las fechas de las reservas y no hemos conseguido implementar la funcionalidad
        de que se mantenga un tiempo de espera de 30 segundos antes de que de una reserva como finalizada</p>
    <h2>Administrar BD</h2>
        <p>Cada ventana de vista y edición de alguna tabla en la base de datos ha sido adecuada a cada usuario permitiéndoles realizar
            las tareas que tengan permitido (anónimo registra clientes, pero administrador puede registrar otros administradores, recepcionistas,
            ,etc., recepcionista puede modificar otras reservas pero cliente solo la suya, solo administrador puede ver la tabla de logs.</p>
    <h2>Mantenimiento de la BD (control de backups y log)</h2>
        <p>Backups: los backups se descargan y pueden ser subidos directamente mediante para restablecerla.
            Todos los cambios en la base de datos se reflejan en la tabla logs.</p>
    <h2>Saneado y seguridad</h2>
        <p>Se comprueba que los datos entrantes en formularios se ejecuten de manera limpia y evitando inyección sql.
            Además, se cifran las contraseñas al introducirlas en la base de datos y se comprueba que sea posible verificarse con ellas.</p>
    <h2>Maquetado</h2>
        <p>El tema del hotel es la cultura japonesa, por lo que se han empleado colores representativos del país y la figura de la espiral.</p>
</body>
</html>