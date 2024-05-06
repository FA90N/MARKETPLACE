<?php
require_once __DIR__.'/bd.php';

class Usuario {
    public $idUsuario = 0;
    public $nombre;
    public $email;
    public $pwd;
    public $admin=0;
    const TAM_PAGINA = 3;

    public function insertar() {
        $bd = abrirBD();
        $st = $bd->prepare("INSERT INTO usuarios
                (nombre,email,pwd,admin) 
                VALUES (?,?,?,?)");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("sssi", 
                $this->nombre, 
                $this->email, 
                $this->pwd,
                $this->admin);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $this->idUsuario = $bd->insert_id;
        
        $st->close();
        $bd->close();
    }

    public function actualizar() {
        $bd = abrirBD();
        $st = $bd->prepare("UPDATE usuarios SET
                nombre=?, email=?, pwd=?, admin=? WHERE idUsuario=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("sssii", 
                $this->nombre, 
                $this->email, 
                $this->pwd,
                $this->admin,
                $this->idUsuario);
        $res = $st->execute();
        if ($res === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        
        $st->close();
        $bd->close();
    }

    public function guardar()
    {
        if ($this->idUsuario>0) {
            return $this->actualizar();
        } else {
            return $this->insertar();
        }
    }

    public static function cargaLogin($email) {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM usuarios
                WHERE email=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("s", $email);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $usuario = $res->fetch_object('Usuario');
        $res->free();
        $st->close();
        $bd->close();
        return $usuario;
    }
    public static function listado($pag) {
        $tamPagina = Usuario::TAM_PAGINA;
        $offset = ($pag - 1) * $tamPagina;
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM usuarios Limit ?,?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("ii", $offset,$tamPagina);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $usuarios=[];

        while ($usuario = $res->fetch_assoc()) {
            $usuarios[] = $usuario;
        }
        $res->free();
        $st->close();
        $bd->close();
        return $usuarios;
    }

    public static function carga($id) {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT * FROM usuarios
                WHERE idUsuario=?");
        if ($st === FALSE) {
            die("Error SQL: " . $bd->error);
        }
        $st->bind_param("i", $id);
        $ok = $st->execute();
        if ($ok === FALSE) {
            die("Error de ejecución: " . $bd->error);
        }
        $res = $st->get_result();
        $usuario = $res->fetch_object('Usuario');
        $res->free();
        $st->close();
        $bd->close();
        return $usuario;
    }


    public static function cuentaUsuario() 
    {
        $bd = abrirBD();
        $st = $bd->prepare("SELECT COUNT(*) as num FROM Usuarios");
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

    public static function borrar($id)
    {
        $bd = abrirBD();
        $st = $bd->prepare(
            'DELETE FROM Usuarios WHERE idUsuario=?'
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
}