<?php
require_once __DIR__.'/bd.php';

class Contenido {

    public $idContenido=0;
    public $titulo;
    public $autor;
    public $descripcion;
    public $imagen;
    public $fichero;
    public $idTipoContenido;
    const TAM_PAGINA = 5;

    public function actualizar(){

        $bd = abrirBD();
        $st = $bd->prepare("UPDATE contenidos set 
                titulo=?,autor=?,descripcion=?,imagen=?,fichero=?,idTipoContenido=? where idContenido=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("sssssii", 
                $this->titulo, 
                $this->autor, 
                $this->descripcion,
                $this->imagen,
                $this->fichero,
                $this->idTipoContenido,
                $this->idContenido);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();

    }

    public function insertar(){
        $bd = abrirBD();
        $st = $bd->prepare("INSERT INTO contenidos
                (titulo,autor,descripcion,imagen,fichero,idTipoContenido) 
                VALUES (?,?,?,?,?,?)");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("sssssi", 
                $this->titulo, 
                $this->autor, 
                $this->descripcion,
                $this->imagen,
                $this->fichero,
                $this->idTipoContenido);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $this->idContenido = $bd->insert_id;
        
        $st->close();
        $bd->close();

    }

    public function guardar()
    {
        if ($this->idContenido>0) {
            return $this->actualizar();
        } else {
            return $this->insertar();
        }
    }


    public static function listado($pag) {
        $tamPagina = Contenido::TAM_PAGINA;
        $offset = ($pag - 1) * $tamPagina;
        $bd = abrirBD();
        $st = $bd->prepare("SELECT c.*, t.nombre FROM Contenidos c LEFT JOIN tipocontenido t ON c.idTipoContenido=t.idTipoContenido order by c.titulo Limit ? ,? ");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("ii", $offset, $tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $contenidos = [];
        while ($contenido = $res->fetch_assoc()) {
            $contenidos[] = $contenido;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $contenidos;
    }

    public static function listadoCategoria($idCat,$pag) {
        $tamPagina = Contenido::TAM_PAGINA;
        $offset = ($pag - 1) * $tamPagina;
        $bd = abrirBD();
        $st = $bd->prepare("SELECT c.*, t.nombre FROM Contenidos c 
        LEFT JOIN tipocontenido t ON c.idTipoContenido=t.idTipoContenido where c.idTipoContenido=? Limit ? ,? ");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("iii", $idCat,$offset, $tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $contenidos = [];
        while ($contenido = $res->fetch_assoc()) {
            $contenidos[] = $contenido;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $contenidos;
    }

    public static function cuentaContenido()
    {
        $bd = abrirBD();

        $st = $bd->prepare("SELECT COUNT(*) as num FROM Contenidos ");

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

    public static function cuentaConten($cate)
    {
        $bd = abrirBD();

        $st = $bd->prepare("SELECT COUNT(*) as num FROM Contenidos where idTipoContenido=? ");
        
        if ($st === FALSE) {
            die("ERROR: " . $bd->error);
        }
        $st->bind_param("i", $cate);
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

    public static function obtenerContenido($idContenido){
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM Contenidos where idContenido=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("i", $idContenido);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $contenido= $res->fetch_object("Contenido");
        $bd->close();
        $st->close();
        $res->close();

        return $contenido;
    }

    public static function borrar($id)
    {
        $bd = abrirBD();
        $st = $bd->prepare(
            'DELETE FROM Contenidos WHERE idContenido=?'
        );
        if ($st === FALSE) {
            die('ERROR SQL: ' . $bd->error);
        }
        $st->bind_param('i', $id);
        if ($st->execute() === FALSE) {
            die('ERROR BD: ' . $bd->error);
        }
        $st->close();
        $bd->close();
    }
  

    public function getRutaFichero(){
        return "ficheros/".$this->idContenido."_".$this->fichero;
    }

    public static function getRutaFicheroArray($file){
        return "ficheros/".$file["idContenido"]."_".$file["fichero"];
    }

    public function getRutaFoto(){
        return "fotos/".$this->idContenido."_".$this->imagen;
    }
    public static function getRutaArray($file){
        return "fotos/".$file["idContenido"]."_".$file["imagen"];
    }

    
}