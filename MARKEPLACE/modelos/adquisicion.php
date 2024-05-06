<?php
require_once __DIR__.'/bd.php';

class Adquisicion{

    public $idAdquisicion=0;
    public $idUsuario;
    public $idContenido;
    public $fechaPedido;
    public $fechaAprobacion;
    public $aprobado=0;
    const TAM_PAGINA = 4;


    public function insertar()
    {
        $bd = abrirBD();
        $st = $bd->prepare(
            'INSERT INTO Adquisiciones (idUsuario,idContenido,fechaPedido,aprobado) VALUES (?,?,?,?)'
        );
        if ($st === FALSE) {
            die('ERROR SQL: ' . $bd->error);
        }
        $st->bind_param(
            'iisi',
            $this->idUsuario,
            $this->idContenido,
            $this->fechaPedido,
            $this->aprobado
        );
        if ($st->execute() === FALSE) {
            die('ERROR BD: ' . $bd->error);
        }
        $this->idAdquisicion = $st->insert_id;
        $st->close();
        $bd->close();
    }

    public function actualizar()
    {
        $bd = abrirBD();
        $st = $bd->prepare(
            "UPDATE Adquisiciones set fechaAprobacion=?,aprobado=? WHERE idAdquisicion=?"
        );
        if ($st === FALSE) {
            die('ERROR SQL: ' . $bd->error);
        }
        $st->bind_param(
            'sii',
            $this->fechaAprobacion,
            $this->aprobado,
            $this->idAdquisicion,
        );
        if ($st->execute() === FALSE) {
            die('ERROR BD: ' . $bd->error);
        }
        $st->close();
        $bd->close();
    }



    public static function comprobar($idUser, $idCont){

        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM adquisiciones where idUsuario=? and idContenido=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("ii", $idUser, $idCont);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $adquisicion=$res->fetch_assoc();
        $res->free();
        $st->close();
        $bd->close();
        return $adquisicion;
    } 

    public static function listadoAdquisicion($idUsuario,$pag){
        $tamPagina = Adquisicion::TAM_PAGINA;
        $offset = ($pag - 1) * $tamPagina;
        $bd=abrirBD();
        $st = $bd->prepare("SELECT a.*, c.titulo,c.imagen from adquisiciones a 
                inner join contenidos c on a.idContenido=c.idContenido where idUsuario=? order by fechaPedido desc Limit ?,?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("iii", $idUsuario,$offset, $tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $listado = [];
        while ($lista = $res->fetch_assoc()) {
            $listado[] = $lista;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $listado;
    }

    public static function listado($pag){
        $tamPagina = Adquisicion::TAM_PAGINA;
        $offset = ($pag - 1) * $tamPagina;
        $bd=abrirBD();
        $st = $bd->prepare("SELECT a.* ,u.nombre, c.titulo from adquisiciones a 
            left join usuarios u on a.idUsuario=u.idUsuario inner join contenidos c 
            on a.idContenido=c.idContenido order by a.fechaPedido desc Limit ?,?");

        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("ii",$offset, $tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $listado = [];
        while ($lista = $res->fetch_assoc()) {
            $listado[] = $lista;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $listado;
    }

    public static function cuentaAdquisicion($idUsuario)
    {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT COUNT(*) as num FROM Adquisiciones where idUsuario=?");
        if ($st === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $st->bind_param("i", $idUsuario);
        $ok = $st->execute();
        if ($ok === false) {
            die("ERROR" . $bd->error);
        }
        $res = $st->get_result();
        $datos = $res->fetch_assoc();
        $res->free();
        $st->close();
        $bd->close();
        return $datos["num"];
    }

    public static function cuentaAdqui()
    {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT COUNT(*) as num FROM Adquisiciones");
        if ($st === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $ok = $st->execute();
        if ($ok === false) {
            die("ERROR" . $bd->error);
        }
        $res = $st->get_result();
        $datos = $res->fetch_assoc();
        $res->free();
        $st->close();
        $bd->close();
        return $datos["num"];
    }

    public static function cargar($id){

        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM Adquisiciones where idAdquisicion=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("i", $id);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $aquisicion = $res->fetch_object('Adquisicion');
        $res->free();
        $st->close();
        $bd->close();
        return $aquisicion;
        
    }

    public static function borrar($id){
        $bd = abrirBD();
        $st = $bd->prepare("DELETE from Adquisiciones where idAdquisicion=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("i", $id);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }

        $st->close();
        $bd->close();
    }





}