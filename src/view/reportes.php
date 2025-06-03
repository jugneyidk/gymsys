<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reportes - Sistema</title>
   <?php require_once "comunes/linkcss.php"; ?>
   <link rel="stylesheet" href="assets/css/datatables.min.css">
   <script src="assets/js/chart.min.js" defer></script>
</head>

<body class="bg-body-tertiary">
   <?php require_once "comunes/menu.php"; ?>

   <main class="flex-grow-1 py-4">
      <div class="container-lg px-4">
         <!-- Sección de Generación de Reportes -->
         <div class="row mb-4">
            <div class="col-12">
               <div class="card shadow-sm border-0 rounded-3">
                  <div class="card-header bg-dark text-white py-3">
                     <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Generación de Reportes</h2>
                        <?php if ($permisosModulo["crear"]): ?>
                           <button class="btn btn-light btn-sm" id="btnDescargarPDF">
                              <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                           </button>
                        <?php endif; ?>
                     </div>
                  </div>

                  <div class="card-body">
                     <form id="formReportes" class="needs-validation" novalidate>
                        <div class="row g-3">
                           <!-- Selector de Tipo de Reporte -->
                           <div class="col-md-6">
                              <div class="form-floating">
                                 <select class="form-select" id="tipoReporte" name="tipoReporte" required>
                                    <option value="atletas">Atletas</option>
                                    <option value="reporteIndividualAtleta">Atletas (individual)</option>
                                    <option value="entrenadores">Entrenadores</option>
                                    <option value="eventos">Eventos</option>
                                    <option value="mensualidades">Mensualidades</option>
                                    <option value="wada">WADA</option>
                                    <option value="asistencias">Asistencias</option>
                                 </select>
                                 <label for="tipoReporte">Tipo de Reporte</label>
                              </div>
                           </div>

                           <!-- Filtros Generales -->
                           <div id="filtrosGenerales" class="col-12">
                              <div class="row g-3">
                                 <div class="col-md-6">
                                    <div class="form-floating">
                                       <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                                       <label for="fechaInicio">Fecha de Inicio</label>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-floating">
                                       <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                                       <label for="fechaFin">Fecha de Fin</label>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <!-- Filtros de Atletas -->
                           <div id="filtrosAtletas" class="col-12 filtros-reporte">
                              <div class="row g-3">
                                 <div class="col-md-4">
                                    <div class="form-floating">
                                       <input type="number" class="form-control" id="edadMin" name="edadMin">
                                       <label for="edadMin">Edad Mínima</label>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-floating">
                                       <input type="number" class="form-control" id="edadMax" name="edadMax">
                                       <label for="edadMax">Edad Máxima</label>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-floating">
                                       <select class="form-select" id="genero" name="genero">
                                          <option value="">Todos</option>
                                          <option value="Masculino">Masculino</option>
                                          <option value="Femenino">Femenino</option>
                                       </select>
                                       <label for="genero">Género</label>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <!-- Filtros de Reporte Individual -->
                           <div id="filtrosIndividualAtleta" class="col-12 filtros-reporte" style="display: none;">
                              <div class="row g-3">
                                 <div class="col-md-12">
                                    <div class="form-floating">
                                       <input type="text" class="form-control" id="cedulaAtleta" name="cedulaAtleta">
                                       <label for="cedulaAtleta">Cédula del Atleta</label>
                                    </div>
                                 </div>

                                 <div class="col-12">
                                    <div class="card border">
                                       <div class="card-body">
                                          <h6 class="card-subtitle mb-3 text-muted">Datos a incluir en el reporte</h6>
                                          <div class="d-flex flex-wrap gap-3">
                                             <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAsistencias" name="datosReporte[]" value="asistencias">
                                                <label class="form-check-label" for="checkAsistencias">Asistencias</label>
                                             </div>
                                             <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkCampeonatos" name="datosReporte[]" value="campeonatos">
                                                <label class="form-check-label" for="checkCampeonatos">Participación en Campeonatos</label>
                                             </div>
                                             <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkMensualidades" name="datosReporte[]" value="mensualidades">
                                                <label class="form-check-label" for="checkMensualidades">Pago de Mensualidades</label>
                                             </div>
                                             <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkWADA" name="datosReporte[]" value="wada">
                                                <label class="form-check-label" for="checkWADA">Cumplimiento WADA</label>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <!-- Filtros de Entrenadores -->
                           <div id="filtrosEntrenadores" class="col-12 filtros-reporte" style="display: none;">
                              <div class="form-floating">
                                 <select class="form-select" id="gradoInstruccion" name="gradoInstruccion">
                                    <option value="">Todos</option>
                                    <option value="Primaria">Primaria</option>
                                    <option value="Secundaria">Secundaria</option>
                                    <option value="Universidad">Universidad</option>
                                 </select>
                                 <label for="gradoInstruccion">Grado de Instrucción</label>
                              </div>
                           </div>

                           <div class="col-12">
                              <button type="button" class="btn btn-primary" id="btnGenerarReporte">
                                 <i class="fas fa-chart-line me-2"></i>Generar Reporte
                              </button>
                           </div>
                        </div>
                     </form>

                     <!-- Resultados del Reporte -->
                     <div class="mt-4" style="display: none;" id="resultadosReporte">
                        <div class="card border">
                           <div class="card-body">
                              <h5 class="card-title">Resultados</h5>
                              <div class="table-responsive">
                                 <table class="table table-hover" id="tablaReportes">
                                    <thead>
                                    </thead>
                                    <tbody id="listadoReportes"></tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>

                     <!-- Estadísticas -->
                     <div id="estadisticasReporte" class="mt-4" style="display: none;">
                        <div class="card border">
                           <div class="card-body">
                              <h5 class="card-title">Estadísticas Generadas</h5>
                              <ul id="listaEstadisticas" class="list-group list-group-flush"></ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <!-- Sección de Gráficos -->
         <div class="row g-4">
            <div class="col-12">
               <h5 class="mb-4">Reportes Estadísticos</h5>
            </div>

            <!-- Gráficos de Distribución -->
            <div class="col-lg-8">
               <div class="card shadow-sm border-0 h-100">
                  <div class="card-body">
                     <h6 class="card-title">Distribución de Edad de Atletas</h6>
                     <canvas id="edadAtletasChart"></canvas>
                  </div>
               </div>
            </div>

            <div class="col-lg-4">
               <div class="card shadow-sm border-0 h-100">
                  <div class="card-body">
                     <h6 class="card-title">Proporción de Género</h6>
                     <canvas id="generoChart"></canvas>
                  </div>
               </div>
            </div>

            <!-- Gráficos de Progreso -->
            <div class="col-lg-8">
               <div class="card shadow-sm border-0 h-100">
                  <div class="card-body">
                     <h6 class="card-title">Progreso de Asistencias Mensuales</h6>
                     <canvas id="asistenciasChart"></canvas>
                  </div>
               </div>
            </div>

            <div class="col-lg-4">
               <div class="card shadow-sm border-0 h-100">
                  <div class="card-body">
                     <h6 class="card-title">Cumplimiento de WADA</h6>
                     <canvas id="wadaChart"></canvas>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </main>

   <?php require_once "comunes/footer.php"; ?>
   <script src="assets/js/datatables/datatables.min.js"></script>
   <script type="module" src="assets/js/reportes.js"></script>
</body>

</html>