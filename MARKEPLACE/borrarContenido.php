<?php
require_once __DIR__ . '/lib/funcion.php';
require_once __DIR__ . '/modelos/usuario.php';
require_once __DIR__ . '/modelos/contenido.php';

session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header('Location: login.php');
    die();
    $usuario = null;
}

$admin = $usuario->admin;
if (!$admin) {
    http_response_code(403);
    die("Forbidden");
}

$id = $_GET["id"];
$contenido=Contenido::obtenerContenido($id);
Contenido::borrar($id);
unlink($contenido->getRutaFichero());
unlink($contenido->getRutaFoto());
header("Location: contenidos.php");