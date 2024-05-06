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

$idUsuario=0;

if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    $user = $_SESSION['datos'];
    unset($_SESSION['errores']);
    unset($_SESSION['datos']);
} else {
    $errores = [];
    if(isset($_GET["idUsuario"])){
        $idUsuario = $_GET["idUsuario"];
    }
    
    if ($idUsuario > 0) {
        $user = Usuario::carga($idUsuario);
        
    } else {
        $user = new Usuario();
        
    }
}
$tituloPagina = "Mantenimiento de usuarios";
include __DIR__ . '/include/cabeceraPag.php';
?>

<h1><?= $tituloPagina; ?></h1>
<div class="row-3">

    <form action="guardarUsuario.php" method="POST" class="row g-3">
        <input id="id" name="id" type="hidden" value="<?= $user->idUsuario ;?>" />

        <div class="col-12">
            <label for="nombre" class="form-label">Nombre: </label>
            <input type="text" class="form-control <?php if (isset($errores['nombre'])) echo 'is-invalid'; ?>" 
                id="nombre" name="nombre" placeholder="Nombre" value="<?= e($user->nombre) ;?>" />
            <?php if (isset($errores['nombre'])) : ?>
                <div class="invalid-feedback">
                    <?= e($errores['nombre']) ;?>
                </div>
            <?php endif; ?>

        </div>
        <div class="col-12">
            <label for="email" class="form-label">Correo: </label>
            <input type="text" class="form-control <?php if (isset($errores['email'])) echo 'is-invalid'; ?>" data-id="<?= $user->idUsuario ?>" 
                id="email" name="email" placeholder="correo@example.com" value="<?= e($user->email) ;?>" />
            <?php if (isset($errores['email'])) : ?>
                <div class="invalid-feedback" >
                    <?= e($errores['email']) ;?>
                </div>
            <?php endif; ?>

            <div class="invalid-feedback d-none"  id="error">
                <strong>El email ya está registrado</strong> 
            </div>
        </div>
        <div class="col-12">
            <label for="Contraseña" class="form-label">Contraseña: </label>
            <input type="password" class="form-control <?php if (isset($errores['pwd'])) echo 'is-invalid'; ?>" id="pwd" name="pwd" placeholder="Contraseña" />
            <?php if (isset($errores['pwd'])) : ?>
                <div class="invalid-feedback">
                    <?= e($errores['pwd']) ;?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-check form-switch col-6">
            <input class="form-check-input" type="checkbox" name="admin" id="admin" <?php if ($user->admin) echo "checked"; ?>>
            <label class="form-check-label" for="admin">¿Es Admin?</label>
        </div>
        <div class="col-6 text-end">
            <button type="submit" class="btn btn-primary" id="guardar">Guardar</button>
            <a type="button" href="usuarios.php" class="btn btn-secondary"  disabled>Cancelar</a>
        </div>



    </form>
</div>

</div>


<script>
    const email = document.getElementById("email");
    const btnGuardar = document.getElementById("guardar");
    let div = document.createElement("div");
    email.addEventListener("change", function() {
        let id = email.dataset.id;
        let formData = new FormData();
        formData.append("email", email.value);
        formData.append("idUsuario", id);
        fetch("validarEmail.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(msg => {
                const error = document.getElementById("error");
                if (msg == "DUPLICADO") {
                    email.classList.add("is-invalid");
                    error.classList.remove("d-none");
                    btnGuardar.disabled = true;
                } else {
                    error.classList.add("d-none");
                    email.classList.remove("is-invalid");
                    btnGuardar.disabled = false;
                }
            });
    });
</script>


<?php include __DIR__ . '/include/scripts.php'; ?>
<?php include __DIR__ . '/include/pie.php'; ?>