<?php
require_once __DIR__ . '/lib/funcion.php';
require_once __DIR__ . '/modelos/usuario.php';
require_once __DIR__ . '/modelos/contenido.php';
require_once __DIR__ . '/modelos/categoria.php';

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
$errores = [];
$idContenido= $_POST["id"];

$titulo = $_POST["titulo"];
$autor = $_POST["autor"];
$descripcion = $_POST["descripcion"];
$categoria = $_POST["tipo"];

$foto = $_FILES["foto"];
$fichero = $_FILES["fichero"];

if($idContenido>0){
    $contenido = Contenido::obtenerContenido($idContenido);
}else{
    $contenido=new Contenido();
}
$contenido->titulo= $titulo;
$contenido->autor = $autor;
$contenido->descripcion = $descripcion;
$contenido->idTipoContenido = $categoria;

if ($contenido->titulo == '') {
    $errores['titulo'] = "Titulo es requerido";
  
}
if ($contenido->autor == '') {
    $errores['autor'] = "Autor es requerido";
  
}
if ($contenido->descripcion == '') {
    $errores['descripcion'] = "Descripcion es requerido";
}

//Validacion de fotos
if($foto["error"] == UPLOAD_ERR_NO_FILE){
    if($contenido->idContenido==0)
        $errores["foto"] = "Foto requerido";
    
}elseif($foto['error'] == UPLOAD_ERR_OK){

    if (str_starts_with($foto['type'], 'image/')) {
        $oldFoto = $contenido->imagen;
        $contenido->imagen=$foto["name"];
       
    }else {
        $errores['foto'] = 'La foto no es una imagen válida';
    }  

}else{
    $errores["foto"] = "Ha habido error subiendo foto";
}


if($fichero["error"]==UPLOAD_ERR_NO_FILE){
   

    if($contenido->idContenido==0)
        $errores["fichero"] = "Fichero requerido";
    
}elseif($fichero['error'] == UPLOAD_ERR_OK){
    $oldFichero = $contenido->fichero;
    $contenido->fichero=$fichero["name"];

}else{
    $errores["fichero"] = "Ha habido error subiendo fichero";
}

if (count($errores) > 0) {
    $_SESSION['errores'] = $errores;
    $_SESSION['datos'] = $contenido;
    header("Location: mantenimientoContenido.php");
    die();
}else{
    

    $contenido->guardar();
    if($fichero['error'] == UPLOAD_ERR_OK){
        unlink("ficheros/".$contenido->idContenido."_".$oldFichero);
        $destino = 'ficheros/' .$contenido->idContenido."_".$fichero["name"];
        move_uploaded_file($fichero['tmp_name'], $destino);    
    }

    if($foto['error'] == UPLOAD_ERR_OK){
        unlink("fotos/".$contenido->idContenido."_".$oldFoto);
        $destino = 'fotos/' .$contenido->idContenido."_".$foto["name"];
        move_uploaded_file($foto['tmp_name'], $destino);    
    }
    header("Location: contenidos.php");
}
