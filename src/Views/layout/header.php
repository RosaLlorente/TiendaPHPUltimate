<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TiendaPHP</title>

    <!--ENLACE CSS DE BOOTSTRAP-->
    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
    />
    <!--ENLACE CSS PROPIO-->
    <link rel="stylesheet" href="<?=BASE_URL?>/public/CSS/Style.css" name="EstilosPersonalizadosPropios" content="Archivo que contiene css propio">

</head>
<body>
<header class="w-100" style="z-index: 1;">
    <nav class="navbar navbar-expand-lg bg-body-tertiary w-100 position-fixed">
        <div class="container-fluid">
                <a class="Marca navbar-brand" href="<?=BASE_URL?>">TiendaPHP</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?=BASE_URL?>Catalog">Ver Catalogo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?=BASE_URL?>Cart"><svg style="width: 20px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6.29977 5H21L19 12H7.37671M20 16H8L6 3H3M9 20C9 20.5523 8.55228 21 8 21C7.44772 21 7 20.5523 7 20C7 19.4477 7.44772 19 8 19C8.55228 19 9 19.4477 9 20ZM20 20C20 20.5523 19.5523 21 19 21C18.4477 21 18 20.5523 18 20C18 19.4477 18.4477 19 19 19C19.5523 19 20 19.4477 20 20Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></a>
                        </li>
                        <?php if(isset($_SESSION['user_id'])): ?>
                        <li>
                            <a class="nav-link active" aria-current="page" href="<?=BASE_URL?>SeeMyOrders/<?=$_SESSION['user_id']?>">Ver mis pedidos</a>
                        </li>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'):?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Gestión de categorias
                                </a>
                                <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?=BASE_URL?>CreateCategory">Crear nueva categoria</a></li>
                                <li><a class="dropdown-item" href="<?=BASE_URL?>HandleCategory">Gestionar categorias</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Gestión de productos
                                </a>
                                <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?=BASE_URL?>CreateProduct">Crear nuevo producto</a></li>
                                <li><a class="dropdown-item" href="<?=BASE_URL?>HandleProducts">Gestionar productos</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="<?=BASE_URL?>HandleOrder">Gestionar pedidos</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <div>
                        <a class="btn btn-outline-success" href="<?=BASE_URL?>login">Iniciar Sesión</a>
                        <a class="btn btn-outline-success" href="<?=BASE_URL?>register">Registrarse</a>
                    </div>
                <?php else: ?>
                    
                <div>
                    <b>Bienvenido, <?= htmlspecialchars($_SESSION['user_name']); ?> </b>
                    <a class="btn btn-outline-success" href="<?=BASE_URL?>logout">Cerrar Sesión</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>
<main>