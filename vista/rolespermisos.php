<?php $formulario = "rolespermisos"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="description"
      content="Gestion de roles y permisos en el sistema de gestión para el Gimnasio de Halterofilia 'Eddie Suarez' de la Universidad Politécnica Territorial Andrés Eloy Blanco (UPTAEB).">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Roles y Permisos - Sistema</title>
   <?php require_once("comunes/linkcss.php"); ?>
</head>

<body class="bg-light">
   <script>
      var actualizar = <?php echo $permisos["actualizar"] ?>;
      var eliminar = <?php echo $permisos["eliminar"] ?>;
   </script>
   <?php require_once("comunes/menu.php"); ?>
   <main class="container-md my-3 my-md-5">
      <div class="row">
         <div class="col">
            <div class="card shadow">
               <div
                  class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                  <h2 class="mb-0">Gestionar Roles y Permisos</h2>
                  <div>
                     <?php
                     if ($permisos["actualizar"] === 1):
                     ?>
                        <button type="button" class="btn btn-outline-light" data-bs-toggle="modal"
                           data-bs-target="#modalAsignarRol" id="btnAsignarRol">
                           Asignar Rol
                        </button>
                     <?php
                     endif;
                     ?>
                     <?php
                     if ($permisos["crear"] === 1):
                     ?>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal"
                           data-bs-target="#modal" id="btnCrearRol">
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
      if ($permisos["actualizar"] === 1 || $permisos["crear"] === 1):
         require_once("comunes/modal.php");
      ?>
         <div class="modal fade" id="modalAsignarRol" tabindex="-1" aria-labelledby="modalAsignarRolLabel"
            aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="modalAsignarRolLabel">Asignar Rol a usuario</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <form id="f1">
                        <div class="container">
                           <div class="row">
                              <div class="col mb-3">
                                 <label for="nombre_tipo_atleta" class="form-label">Usuario:</label>
                                 <input type="text" class="form-control" id="nombre_tipo_atleta" name="nombre_tipo_atleta">
                                 <div id="snombre_tipo_atleta" class="invalid-feedback"></div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col mb-3">
                                 <label for="rol" class="form-label">Rol</label>
                                 <select name="rol" id="rol">
                                    <option value="">-</option>
                                 </select>
                              </div>
                           </div>
                           <div class="row">
                              <button type="button" id="btnRegistrarTipoAtleta" class="btn btn-primary btn-block">Registrar</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      <?php endif; ?>
   </main>
   <br>
   <?php require_once("comunes/footer.php"); ?>
   <script type="text/javascript" src="datatables/datatables.min.js"></script>
   <script type="module" src="js/rolespermisos.js"></script>
</body>

</html>