<?php 
require_once __DIR__."/modelos/usuario.php";
require_once __DIR__."/modelos/adquisicion.php";
require_once __DIR__."/lib/funcion.php";
session_start();
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}else {
    header('Location: login.php');
    die();
    $usuario = null;
}

$adquisicion = new Adquisicion();
$adquisicion->idUsuario=$usuario->idUsuario;
$adquisicion->idContenido=$_POST['idContenido'];


$adquisicion->fechaPedido=ObtenerFecha();
$adquisicion->insertar();
header("Content-Type: application/json");
echo json_encode($adquisicion);