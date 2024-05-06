<?php
require_once __DIR__ . '/lib/funcion.php';
require_once __DIR__ . '/modelos/usuario.php';
require_once __DIR__ . '/modelos/adquisicion.php';

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
$id = $_POST['idAdquisicion'];

$adquisicion = Adquisicion::cargar($id);
$adquisicion->aprobado=1;
$adquisicion->fechaAprobacion=ObtenerFecha();
$adquisicion->actualizar();

header("Content-Type: application/json");
echo json_encode($adquisicion);

