<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard - Sistema</title>
   <?php require_once "comunes/linkcss.php"; ?>
   <link rel="stylesheet" href="assets/css/all.min.css">
</head>

<body class="bg-body">
   <?php require_once "comunes/menu.php"; ?>
   <main class="mb-4">
      <div class="container-lg mt-4">
         <div class="row justify-content-center">
            <div class="container-lg mt-4">
               <div class="row">
                  <!-- Atletas -->
                  <div class="col-12 col-sm-6 col-lg-3 mb-3 d-flex align-items-stretch">
                     <div class="card text-white bg-primary flex-grow-1">
                        <div class="card-header"><i class="fas fa-users"></i> Atletas</div>
                        <div class="card-body">
                           <h5 class="card-title" id="atletasRegistrados">-</h5>
                           <small class="card-text">Total de atletas registrados.</small>
                        </div>
                     </div>
                  </div>
                  <!-- Entrenadores -->
                  <div class="col-12 col-sm-6 col-lg-3 mb-3 d-flex align-items-stretch">
                     <div class="card text-white bg-success flex-grow-1">
                        <div class="card-header"><i class="fas fa-user-friends"></i> Entrenadores</div>
                        <div class="card-body">
                           <h5 class="card-title" id="entrenadoresRegistrados">-</h5>
                           <small class="card-text">Entrenadores registrados.</small>
                        </div>
                     </div>
                  </div>
                  <!-- Reportes -->
                  <div class="col-12 col-sm-6 col-lg-3 mb-3 d-flex align-items-stretch">
                     <div class="card text-white bg-warning flex-grow-1">
                        <div class="card-header"><i class="fas fa-file-invoice"></i> Mensualidades</div>
                        <div class="card-body">
                           <h5 class="card-title" id="mensualidadesPendientes">-</h5>
                           <small class="card-text">Mensualidades pendientes.</small>
                        </div>
                     </div>
                  </div>

                  <!-- WADA -->
                  <div class="col-12 col-sm-6 col-lg-3 mb-3 d-flex align-items-stretch">
                     <div class="card text-white bg-danger flex-grow-1">
                        <div class="card-header"><i class="fas fa-cogs"></i> WADA</div>
                        <div class="card-body">
                           <h5 class="card-title" id="wadasPendientes">-</h5>
                           <small class="card-text">WADAS pendientes.</small>
                        </div>
                     </div>
                  </div>

               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-12 col-md-4 col-lg-4">
               <h5>Estadísticas de asistencias</h5>
               <canvas id="asistenciasChart"></canvas>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
               <h5>Vigencias de WADA</h5>
               <canvas id="wadaChart"></canvas>
            </div>
            <div class="col-12 col-lg-4">
               <h5>Genero predominante</h5>
               <canvas id="generoChart" class="w-50 h-auto mx-auto"></canvas>
            </div>
         </div>
         <div class="row">
            <div class="col-12 col-lg-6 d-flex align-self-stretch my-4">
               <div class="card w-100">
                  <div class="card-header d-flex justify-content-between border-0 bg-body">
                     <div>
                        <i class="fas fa-bell"></i> Notificaciones Recientes
                     </div>
                     <a href="#" id="verNotificacionesRecientes" title="Ir a Notificaciones" role="button" data-bs-target="#menuNotificaciones" data-tooltip="tooltip" data-bs-placement="top" class="text-decoration-none text-body"><i class="fas fa-eye my-auto"></i></a>
                  </div>
                  <ul class="list-group list-group-flush card-body p-0 d-flex justify-content-center" id="ultimas_notificaciones">
                  </ul>
               </div>
            </div>
            <div class="col-12 col-lg-6 d-flex align-self-stretch my-4">
               <div class="card w-100 bg-body">
                  <div class="card-header d-flex justify-content-between border-0 bg-body">
                     <div>
                        <i class="fas fa-history"></i> Actividad Reciente
                     </div>
                     <?php if (!empty($permisosNav[8]["leer"])): ?>
                        <a href="?p=bitacora" title="Ver Bitácora" data-tooltip="tooltip" data-bs-placement="top" class="text-decoration-none text-body"><i class="fas fa-angle-right my-auto"></i></a>
                     <?php endif; ?>
                  </div>
                  <ul class="list-group list-group-flush card-body p-0 d-flex justify-content-center" id="ultimas_acciones">
                  </ul>
               </div>
            </div>
            <div class="col col-lg-7 d-flex align-self-stretch my-4">
               <div class="card w-100">
                  <div class="card-header d-flex justify-content-between border-0 bg-body">
                     <div>
                        <i class="fas fa-table"></i> Mensualidades Pendientes
                     </div>
                     <?php if (!empty($permisosNav[5]["leer"])): ?>
                        <a href="?p=mensualidad" title="Ir a Mensualidades" data-tooltip="tooltip" data-bs-placement="top" class="text-decoration-none text-body"><i class="fas fa-angle-right my-auto"></i></a>
                     <?php endif; ?>
                  </div>
                  <div class="card-body table-responsive">
                     <table class="table" id="tablaMensualidadesPendientes">
                        <thead>
                           <tr>
                              <th>Cedula</th>
                              <th>Nombre</th>
                              <th>Monto</th>
                           </tr>
                        </thead>
                        <tbody>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <div class="col col-lg-5 d-flex align-self-stretch my-4">
               <div class="card w-100">
                  <div class="card-header d-flex justify-content-between border-0 bg-body">
                     <div>
                        <i class="fas fa-address-card"></i> WADAs por vencer
                     </div>
                     <?php if (!empty($permisosNav[6]["leer"])): ?>
                        <a href="?p=wada" title="Ir a Wadas" data-tooltip="tooltip" data-bs-placement="top" class="text-decoration-none text-body"><i class="fas fa-angle-right my-auto"></i></a>
                     <?php endif; ?>
                  </div>
                  <div class="card-body table-responsive">
                     <table class="table" id="tablaWadasPendientes">
                        <thead>
                           <tr>
                              <th>Nombre</th>
                              <th>Fecha vencimiento</th>
                           </tr>
                        </thead>
                        <tbody>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
   </main>
   <?php require_once "comunes/footer.php"; ?>
   <script src="assets/js/chart.min.js" defer></script>
   <script type="module" src="assets/js/dashboard.js"></script>
</body>

</html>