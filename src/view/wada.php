<?php if (empty($permisosModulo["leer"])) header("Location: ."); ?>
<?php $formulario = "wada"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description"
      content="Gestion de WADA de los atletas en el sistema de gestión para el Gimnasio de Halterofilia 'Eddie Suarez' de la Universidad Politécnica Territorial Andrés Eloy Blanco (UPTAEB).">
   <title>Registrar Status WADA - Sistema</title>
   <?php require_once "comunes/linkcss.php"; ?>
</head>

<body class="bg-body">
   <script>
      var actualizar = <?= $permisosModulo["actualizar"] ?>;
      var eliminar = <?= $permisosModulo["eliminar"] ?>;
   </script>
   <?php require_once "comunes/menu.php"; ?>
   <main class="container-md my-3 my-md-5">
      <div class="row">
         <div class="col-12 col-xxl-8">
            <div class="card shadow ">
               <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                  <h2 class="mb-0">Gestionar WADA</h2>
                  <?php
                  if ($permisosModulo["crear"] === 1):
                  ?>
                     <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modal">
                        Registrar+
                     </button>
                  <?php
                  endif;
                  ?>
               </div>
               <div class="card-body">
                  <h3 class="text-center mb-2">Atletas Registrados en <attr title="Agencia Mundial Anti-dopaje">WADA</attr>
                  </h3>
                  <div class="table-responsive">
                     <table class="table table-striped table-hover" id="tablaWada">
                        <thead>
                           <tr>
                              <th class="d-none">Cedula</th>
                              <th>Atleta</th>
                              <th>Status</th>
                              <th class="d-none d-xxl-table-cell">Inscrito</th>
                              <th class="d-none d-md-table-cell">Última Actualización</th>
                              <th>Vencimiento</th>
                              <th>Acción</th>
                           </tr>
                        </thead>
                        <tbody id="listado"></tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-12 col-xxl-4 mt-3 mt-xxl-0">
            <div class="card shadow">
               <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                  <h2 class="text-center mb-0">Próximos a Vencer</h2>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-striped table-hover" id="tablaProximosVencer">
                        <thead>
                           <tr>
                              <th class="d-none">Cedula</th>
                              <th>Atleta</th>
                              <th>Vencimiento</th>
                              <th>Acción</th>
                           </tr>
                        </thead>
                        <tbody></tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php require_once "comunes/modal.php"; ?>
   </main>
   <?php require_once "comunes/footer.php"; ?>
   <script src="assets/js/datatables/datatables.min.js"></script>
   <script type="module" src="assets/js/wada.js"></script>
</body>

</html>