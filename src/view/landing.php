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
    <meta name="description"
        content="Sistema de gestión para el Gimnasio de Halterofilia 'Eddie Suarez' de la Universidad Politécnica Territorial Andrés Eloy Blanco (UPTAEB). Administra y organiza la información de atletas, entrenadores, asistencias y eventos de manera eficiente y segura.">
    <title>Gimnasio de Halterofilia 'Eddie Suarez'</title>
    <?php require_once dirname(__DIR__)."/view/comunes/linkcss.php"; ?>
    <link rel="preconnect" href="https://maps.gstatic.com">
</head>

<body class="">
    <header>
        <?php require_once dirname(__DIR__)."/view/comunes/menu.php"; ?>
    </header>
    <main class="container mt-4">
        <!-- Hero Section -->
        <section class="landing-hero text-center">
            <div class="container">
                <h1 class="display-3 fw-bold mb-4">¡Bienvenido al Gimnasio 'Eddie Suarez'!</h1>
                <p class="lead mb-4">Fortaleciendo cuerpo y mente, un día a la vez.</p>
                <p class="mb-5">Te invitamos a formar parte de nuestra comunidad de halterofilia.</p>
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                            <a class="btn btn-light btn-lg px-5" href="?p=login" role="button">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión
                            </a>
                            <a class="btn btn-outline-light btn-lg px-5 disabled" href="?p=registro" role="button">
                                <i class="fas fa-user-plus me-2"></i>Registrarse
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Servicios Section -->
        <section class="py-5" id="servicios">
            <h2 class="section-title text-center mb-5">Nuestros Servicios</h2>
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg">
                    <div class="landing-service-card">
                        <i class="fas fa-dumbbell"></i>
                        <h3>Halterofilia</h3>
                        <p class="text-muted mb-0">Entrenamiento especializado en levantamiento olímpico</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg">
                    <div class="landing-service-card">
                        <i class="fas fa-user"></i>
                        <h3>Entrenamientos Personalizados</h3>
                        <p class="text-muted mb-0">Planes adaptados a tus objetivos</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg">
                    <div class="landing-service-card">
                        <i class="fas fa-bullseye"></i>
                        <h3>Preparación Física</h3>
                        <p class="text-muted mb-0">Mejora tu rendimiento deportivo</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg">
                    <div class="landing-service-card">
                        <i class="fas fa-heartbeat"></i>
                        <h3>Cardio</h3>
                        <p class="text-muted mb-0">Fortalece tu sistema cardiovascular</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg">
                    <div class="landing-service-card">
                        <i class="fas fa-running"></i>
                        <h3>Pliometría</h3>
                        <p class="text-muted mb-0">Desarrolla potencia y explosividad</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ubicación Section -->
        <section class="py-5">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-lg-6">
                    <div class="carousel-container">
                        <div class="carousel-slide">
                            <img src="assets/img/imagen1.jpg" alt="Imagen 1" class="active">
                            <img src="assets/img/imagen2.jpg" alt="Imagen 2">
                            <img src="assets/img/imagen3.jpg" alt="Imagen 3">
                        </div>
                        <button class="carousel-control-prev" onclick="moveSlide(-1)">&#10094;</button>
                        <button class="carousel-control-next" onclick="moveSlide(1)">&#10095;</button>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <h2 class="section-title mb-4">Ubicación</h2>
                    <p class="lead mb-4">Visítanos en nuestra dirección física para más información.</p>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d982.1476115489014!2d-69.36323721453827!3d10.050614629063217!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e8766b9ad2ae25b%3A0xa1b16cf446857ad1!2sUPTAEB%20-%20Universidad%20Polit%C3%A9cnica%20Territorial%20Andr%C3%A9s%20Eloy%20Blanco!5e0!3m2!1ses!2sco!4v1726695397731!5m2!1ses!2sco"
                                width="100%" height="300" style="border:0; border-radius: var(--radius-lg);" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade" title='Ubicación del Gimnasio "Eddie Suarez"'></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contacto Section -->
        <section class="py-5">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title mb-4">Contacto</h2>
                    <p class="lead mb-4">¿Tienes preguntas? ¡No dudes en contactarnos!</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="mailto:jugneycontacto@gmail.com" class="btn-contacto">
                            <i class="fas fa-envelope"></i> Correo
                        </a>
                        <a href="https://wa.me/584245681343" target="_blank" class="btn-contacto">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>
    <?php require_once dirname(__DIR__)."/view/comunes/footer.php"; ?>
    <script src="assets/js/landing.js"></script>
</body>

</html>