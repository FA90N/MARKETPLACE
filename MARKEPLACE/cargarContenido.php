<?php
require_once __DIR__.'/modelos/contenido.php';

session_start();
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}else {
    header('Location: login.php');
    die();
    $usuario = null;
}

$id= $_GET["id"];

$contenido= Contenido::obtenerContenido($id);

header("Content-Type: application/json");
echo json_encode($contenido);