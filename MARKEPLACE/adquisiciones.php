<?php
require_once __DIR__ . '/lib/funcion.php';
require_once __DIR__ . '/modelos/usuario.php';
require_once __DIR__ . '/modelos/adquisicion.php';


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

$lista = Adquisicion::listado($pag);
$numero = Adquisicion::cuentaAdqui();
$numPaginas = ceil($numero / Adquisicion::TAM_PAGINA);

$tituloPagina = "Mantenimiento de adquisiciones";
$url = "adquisiciones.php?pag=";
include __DIR__ . "/include/cabeceraPag.php";
?>

<table class="table">
    <thead>
        <tr class="table-primary">
            <th>Fecha Pedido</th>
            <th>Nombre Usuario</th>
            <th>Titulo del pedido</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista as $l) : ?>
            <tr id="tabla">
                <td><?= e(formatearFechaHoraLarga($l["fechaPedido"])); ?></td>
                <td><?= e($l["nombre"]) ?></td>
                <td><?= e($l["titulo"]) ?></td>
                <td id="estado">
                    <?php if ($l["aprobado"] == 1) : ?>
                        <?= e(formatearFechaHoraLarga($l["fechaAprobacion"])) ?>
                    <?php else : ?>
                        <button type="button" data-id="<?= $l["idAdquisicion"] ?>" id="aprobar" class="aprobar btn btn-success">Aprobar </button>
                    <?php endif; ?>
                </td>

                <td> <a href="#" data-bs-toggle="modal" data-bs-target="#borrar<?= e($l['idAdquisicion']) ?>" class="btn btn-outline-danger"> Borrar </a></td>
            </tr>


            <!-- MODAL DE BORRAR -->

            <div class="modal fade " id="borrar<?= $l['idAdquisicion'] ?>">
                <div class="modal-dialog ">
                    <div class="modal-content text-center">
                        <div class="modal-header">
                            <h4 class="modal-tittle">
                                <h5><i class="bi bi-exclamation-triangle"></i> Borrar Adquisicion </h5>
                            </h4>
                            <button type="button" class=btn-close data-bs-dismiss="modal" aria-label="Cerrar"> </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <h5>¿Seguro quieres borrar esta adquisición de <?= e($l['nombre']) ?>?</h5>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="borrarAdquisicion.php?id=<?= e($l['idAdquisicion']) ?>" class="btn btn-outline-danger">
                                Borrar Adquisición
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

<script>
    const botones = document.querySelectorAll(".aprobar");

    botones.forEach((b) => {
        const p = b.parentElement;
        b.addEventListener("click", () => {
            console.log(b.dataset.id);
            const data = new FormData();
            data.append("idAdquisicion", b.dataset.id);
            fetch("aprobar.php", {
                    method: "POST",
                    body: data
                })
                .then(res => res.json())
                .then(a => {
                    let fecha = document.createElement("p");
                    var fechaObjeto = new Date(a.fechaAprobacion);
                    // Obtener los componentes de la fecha
                    var dia = fechaObjeto.getDate();
                    var mes = fechaObjeto.toLocaleString('default', {
                        month: 'short'
                    });
                    var anio = fechaObjeto.getFullYear();
                    var horas = fechaObjeto.getHours();
                    var minutos = fechaObjeto.getMinutes();
                    // Formatear la fecha en el estilo deseado
                    var fechaFormateada = dia + ' ' + mes + '. ' + anio + ' ' + horas.toString().padStart(2, '0') + ':' + minutos.toString().padStart(2, '0');
                    fecha.innerText = fechaFormateada;
                    p.replaceChild(fecha, b);
                })

            // .then(res=>{
            //     if(res.ok){  
            //         let fecha = document.createElement("p");
            //         fecha.innerText = "<?= formatearFechaHoraLarga(ObtenerFecha()) ?>";
            //        
            //         p.replaceChild(fecha,b);
            //     }
            // })


        })
    })
</script>


<?php include __DIR__ . '/include/paginacion.php'; ?>
<?php include __DIR__ . '/include/scripts.php'; ?>
<?php include __DIR__ . '/include/pie.php'; ?>