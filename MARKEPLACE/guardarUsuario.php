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

$errores = [];
$idUser = $_POST["id"];
$nombreUser = $_POST["nombre"];
$email = $_POST["email"];
$pwd =  $_POST["pwd"];

//Validacion sobre si mismo
if($idUser==$usuario->idUsuario){
    http_response_code(401);
    die("No se puede modificar a si mismo");
}


if($idUser>0){
    $user = Usuario::carga($idUser);
}else{
    $user = new Usuario();
}
$user->nombre = $nombreUser;
$user->email = $email;
if(isset($_POST["admin"])){
    $user->admin = 1;
}else{
    $user->admin = 0;
}


if ($user->nombre == '') {
    $errores['nombre'] = "Nombre completo requerido";
  
}

if($user->email == ''){
    $errores["email"]="Email requerido, formato correo (correo@correo.com)";
    

}else{
    if(filter_var($user->email, FILTER_VALIDATE_EMAIL)==false){
        $errores["email"]="Email invalida, formato correo (correo@correo.com)";
        
    }
}

if($pwd == ""){
    if($user->idUsuario==0){
        $errores["pwd"]="La contraseña es requerido";
    }
}else{
    $user->pwd=$pwd;
    $user->pwd = password_hash($user->pwd, PASSWORD_DEFAULT);
}



if (count($errores) > 0) {
    $_SESSION['errores'] = $errores;
    $_SESSION['datos'] = $user;
    echo "Hay errores";
    header("Location: mantenimientoUsuario.php");
    die();
}else{
    
    $user->guardar();
    header('Location: usuarios.php');
}




