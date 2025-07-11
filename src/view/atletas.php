<?php if (empty($permisosModulo["leer"]))
   header("Location: ."); ?>
<?php $formulario = "atletas"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description"
      content="Gestion de atletas en el sistema de gestión para el Gimnasio de Halterofilia 'Eddie Suarez' de la Universidad Politécnica Territorial Andrés Eloy Blanco (UPTAEB).">
   <title>Inscripción de Atletas - Sistema</title>
   <?php require_once "comunes/linkcss.php"; ?>
</head>

<body class="bg-body">
   <?= str_replace('name="_csrf_token"','id="csrf_token_global" name="_csrf_token"',$controller->csrfField()); ?>

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
                  <h2 class="mb-0">Gestionar Atletas</h2>
                  <?php if (!empty($permisosModulo["crear"])): ?>
                     <div>
                        <!-- <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalRepresentantes">
                           Representantes
                        </button> -->

                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modal">
                           Registrar <i class="fa-solid fa-plus"></i>
                        </button>
                     </div>
                  <?php endif; ?>
               </div>
               <div class="card-body">
                  <h3 class="text-center mb-2">Atletas Inscritos</h3>
                  <div class="table-responsive">
                     <table class="table table-striped table-hover" id="tablaatleta">
                        <thead>
                           <tr>
                              <th>Cédula</th>
                              <th>Nombre</th>
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
   </main>
   <?php require_once "comunes/modal_tipos_atletas.php"; ?>
   <?php require_once "comunes/modal.php"; ?>
   <?php require_once "comunes/modal_representantes.php"; ?>
   <?php require_once "comunes/footer.php"; ?>
   <script type="text/javascript" src="assets/js/datatables/datatables.min.js"></script>
   <script type="module" src="assets/js/atletas.js"></script>
   
</body>

</html>