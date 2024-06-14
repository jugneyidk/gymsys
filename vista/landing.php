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
</head>
<body>
    <header>
        <?php require_once("comunes/menu.php"); ?>
    </header>
    <main class="container mt-5">
        <!-- Sección de bienvenida -->
        <div class="jumbotron text-center">
            <h1 class="display-4">¡Bienvenido al Gimnasio UPTAEB!</h1>
            <p class="lead">El mejor lugar para fortalecer tu cuerpo y mente.</p>
            <hr class="my-4">
            <p>Únete a nosotros para explorar nuevas maneras de mantenerte en forma y saludable.</p>
            <a class="btn btn-primary btn-lg" href="?p=login" role="button">Iniciar sesión</a>
        </div>
        <!-- Sección adicional, por ejemplo, servicios -->
        <div class="row">
          <!-- Sección de bienvenida -->
<div class="col-md-5">
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
            <div class="col-md-2">
                <h2>Servicios</h2>
                <p>Ofrecemos entrenamiento personalizado, clases en grupo, y más.</p>
                <p><a class="btn btn-secondary" href="#" role="button">Ver detalles »</a></p>
            </div>
            <div class="col-md-2">
                <h2>Contacto</h2>
                <p>¿Tienes preguntas? ¡No dudes en contactarnos!</p>
                <p><a class="btn btn-secondary" href="#" role="button">Contactar »</a></p>
            </div>
            <div class="col-md-2">
                <h2>Ubicación</h2>
                <p>Visítanos en nuestra dirección física para más información.</p>
                <p><a class="btn btn-secondary" href="#" role="button">Cómo llegar »</a></p>
            </div>
        </div>
    </main>
    <br>
    <?php require_once("comunes/footer.php"); ?>
    <script src="js/landing.js"></script>
</body>
</html>
