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
   <?= str_replace('name="_csrf_token"','id="csrf_token_global" name="_csrf_token"',$controller->csrfField()); ?>
   <script>
      var actualizar = <?= $permisosModulo["actualizar"] ?>;
      var eliminar = <?= $permisosModulo["eliminar"] ?>;
   </script>
   <?php require_once "comunes/menu.php"; ?>
    <main class="container-md my-3 my-md-5">
       <div class="row g-4">
          <div class="col-12 col-xxl-8">
             <div class="card shadow module-card">
                <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white module-header">
                   <h2 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Gestionar WADA</h2>
                   <?php
                   if ($permisosModulo["crear"] === 1):
                   ?>
                      <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modal">
                         <i class="fas fa-plus me-2"></i>Registrar
                      </button>
                   <?php
                   endif;
                   ?>
                </div>
                <div class="card-body">
                   <h3 class="text-center mb-4 section-title">Atletas Registrados en <abbr title="Agencia Mundial Anti-dopaje">WADA</abbr></h3>
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
          <div class="col-12 col-xxl-4">
             <div class="card shadow module-card">
                <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white module-header">
                   <h2 class="text-center mb-0 w-100"><i class="fas fa-exclamation-triangle me-2"></i>Próximos a Vencer</h2>
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