<?php
require_once __DIR__.'/bd.php';

class Categoria{
    public $idCategoria;
    public $nombre;

    public static function carga($idCategoria){
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM tipoContenido where idTipoContenido=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("i", $idCategoria);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $categoria= $res->fetch_object("Categoria");
        $bd->close();
        $st->close();
        $res->close();
        return $categoria;
    }


    public static function listado() {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM tipocontenido order by nombre");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $categorias = [];
        while ($categoria = $res->fetch_assoc()) {
            $categorias[] = $categoria;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $categorias;

    }

}