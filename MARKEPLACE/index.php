<?php
require_once __DIR__ . '/lib/funcion.php';
require_once __DIR__ . '/modelos/usuario.php';
require_once __DIR__ . '/modelos/contenido.php';
require_once __DIR__ . '/modelos/adquisicion.php';
require_once __DIR__ . '/modelos/categoria.php';

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
$activo = "index";
$tituloPagina = "MarketPlace";

$pag = 0;
if (isset($_GET['pag'])) {
    $pag = $_GET['pag'];
} else {
    $pag = 1;
}

if (isset($_GET["idCategoria"])) {
    $cate = $_GET["idCategoria"];
} elseif (isset($_SESSION["categoria"])) {
    $cate = $_SESSION["categoria"];
} else {
    $cate = 0;
}


if ($cate > 0) {
    $contenidos = Contenido::listadoCategoria($cate, $pag);
    $numero = Contenido::cuentaConten($cate);
} else {
    $contenidos = Contenido::listado($pag);
    $numero = Contenido::cuentaContenido();
}


$_SESSION["categoria"] = $cate;
$numPaginas = ceil($numero / Contenido::TAM_PAGINA);
$categoria = Categoria::listado();
$url = "index.php?pag=";

include __DIR__ . '/include/cabeceraPag.php';
?>

<div class="row g-3">
    <h1 class="display-1 text-center">Contenidos disponibles</h1>
    <div class=" col-md-3">
        <h5>Seleccione una categoria: </h5>

        <select class="form-select" id="categorias">
            <option value="0">Ver todo</option>
            <?php foreach ($categoria as $cat) : ?>

                <option value="<?= e($cat["idTipoContenido"]) ?>" <?php if (e($cat["idTipoContenido"]) == $cate) echo "selected"; ?>><?= e($cat["nombre"]) ?></option>

            <?php endforeach; ?>
        </select>
    </div>

    <hr>
    <?php foreach ($contenidos as $c) : ?>
        <div class="listas col-12 col-md-6 col-lg-4" data-bs-toggle="modal" data-bs-target="#mostrar<?= $c['idContenido'] ?>">
            <br />
            <div class="card">
                <img id="foto" src="<?= Contenido::getRutaArray($c) ?>" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title"><?= e($c["titulo"]) ?></h5>


                    <div class="col-12">
                        <p><strong><?= e($c["nombre"]) ?></strong> </p>
                        <p>Autor: <?= e($c["autor"]) ?></p>
                    </div>

                    <div class="icono col-12 text-end">
                        <?php $aq = Adquisicion::comprobar($usuario->idUsuario, e($c["idContenido"])); ?>
                        <?php if (isset($aq["idAdquisicion"])) : ?>
                            <i class="bi bi-check-circle-fill" data-toggle="tooltip" data-placement="right" title=" <?php if ($aq["aprobado"] == 1) echo "En la biblioteca"; ?>"></i>
                        <?php endif; ?>

                    </div>


                </div>


            </div>

        </div>

        <!--VENTANA  MODAL  -->
        <div class="modal fade" id="mostrar<?= $c['idContenido'] ?>">

            <div class="modal-dialog ">
                <div class="modal-content text-center">
                    <div class="modal-header">
                        <h3 class="modal-tittle">
                            <h5>Titulo: <?= e($c["titulo"]) ?></h5>
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"> </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <img id="foto" src="<?= Contenido::getRutaArray($c) ?>">
                            <p>Autor: <?= e($c["autor"]) ?></p>
                            <p>Descripción: <?= nl2br(e($c["descripcion"])) ?></p>
                        </div>
                    </div>
                    <div class="opcion modal-footer">

                        <?php if (isset($aq["idAdquisicion"]) && $aq["aprobado"] > 0) : ?>
                            <a type="button" class="btn btn-success btn-sm" href="descargar.php?id=<?= $c['idContenido'] ?>">Descargar</a>
                        <?php elseif (isset($aq["idAdquisicion"]) && $aq["aprobado"] == 0) : ?>
                            <p>"Pedido pendiente"</p>
                        <?php else : ?>
                            <button data-id="<?= $c["idContenido"] ?>" type="button" class="adquirir btn btn-outline-success">
                                Adquirir
                            </button>
                        <?php endif; ?>


                    </div>
                </div>

            </div>
        </div>

    <?php endforeach; ?>

</div>

<?php include __DIR__ . '/include/paginacion.php'; ?>

<script src="index.js">
   
</script>
<?php include __DIR__ . '/include/scripts.php'; ?>
<?php include __DIR__ . '/include/pie.php'; ?>