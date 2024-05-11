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
    <main>
    <!-- Sección de bienvenida -->
  <div class="container mt-5">
    <div class="jumbotron">
      <h1 class="display-4">¡Bienvenido!</h1>
      <p class="lead">Sistema de informacion del gimnasio de halterofilia de la UPTAEB.</p>
      <hr class="my-4">
      <p>Breve historia aqui.</p>
      <a class="btn btn-primary btn-lg" href="?p=login" role="button">Iniciar sesion</a>
    </div>
  </div>
<div class="row">
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

  <!-- Sección de características -->
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-4">
        <h2>item 1</h2>
        <p> nosequeponeraqui</p>
      </div>
      <div class="col-md-4">
        <h2>item 2</h2>
        <p>nosequeponeraqui</p>
      </div>
      <div class="col-md-4">
        <h2>item 3</h2>
        <p> nosequeponeraqui</p>
      </div>
    </div>
  </div>
    </main>
    <footer>
    <?php require_once("comunes/footer.php"); ?>
    </footer>
    <script src="js/landing.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    
</body>
</html>