<?php if (empty($permisosModulo["leer"])) header("Location: ."); ?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="description"
      content="Gestion de asistencias de atletas en el sistema de gestión para el Gimnasio de Halterofilia 'Eddie Suarez' de la Universidad Politécnica Territorial Andrés Eloy Blanco (UPTAEB).">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Asistencias Diarias - Sistema</title>
   <?php require_once "comunes/linkcss.php"; ?>
   <link rel="stylesheet" href="datatables/datatables.min.css">
   <link rel="stylesheet" href="assets/css/asistencias.css">
</head>

<body class="bg-body">
   <script>
      var actualizar = <?= $permisosModulo["actualizar"] ?>;
      var eliminar = <?= $permisosModulo["eliminar"] ?>;
   </script>
   <?php require_once "comunes/menu.php"; ?>
   <main class="container-md my-3 my-md-5">

      <div class="row">
         <div class="col">
            <div class="card shadow">
               <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                  <h2 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Asistencias Diarias</h2>
                  <div>
                     <?php
                     if ($permisosModulo["eliminar"] === 1):
                     ?>
                        <button type="button" class="btn btn-danger" id="btnEliminarAsistencias">
                           <i class="bi bi-trash me-1"></i> Eliminar asistencias del día
                        </button>
                     <?php
                     endif;
                     ?>
                  </div>
               </div>
               <div class="card-body">
                  <form id="formAsistencias">
                     <div class="row mb-3">
                        <div class="col-md-6">
                           <label for="fechaAsistencia" class="form-label">Fecha:</label>
                           <input type="date" class="form-control" id="fechaAsistencia" name="fechaAsistencia">
                        </div>
                     </div>
                     <div class="table-responsive">
                        <table class="table table-striped table-hover table-sm" id="tablaAsistencias">
                           <thead class="table-dark">
                              <tr>
                                 <th>Cédula</th>
                                 <th>Nombre</th>
                                 <th class="d-none d-md-table-cell">Apellidos</th>
                                 <th>Estado</th>
                                 <th>Hora Entrada</th>
                                 <th>Hora Salida</th>
                                 <th>
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" 
                                          title="Rating of Perceived Exertion (Escala de Borg): 1-2 Muy suave | 3-4 Moderado | 5-6 Duro | 7-8 Muy duro | 9-10 Extremo/Máximo">
                                       RPE <i class="bi bi-info-circle-fill"></i>
                                    </span>
                                 </th>
                                 <th>Tipo Sesión</th>
                                 <th>Observaciones</th>
                              </tr>
                           </thead>
                           <tbody id="listadoAsistencias"></tbody>
                        </table>
                     </div>
                     <?php
                     if ($permisosModulo["crear"] === 1 && $permisosModulo["actualizar"]):
                     ?>
                        <button type="button" class="btn btn-primary mt-3" id="btnGuardarAsistencias">
                           <i class="bi bi-save me-1"></i> Guardar Asistencias
                        </button>
                     <?php
                     endif;
                     ?>
                  </form>
               </div>
            </div>
         </div>
      </div>

   </main>
   <?php require_once "comunes/footer.php"; ?>
   <script src="assets/js/datatables/datatables.min.js"></script>
   <script type="module" src="assets/js/asistencias.js"></script>
</body>

</html>