<?php
require_once('html.php');
if (isset($_GET['controller'])) {
  $controller = $_GET['controller'];
} else {
  $controller = 'habitaciones';
}

<<<<<<< HEAD
<<<<<<< HEAD
echo HTMLrenderWeb($controller);
=======
echo HTMLrenderWeb();
>>>>>>> 91a3a1dac8e7506f416327a7ea0ae2925bfaaeaf
=======
echo HTMLrenderWeb($controller);
>>>>>>> alex
?>