<?php
const TAM_PAGINA = 3;

function abrirBD() {
    $bd = new mysqli(
        "localhost",   // Servidor
        "localhost",   // Usuario
        "localhost",     // Contraseña
        "localhost"); // Esquema
    if ($bd->connect_errno) {
        die("Error de conexión: " . $bd->connect_error);
    }
    $bd->set_charset("utf8mb4");
    return $bd;
}

