<?php
require_once __DIR__ . '/lib/funcion.php';
require_once __DIR__ . '/modelos/usuario.php';
require_once __DIR__ . '/modelos/contenido.php';
require_once __DIR__ . '/modelos/adquisicion.php';

session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header('Location: login.php');
    die();
    $usuario = null;
}
$idCont = $_GET["id"];
if ($usuario->admin != 1) {
    $adquisicion = Adquisicion::comprobar($usuario->idUsuario, $idCont);
    if (isset($adquisicion["idAdquisicion"])) {
        if ($usuario->idUsuario != $adquisicion["idUsuario"] || $adquisicion["aprobado"] == 0) {
            http_response_code(403);
            die("No tiene acceso al contenido");
        }
    } else {
        http_response_code(401);
        die("No estas autorizado ");
    }
}

$contenido = Contenido::obtenerContenido($idCont);
if (isset($contenido->idContenido)) {
    $ruta = $contenido->getRutaFichero();
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=" . $contenido->fichero);
    readfile($ruta);
}else{
    http_response_code(404);
    die("Fichero no encontrado");
}
