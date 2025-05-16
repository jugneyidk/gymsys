<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tarjeta de Usuario - Halterofilia</title>
   <?php require_once "comunes/linkcss.php" ?>
</head>

<body class="bg-light">
   <?php require_once "comunes/menu.php" ?>
   <main class="container-md my-3 my-md-5">
      <div class="row justify-content-center">
         <div class="col-lg-6">
            <div class="card placeholder-glow">
               <div class="card-header text-center bg-white border-0">
                  <!-- <img src="assets/img/atleta-foto.png" alt="Foto del Atleta" class="rounded-circle img-thumbnail"
                     style="width: 150px; height: 150px; object-fit: cover;"> -->
                  <span class="rounded-circle  bg-primary placeholder" style="width: 150px; height: 150px;"></span>
                  <span class="mt-3 d-block" id="cedula">
                     <span class="placeholder col-4"></span>
                  </span>
                  <h3 class="mb-0" id="nombre-completo">
                     <span class="placeholder col-6"></span>
                  </h3>
                  <small id="edad" class="text-muted">
                     <span class="placeholder col-3"></span>
                  </small>
               </div>
               <div class="card-body">
                  <div class="row mb-3">
                     <div class="col-md-4 text-center">
                        <span class="fw-bold">Peso</span>
                        <div class="text-muted" id="peso">
                           <span class="placeholder w-100"></span>
                        </div>
                     </div>
                     <div class="col-md-4 text-center">
                        <span class="fw-bold">Altura</span>
                        <div class="text-muted" id="altura">
                           <span class="placeholder w-100"></span>
                        </div>
                     </div>
                     <div class="col-md-4 text-center">
                        <span class="fw-bold">Genero</span>
                        <div class="text-muted" id="genero">
                           <span class="placeholder w-100"></span>
                        </div>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-md-6 text-center">
                        <span class="fw-bold">Correo Electrónico</span>
                        <div class="text-muted" id="correo-electronico">
                           <span class="placeholder w-100"></span>
                        </div>
                     </div>
                     <div class="col-md-6 text-center">
                        <span class="fw-bold">Teléfono</span>
                        <div class="text-muted" id="telefono">
                           <span class="placeholder w-100"></span>
                        </div>
                     </div>
                  </div>
                  <div class="row justify-content-center">
                     <div class="col-md-6 text-center">
                        <span class="fw-bold">Fecha de Nacimiento</span>
                        <div class="text-muted" id="fecha-nacimiento">
                           <span class="placeholder w-100"></span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card-footer text-center">
                  <a href="" class="btn btn-secondary">Volver</a>
               </div>
            </div>
         </div>
      </div>
   </main>
   <?php require_once "comunes/footer.php" ?>
   <script src="assets/js/perfilatleta.js" type="module"></script>
</body>

</html>