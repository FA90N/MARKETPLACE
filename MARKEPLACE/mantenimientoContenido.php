<?php
require_once __DIR__ . '/lib/funcion.php';
require_once __DIR__ . '/modelos/usuario.php';
require_once __DIR__ . '/modelos/contenido.php';
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
if (!$admin) {
    http_response_code(403);
    die("Forbidden");
}
$categoria = Categoria::listado();
$idContenido = 0;
$errores = [];
$contenido = new Contenido();
$rutaImagen = "fotos/nofoto.jpg";
$actual = "";
if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    $contenido = $_SESSION['datos'];
    unset($_SESSION['errores']);
    unset($_SESSION['datos']);
} else {

    if (isset($_GET["idContenido"])) {
        $idContenido = $_GET["idContenido"];
    }

    if ($idContenido > 0) {
        $contenido = Contenido::obtenerContenido($idContenido);
        
        $rutaImagen = $contenido->getRutaFoto();
        $actual = "Fichero: " . $contenido->fichero;
    }
}
$idTipo = $contenido->idTipoContenido;

$tituloPagina = "Mantenimiento de Contenidos";
include __DIR__ . '/include/cabeceraPag.php';
?>

<div class="row g-3">
    <div class="col-md-6">
        <h1><?= $tituloPagina; ?></h1>
    </div>
</div>

<div class="col-md-12">
    <form action="guardarContenido.php" method="POST" class="row g-3" enctype="multipart/form-data">

        <input id="id" name="id" type="hidden" value="<?= e($contenido->idContenido) ?>" />

        <div class="col-md-6">
            <label class="form-label" for="titulo">
                <strong>Titulo: </strong>
            </label>
            <input type="text" class="form-control <?php if (isset($errores['titulo'])) echo 'is-invalid'; ?>" id="titulo" name="titulo" placeholder="Titulo" value="<?= e($contenido->titulo) ?>" />

            <?php if (isset($errores['titulo'])) : ?>
                <div class="invalid-feedback">
                    <?= e($errores['titulo']) ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="col-md-6">
            <label class="form-label" for="autor">
                <strong>Autor: </strong>
            </label>
            <input type="text" class="form-control 
                <?php if (isset($errores['autor'])) echo 'is-invalid'; ?>" id="autor" name="autor" placeholder="Autor" value="<?= e($contenido->autor) ?>" />

            <?php if (isset($errores['autor'])) : ?>
                <div class="invalid-feedback">
                    <?= e($errores['autor']) ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="col-md-6">
            <label class="form-label" for="tipo">
                <strong>Tipo de contenido: </strong>
            </label>

            <select class="form-select" name="tipo" id="tipo">
                <?php foreach ($categoria as $c) : ?>
                    <option value="<?= e($c["idTipoContenido"]) ?>" <?php if ($idTipo == e($c["idTipoContenido"])) echo "selected"; ?>><?= e($c["nombre"]) ?></option>
                <?php endforeach; ?>
            </select>

        </div>

        <div class="col-md-6">
            <label class="form-label " for="Descripcion">
                <strong>Descripción: </strong>
            </label>
            <textarea class="form-control  <?php if (isset($errores['descripcion'])) echo 'is-invalid'; ?>" name="descripcion" id="descripcion" cols="10" rows="5"><?= e($contenido->descripcion) ?></textarea>
            <?php if (isset($errores['descripcion'])) : ?>
                <div class="invalid-feedback">
                    <?= e($errores['descripcion']) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6 text-center">
            <label class="form-label " for="imagen">
                <strong>
                    <h5>Imagen:</h5>
                </strong>
            </label>
            <input type="file" name="foto" id="foto" class="d-none form-control <?php if (isset($errores['foto'])) echo 'is-invalid'; ?>" />
            <div id="dropArea" class="bg-body-secondary rounded mb-3 mx-auto p4 text-center" style="width:100%">

                <img src="<?= $rutaImagen ?>" id="imagen">
                <br>
                <button type="button" id="selImagen" class="btn btn-primary">
                    Selecciona la imagen
                </button>
                <br />
                <strong>o arrástralo aquí</strong>
            </div>

            <?php if (isset($errores['foto'])) : ?>
                <div class="invalid-feedback">
                    <?= e($errores['foto']) ?>
                </div>
            <?php endif; ?>


        </div>

        <div class="col-md-6 text-center">
            <label class="form-label" for="fichero">
                <strong>
                    <h5>Fichero:</h5>
                </strong>
            </label>

            <input type="file" name="fichero" id="fichero" class="d-none form-control <?php if (isset($errores['fichero'])) echo 'is-invalid'; ?>" />
            <div id="dropArea" class="bg-body-secondary rounded mb-3 mx-auto p4 text-center" style="width:100%">
                <img src="fotos/noFichero.png" id="imgFichero" style="width:180px">
                <br>
                <button type="button" id="selFichero" class="btn btn-primary">
                    Selecciona el fichero
                </button>
                <p>o arrástralo aquí</p>
                <strong id="actual" class="text-aling-end"><?= $actual ?></strong>
            </div>


            <div class="text-center">
                
                    <a type="button" href="descargar.php?id=<?= $contenido->idContenido ?>" class="btnDescargar btn btn-warning d-none">Descargar contenido</a>
               


            </div>

            <?php if (isset($errores['fichero'])) : ?>
                <div class="invalid-feedback">
                    <?= e($errores['fichero']) ?>
                </div>
            <?php endif; ?>


        </div>


        <div class="col-md-12 text-start">
            <button type="submit" class="btn btn-primary" id="guardar">Guardar</button>
            <a type="button" href="contenidos.php" class="btn btn-secondary" disabled>Cancelar</a>
        </div>
    </form>
