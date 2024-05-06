<?php
require_once __DIR__ . '/lib/funcion.php';
require_once __DIR__ . '/modelos/usuario.php';


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
$idUser = $_GET["id"];
//No se si es necesario hacer esta comprobacion
if($usuario->idUsuario == $idUser){
    http_response_code(401);
    die("No se puede borrar a si mismo, siendo el administrador");
}

Usuario::borrar($idUser);
header('Location: usuarios.php');