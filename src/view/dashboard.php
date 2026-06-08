<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard - Sistema</title>
   <?php require_once "comunes/linkcss.php"; ?>
   <link rel="stylesheet" href="assets/css/all.min.css">
</head>

<body class="bg-body dashboard-page">
   <?php require_once "comunes/menu.php"; ?>
    <main class="dashboard-main mb-4">
       <div class="container-lg dashboard-shell mt-4">
          <!-- KPI Cards -->
          <div class="row g-4 mb-4 dashboard-kpi-grid">
             <!-- Atletas -->
             <div class="col-12 col-sm-6 col-lg-3">
                <div class="card text-white bg-primary stat-card h-100">
                   <div class="card-header"><i class="fas fa-users me-2"></i>Atletas</div>
                   <div class="card-body">
                      <h5 class="card-title" id="atletasRegistrados">-</h5>
                      <small class="card-text">Total de atletas registrados.</small>
                   </div>
                </div>
             </div>
             <!-- Entrenadores -->
             <div class="col-12 col-sm-6 col-lg-3">
                <div class="card text-white bg-success stat-card h-100">
                   <div class="card-header"><i class="fas fa-user-friends me-2"></i>Entrenadores</div>
                   <div class="card-body">
                      <h5 class="card-title" id="entrenadoresRegistrados">-</h5>
                      <small class="card-text">Entrenadores registrados.</small>
                   </div>
                </div>
             </div>
             <!-- Mensualidades -->
             <div class="col-12 col-sm-6 col-lg-3">
                <div class="card text-white bg-warning stat-card h-100">
                   <div class="card-header"><i class="fas fa-file-invoice me-2"></i>Mensualidades</div>
                   <div class="card-body">
                      <h5 class="card-title" id="mensualidadesPendientes">-</h5>
                      <small class="card-text">Mensualidades pendientes.</small>
                   </div>
                </div>
             </div>
             <!-- WADA -->
             <div class="col-12 col-sm-6 col-lg-3">
                <div class="card text-white bg-danger stat-card h-100">
                   <div class="card-header"><i class="fas fa-cogs me-2"></i>WADA</div>
                   <div class="card-body">
                      <h5 class="card-title" id="wadasPendientes">-</h5>
                      <small class="card-text">WADAS pendientes.</small>
                   </div>
                </div>
             </div>
          </div>

          <!-- Charts Section -->
           <div class="row g-4 mb-4 dashboard-chart-grid">
             <div class="col-12 col-md-4">
                <div class="chart-card h-100">
                   <h6 class="card-title mb-3"><i class="fas fa-chart-line me-2"></i>Estadísticas de asistencias</h6>
                   <canvas id="asistenciasChart"></canvas>
                </div>
             </div>
             <div class="col-12 col-md-4">
                <div class="chart-card h-100">
                   <h6 class="card-title mb-3"><i class="fas fa-shield-alt me-2"></i>Vigencias de WADA</h6>
                   <canvas id="wadaChart"></canvas>
                </div>
             </div>
             <div class="col-12 col-md-4">
                <div class="chart-card h-100">
                   <h6 class="card-title mb-3"><i class="fas fa-venus-mars me-2"></i>Género predominante</h6>
                   <canvas id="generoChart" class="w-75 h-auto mx-auto d-block"></canvas>
                </div>
             </div>
          </div>

          <!-- Recent Activity Section -->
           <div class="row g-4 dashboard-activity-grid">
             <div class="col-12 col-lg-6">
                <div class="card module-card h-100">
                   <div class="card-header d-flex justify-content-between align-items-center">
                      <div>
                         <i class="fas fa-bell me-2"></i>Notificaciones Recientes
                      </div>
                      <a href="#" id="verNotificacionesRecientes" title="Ir a Notificaciones" role="button" data-bs-target="#menuNotificaciones" data-tooltip="tooltip" data-bs-placement="top" class="text-decoration-none text-body"><i class="fas fa-eye"></i></a>
                   </div>
                   <ul class="list-group list-group-flush" id="ultimas_notificaciones">
                   </ul>
                </div>
             </div>
             <div class="col-12 col-lg-6">
                <div class="card module-card h-100">
                   <div class="card-header d-flex justify-content-between align-items-center">
                      <div>
                         <i class="fas fa-history me-2"></i>Actividad Reciente
                      </div>
                      <?php if (!empty($permisosNav[8]["leer"])): ?>
                         <a href="?p=bitacora" title="Ver Bitácora" data-tooltip="tooltip" data-bs-placement="top" class="text-decoration-none text-body"><i class="fas fa-angle-right"></i></a>
                      <?php endif; ?>
                   </div>
                   <ul class="list-group list-group-flush" id="ultimas_acciones">
                   </ul>
                </div>
             </div>
             <div class="col-12 col-lg-7">
                <div class="card module-card h-100">
                   <div class="card-header d-flex justify-content-between align-items-center">
                      <div>
                         <i class="fas fa-table me-2"></i>Mensualidades Pendientes
                      </div>
                      <?php if (!empty($permisosNav[5]["leer"])): ?>
                         <a href="?p=mensualidad" title="Ir a Mensualidades" data-tooltip="tooltip" data-bs-placement="top" class="text-decoration-none text-body"><i class="fas fa-angle-right"></i></a>
                      <?php endif; ?>
                   </div>
                   <div class="card-body table-responsive">
                      <table class="table table-hover" id="tablaMensualidadesPendientes">
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
             <div class="col-12 col-lg-5">
                <div class="card module-card h-100">
                   <div class="card-header d-flex justify-content-between align-items-center">
                      <div>
                         <i class="fas fa-address-card me-2"></i>WADAs por vencer
                      </div>
                      <?php if (!empty($permisosNav[6]["leer"])): ?>
                         <a href="?p=wada" title="Ir a Wadas" data-tooltip="tooltip" data-bs-placement="top" class="text-decoration-none text-body"><i class="fas fa-angle-right"></i></a>
                      <?php endif; ?>
                   </div>
                   <div class="card-body table-responsive">
                      <table class="table table-hover" id="tablaWadasPendientes">
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
       </div>
    </main>
   <?php require_once "comunes/footer.php"; ?>
   <script src="assets/js/chart.min.js" defer></script>
   <script type="module" src="assets/js/dashboard.js"></script>
</body>

</html>
