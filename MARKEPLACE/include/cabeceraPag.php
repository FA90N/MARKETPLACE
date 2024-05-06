<!doctype html>
<html>

<head>
    <!-- Metas requeridos -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <title> <?=$tituloPagina?> </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    
</head>

<body>
    <div class="container">

    <div class="container">
    <nav class="navbar navbar-expand-md bg-success fixed-top" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand"> MarketPlace </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav me-auto">
                    
                    <li class="nav-item">
                        <a class="nav-link <?php if($activo=='index') echo "active" ;?>" href="index.php">Inicio</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link <?php if($activo=='biblioteca') echo "active" ;?>" href="biblioteca.php">Mi biblioteca</a>
                    </li>
                   
                    <?php if($admin): ?>
                        <li class="nav-item dropdown">
                            <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"> Administración</a>
                            <ul class="dropdown-menu" data-bs-theme="light">
                                <li>
                                    <a class="dropdown-item" href="usuarios.php"> Usuarios </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="contenidos.php"> Contenidos </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="adquisiciones.php">Adquisiciones</a>
                                </li>

                            </ul>
                        </li>
                    <?php endif;?>



                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="navbar-text">
                        <?=$nombreUser?>            
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-right"></i>
                            
                        </a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
  