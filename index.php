<?php
require_once('html.php');
if (isset($_GET['controller'])) {
  $controller = $_GET['controller'];
} else {
  $controller = 'habitaciones';
}

echo HTMLrenderWeb($controller);
?>