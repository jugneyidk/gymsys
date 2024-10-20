<!-- Landing page 
"esta es la pagina principal del proyecto, cuando se entra primera vez 
y cuando no haya sesion iniciada esta sera la primera vista a mostrar"
Info: solo es una vista de bienvenida, acceso a la login a traves de sus referencias
Estado: medio (falta añadir detalles para que este terminada) 
-->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido!</title>
    <?php require_once("comunes/linkcss.php"); ?>
    <link href="css/landing.css" rel="stylesheet">
</head>

<body class="d-flex flex-column vh-100">
    <header>
        <?php require_once("comunes/menu.php"); ?>
    </header>
    <main class="container mt-5">
        <!-- Sección de bienvenida -->
        <div class="jumbotron text-center bg-light p-5 rounded shadow-sm">
            <h1 class="display-3 text-primary">¡Bienvenido al Gimnasio UPTAEB!</h1>
            <p class="lead">Fortaleciendo cuerpo y mente, un día a la vez.</p>
            <hr class="my-4">
            <p>Te invitamos a formar parte de nuestra comunidad.</p>
            <a class="btn btn-outline-primary btn-lg me-2" href="?p=login" role="button">Iniciar sesión</a>
            <a class="btn btn-primary btn-lg" href="?p=registro" role="button">Registrarse</a>
        </div>
        <section class="row mt-5" id="servicios">
            <h2 class="text-center mb-5">Servicios</h2>
            <div class="col d-flex align-items-stretch">
                <div class="card bg-light border-0 p-3 w-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="fas fa-dumbbell h2"></i>
                        </div>
                        <h5 class="card-title mb-2">Halterofilia</h5>
                    </div>
                </div>
            </div>
            <div class="col d-flex align-items-stretch">
                <div class="card bg-light border-0 p-3 w-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="fas fa-user h2"></i>
                        </div>
                        <h5 class="card-title mb-2">Entrenamientos personalizados</h5>
                    </div>
                </div>
            </div>
            <div class="col d-flex align-items-stretch">
                <div class="card bg-light border-0 p-3 w-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="fas fa-bullseye h2"></i>
                        </div>
                        <h5 class="card-title mb-2">Físico</h5>
                    </div>
                </div>
            </div>
            <div class="col d-flex align-items-stretch">
                <div class="card bg-light border-0 p-3 w-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="fas fa-heartbeat h2"></i>
                        </div>
                        <h5 class="card-title mb-2">Cardio</h5>
                    </div>
                </div>
            </div>
            <div class="col d-flex align-items-stretch">
                <div class="card bg-light border-0 p-3 w-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="fas fa-running h2"></i>
                        </div>
                        <h5 class="card-title mb-2">Pliometría</h5>
                    </div>
                </div>
            </div>

        </section>
        <section class="row mt-5">
            <div class="col">
                <div class="carousel-container">
                    <div class="carousel-slide">
                        <img src="img/imagen1.jpg" alt="Imagen 1" class="active">
                        <img src="img/imagen2.jpg" alt="Imagen 2">
                        <img src="img/imagen3.jpg" alt="Imagen 3">
                    </div>
                    <button class="carousel-control-prev" onclick="moveSlide(-1)">&#10094;</button>
                    <button class="carousel-control-next" onclick="moveSlide(1)">&#10095;</button>
                </div>
            </div>            
            <div class="col">
                <h2 class="">Ubicación</h2>
                <span>Visítanos en nuestra dirección física para más información.</span>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d982.1476115489014!2d-69.36323721453827!3d10.050614629063217!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e8766b9ad2ae25b%3A0xa1b16cf446857ad1!2sUPTAEB%20-%20Universidad%20Polit%C3%A9cnica%20Territorial%20Andr%C3%A9s%20Eloy%20Blanco!5e0!3m2!1ses!2sco!4v1726695397731!5m2!1ses!2sco"
                    width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
        <div class="col-md-2">
            <h2 class="">Contacto</h2>
            <p>¿Tienes preguntas? ¡No dudes en contactarnos!</p>
            <div class="d-flex">
                <a href="mailto:jugneycontacto@gmail.com" class="btn-contacto me-3">
                    <i class="fas fa-envelope"></i> Correo
                </a>
                <a href="https://wa.me/584245681343" target="_blank" class="btn-contacto">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
            </div>
        </div>

    </main>
    <br>
    <?php require_once("comunes/footer.php"); ?>
    <script src="js/landing.js"></script>
</body>

</html>