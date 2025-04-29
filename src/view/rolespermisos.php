<?php $formulario = "rolespermisos"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="description"
      content="Gestion de roles y permisos en el sistema de gestión para el Gimnasio de Halterofilia 'Eddie Suarez' de la Universidad Politécnica Territorial Andrés Eloy Blanco (UPTAEB).">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Roles y Permisos - Sistema</title>
   <?php require_once "comunes/linkcss.php"; ?>
</head>

<body class="bg-light">
   <script>
      var actualizar = <?= $permisosModulo["actualizar"] ?>;
      var eliminar = <?= $permisosModulo["eliminar"] ?>;
   </script>
   <?php require_once "comunes/menu.php" ; ?>
   <main class="container-md my-3 my-md-5">
      <div class="row">
         <div class="col">
            <div class="card shadow">
               <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                  <h2 class="mb-0">Gestionar Roles y Permisos</h2>
                  <div>
                     <?php
                     if ($permisosModulo["actualizar"] === 1):
                        ?>
                        <button type="button" class="btn btn-outline-light" data-bs-toggle="modal"
                           data-bs-target="#modalAsignarRol" id="btnAsignarRol">
                           Asignar Rol
                        </button>
                        <?php
                     endif;
                     ?>
                     <?php
                     if ($permisosModulo["crear"] === 1):
                        ?>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modal"
                           id="btnCrearRol">
                           Crear Rol+
                        </button>
                        <?php
                     endif;
                     ?>
                  </div>
               </div>
               <div class="card-body">
                  <h3 class="text-center mb-2">Roles</h3>
                  <div class="table-responsive">
                     <table class="table table-striped table-hover" id="tablaroles">
                        <thead>
                           <tr>
                              <th class="d-none">Id</th>
                              <th>Rol</th>
                              <th>Acción</th>
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
      <?php
      if ($permisosModulo["actualizar"] === 1 || $permisosModulo["crear"] === 1) {
         require_once "comunes/modal.php";
         require_once "comunes/modal_asignar_rol.php";
      } ?>
   </main>
   <br>
   <?php require_once "comunes/footer.php"; ?>
   <script type="text/javascript" src="assets/js/datatables/datatables.min.js"></script>
   <script type="module" src="assets/js/rolespermisos.js"></script>
</body>

</html>