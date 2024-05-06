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
if (!$usuario->admin) {
    http_response_code(403);
    die("Forbidden");
}

$email = $_POST['email'];
$id = $_POST['idUsuario'];
$user = Usuario::cargaLogin($email);
if ($user && $user->idUsuario != $id) {
    echo "DUPLICADO";
}
else {
    echo "OK";
}
