<?php
require_once __DIR__.'/lib/funcion.php';
require_once __DIR__.'/modelos/usuario.php';
require_once __DIR__ . '/modelos/contenido.php';
require_once __DIR__ . '/modelos/adquisicion.php';

session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
}
else {
    header('Location: login.php');
    die();
    $usuario = null;
}
$nombreUser=$usuario->nombre;
$admin = $usuario->admin;
$activo="biblioteca";
$tituloPagina="Biblioteca";

$pag = 0;
if (isset($_GET['pag'])) {
    $pag = $_GET['pag'];
} else {
    $pag = 1;
}

$lista = Adquisicion::listadoAdquisicion($usuario->idUsuario,$pag);
$numero = Adquisicion::cuentaAdquisicion($usuario->idUsuario);
$numPaginas = ceil($numero / Adquisicion::TAM_PAGINA);

$url = "biblioteca.php?pag=";

include __DIR__.'/include/cabeceraPag.php';
?>

<table class="table">
    <thead >
        <tr class="table-primary">
            <th>Imagen</th>
            <th>Titulo</th>
            <th>Fecha pedido</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
       <?php foreach($lista as $l):?>
            <tr>
                <td><img id="foto1"  src="<?= Contenido::getRutaArray($l) ?>"></td>     
                <td><?=e($l["titulo"]) ?></td>  
                <td><?=e(formatearFechaHoraLarga($l["fechaPedido"]));?></td>
                <td>
                    <?php if($l["aprobado"]==1):?>
                        <a type="button" class="btn btn-success btn-sm" href="descargar.php?id=<?=$l['idContenido']?>">Descargar</a>
                    <?php else :?>
                        <p>
                            "Pendiente"
                        </p>
                    <?php endif;?>

                </td>
            </tr>

        <?php endforeach;?>
    </tbody>
</table>



<?php include __DIR__ . '/include/paginacion.php' ;?>
<?php include __DIR__ . '/include/scripts.php'; ?>
<?php include __DIR__ . '/include/pie.php'; ?>