<?php if (empty($permisosModulo["leer"])) header("Location: ."); ?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registrar Mensualidad - Sistema</title>
   <?php require_once "comunes/linkcss.php"; ?>
   <link rel="stylesheet" href="css/all.min.css">
</head>

<body class="bg-body">
   <?= str_replace('name="_csrf_token"','id="csrf_token_global" name="_csrf_token"',$controller->csrfField()); ?>
   <script>
      var crear = <?= htmlspecialchars($permisosModulo["crear"]) ?>;
      var actualizar = <?= htmlspecialchars($permisosModulo["actualizar"]) ?>;
      var eliminar = <?= htmlspecialchars($permisosModulo["eliminar"]) ?>;
   </script>
   <?php require_once "comunes/menu.php"; ?>
   <main class="container-md my-3 my-md-5">
      <div class="row">
         <div class="col-12 col-lg d-flex align-items-stretch mb-lg-3">
            <div class="card shadow flex-grow-1">
               <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                  <h2 class="mb-0">Deudores</h2>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-striped table-hover" id="tablaDeudores">
                        <thead>
                           <tr>
                              <th>Nombre</th>
                              <th>Cédula</th>
                              <th>Tipo</th>
                              <?= $permisosModulo["crear"] ? "<th id='columnaAccion'>Acción</th>" : "" ?>
                           </tr>
                        </thead>
                        <tbody id="listadoDeudores">
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
         <?php
         if ($permisosModulo["crear"] === 1):
         ?>
            <div class="col-12 col-lg-4 d-lg-flex align-items-lg-stretch mt-3 mt-lg-0 mb-lg-3">
               <div class="card shadow">
                  <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center ">
                     <h2 class="mb-0">Registrar Mensualidad</h2>
                  </div>
                  <div class="card-body">
                     <?php require_once "formularios/registrar_mensualidad.php"; ?>
                  </div>
               </div>
            </div>
         <?php
         endif;
         ?>
      </div>
      <div class="row">
         <div class="col-12 my-3">
            <div class="card shadow">
               <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                  <h2 class="mb-0">Pagos Registrados</h2>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-striped table-hover" id="tablaPagosRegistrados">
                        <thead>
                           <tr>
                              <th>Nombre</th>
                              <th>Cédula</th>
                              <th>Tipo</th>
                              <th>Monto</th>
                              <th>Fecha</th>
                              <th>Detalles</th>
                              <?= $permisosModulo["actualizar"] || $permisosModulo["eliminar"] ? "<th id='columnaAccion'>Acción</th>" : "" ?>
                           </tr>
                        </thead>
                        <tbody id="listadoPagosRegistrados">
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel"
         aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="modalDetallesLabel">Detalles del Pago</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>
   </main>
   <?php require_once "comunes/footer.php"; ?>
   <script type="text/javascript" src="assets/js/datatables/datatables.min.js"></script>
   <script type="module" src="assets/js/mensualidad.js"></script>
</body>

</html>