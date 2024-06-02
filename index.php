<?php
require_once('html.php');
if (isset($_GET['controller'])) {
  $controller = $_GET['controller'];
} else {
  $controller = 'cindex';
}

if($controller)

echo HTMLrenderWeb();
?>