<?php
require_once __DIR__.'/modelos/usuario.php';

session_start();
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}else {
    header('Location: login.php');
    die();
    $usuario = null;
}

$admin = $usuario->admin;

if (!$admin) {
    http_response_code(403);
    die("Forbidden");
}

$id = $_GET['id'];
$usuario = Usuario::carga($id);
header("Content-Type: application/json");
echo json_encode($usuario);