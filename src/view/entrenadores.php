<?php $formulario = "entrenadores"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Inscripción de Entrenadores - Sistema</title>
   <meta name="description"
      content="Gestion de entrenadores en el sistema de gestión para el Gimnasio de Halterofilia 'Eddie Suarez' de la Universidad Politécnica Territorial Andrés Eloy Blanco (UPTAEB).">
   <?php require_once "comunes/linkcss.php"; ?>
</head>

<body class="bg-light">
   <script>
      var actualizar = <?= $permisosModulo["actualizar"] ?? 0 ?>;
      var eliminar = <?= $permisosModulo["eliminar"] ?? 0 ?>;
   </script>
   <?php require_once "comunes/menu.php"; ?>
   <main class="container-md my-3 my-md-5">
      <div class="row">
         <div class="col">
            <div class="card shadow">
               <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                  <h2 class="mb-0">Gestionar Entrenadores</h2>
                  <?php
                  if (!empty($permisosModulo["crear"])):
                  ?>
                     <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modal">
                        Registrar <i class="fa-solid fa-plus"></i>
                     </button>
                  <?php
                  endif;
                  ?>
               </div>
               <div class="card-body">
                  <h3 class="text-center mb-2">Entrenadores Inscritos</h3>
                  <div class="table-responsive">
                     <table class="table table-striped table-hover" id="tablaentrenador">
                        <thead>
                           <tr>
                              <th>Cédula</th>
                              <th>Nombre</th>
                              <th class="d-none d-md-table-cell">Teléfono</th>
                              <?= !empty($permisosModulo['actualizar']) || !empty($permisosModulo['eliminar']) ? "<th>Acción</th>" : null ?>
                           </tr>
                        </thead>
                        <tbody id="listado">
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php require "comunes/modal.php"; ?>
   </main>
   <?php require_once "comunes/footer.php"; ?>
   <script type="text/javascript" src="assets/js/datatables/datatables.min.js"></script>
   <script type="module" src="assets/js/entrenadores.js"></script>
</body>

</html>