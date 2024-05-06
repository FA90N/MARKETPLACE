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
$nombreUser = $usuario->nombre;
$admin = $usuario->admin;

if (!$admin) {
    http_response_code(403);
    die("Forbidden");
}

$pag = 0;
if (isset($_GET['pag'])) {
    $pag = $_GET['pag'];
} else {
    $pag = 1;
}
$usuarios = Usuario::listado($pag);
$numero = Usuario::cuentaUsuario();
$numPaginas = ceil($numero / Usuario::TAM_PAGINA);
$tituloPagina = "Mantenimiento de usuarios";
$url = "usuarios.php?pag=";



include __DIR__ . '/include/cabeceraPag.php';
?>

<div>
        <a type="button" href="mantenimientoUsuario.php?idUsuario=0" class="btn btn-primary">Añadir Usuario</a>
</div>
<br/>
<div class="row"> 
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr class="table-primary">
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>¿Es Admin?</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u) : ?>
                    <tr>
                        <td><?= e($u['nombre']) ?></td>
                        <td><?= e($u['email']) ?></td>
                        <td>
                            <?php if (e($u['admin'])) : ?>
                                SI
                            <?php else : ?>
                                NO
                            <?php endif; ?> 
                        </td>

                        <td>
                            <a class="btn btn-outline-primary" href="mantenimientoUsuario.php?idUsuario=<?=e($u['idUsuario'])?>" id="editar">Editar</a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#borrar<?= e($u['idUsuario']) ?>" class="btn btn-outline-danger"> Borrar </a>
                        </td>
                    </tr>

                    <div class="modal fade " id="borrar<?= e($u['idUsuario']) ?>">
                        <div class="modal-dialog ">
                            <div class="modal-content text-center">
                                <div class="modal-header">
                                    <h4 class="modal-tittle">
                                        <h5><i class="bi bi-exclamation-triangle"></i> Borrar Usuario</h5>
                                    </h4>
                                    <button type="button" class=btn-close data-bs-dismiss="modal" aria-label="Cerrar"> </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <h5>¿Seguro quieres borrar a <?= e($u['nombre']) ?>?</h5>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="borrarUsuario.php?id=<?= e($u['idUsuario']) ?>" class="btn btn-outline-danger">
                                        Borrar usuario
                                    </a>
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                                        Cancelar
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>
<script>   
</script>

<?php include __DIR__ . '/include/paginacion.php' ;?>
<?php include __DIR__ . '/include/scripts.php'; ?>
<?php include __DIR__ . '/include/pie.php'; ?>