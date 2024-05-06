<?php
require_once __DIR__ . '/lib/funcion.php';
require_once __DIR__ . '/modelos/usuario.php';
require_once __DIR__ . '/modelos/contenido.php';

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

$contenidos = Contenido::listado($pag);
$numero = Contenido::cuentaContenido();
$numPaginas = ceil($numero / Contenido::TAM_PAGINA);
$url = "contenidos.php?pag=";
$tituloPagina = "Mantenimiento de contenidos";
include __DIR__ . '/include/cabeceraPag.php';
?>

<div>
    <a type="button" href="mantenimientoContenido.php?idContenido=0" class="btn btn-primary">Añadir Contenido</a>
</div>
<br />
<div class="row">


    <div class="col-12">


        <table class="table">
            <thead>
                <tr class="table-primary">
                    <th>Titulo</th>
                    <th>Autor</th>
                    <th>Categoria</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contenidos as $c) : ?>
                    <tr>
                        <td><?= e($c['titulo']) ?></td>
                        <td><?= e($c['autor']) ?></td>
                        <td><?= e($c['nombre']) ?></td>

                        <td>
                            
                            <a class="btn btn-outline-primary" href="mantenimientoContenido.php?idContenido=<?=e($c["idContenido"])?>">Editar</a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#borrar<?= e($c['idContenido']) ?>" class="btn btn-outline-danger"> Borrar </a>
                        </td>
                    </tr>

                    <div class="modal fade " id="borrar<?= e($c['idContenido']) ?>">
                        <div class="modal-dialog ">
                            <div class="modal-content text-center">
                                <div class="modal-header">
                                    <h4 class="modal-tittle">
                                        <h5><i class="bi bi-exclamation-triangle"></i> Borrar Contenido</h5>
                                    </h4>
                                    <button type="button" class=btn-close data-bs-dismiss="modal" aria-label="Cerrar"> </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <h5>¿Seguro quieres borrar a <?= e($c['titulo']) ?>?</h5>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="borrarContenido.php?id=<?= e($c['idContenido']) ?>" class="btn btn-outline-danger">
                                        Borrar contenido
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

<?php include __DIR__ . '/include/paginacion.php'; ?>
<?php include __DIR__ . '/include/scripts.php'; ?>
<?php include __DIR__ . '/include/pie.php'; ?>