</div>

</div>

<script>
    const foto = document.getElementById("foto");
    const fichero = document.getElementById("fichero");
    const btnFoto = document.getElementById("selImagen");
    const btnFichero = document.getElementById("selFichero");
    const imagen = document.getElementById("imagen");
    const actual = document.getElementById("actual");
    const btnDescargar = document.querySelector(".btnDescargar")
    const imgFichero = document.getElementById("imgFichero");
    <?php if ($contenido->idContenido > 0) : ?>
        btnDescargar.classList.remove("d-none")
    <?php endif; ?>
    //Imagen
    foto.addEventListener("change", leerImagen);
    btnFoto.addEventListener("click", function() {
        foto.click();
    })
    imagen.addEventListener("dragenter", function() {
        imagen.classList.add("border", "border-3", "border-primary");
    });

    imagen.addEventListener("dragleave", function() {
        imagen.classList.remove("border", "border-3", "border-primary");
    });

    imagen.addEventListener("dragover", function(e) {
        imagen.classList.add("border", "border-3", "border-primary");
        e.preventDefault();
    });
    imagen.addEventListener("drop", function(e) {
        if (e.dataTransfer.files) {
            foto.files = e.dataTransfer.files;
            leerImagen();
        }
        imagen.classList.remove("border", "border-3", "border-primary");
        e.preventDefault();
    });


    function leerImagen() {
        let ima = foto.files[0];
        let reader = new FileReader();
        reader.onloadend = function() {
            let data = reader.result;
            imagen.src = data;
        };
        reader.readAsDataURL(ima);
    }

    //Fichero
    btnFichero.addEventListener("click", function() {
        fichero.click();
    })

    imgFichero.addEventListener("dragenter", function() {
        imgFichero.classList.add("border", "border-3", "border-primary");
    });

    imgFichero.addEventListener("dragleave", function() {
        imgFichero.classList.remove("border", "border-3", "border-primary");
    });

    imgFichero.addEventListener("dragover", function(e) {
        imgFichero.classList.add("border", "border-3", "border-primary");
        e.preventDefault();
    });
    imgFichero.addEventListener("drop", function(e) {
        if (e.dataTransfer.files) {
            fichero.files = e.dataTransfer.files;
            leerFichero();
        }
        imgFichero.classList.remove("border", "border-3", "border-primary");
        e.preventDefault();
    });

    fichero.addEventListener("change", leerFichero)

    function leerFichero() {
        let fi = fichero.files[0];
        btnDescargar.classList.add("d-none");
        actual.innerText = fi.name;
    }
</script>


<?php include __DIR__ . '/include/scripts.php'; ?>
<?php include __DIR__ . '/include/pie.php'; ?>