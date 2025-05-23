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

<body class="d-flex flex-column vh-100">
   <?php require_once "comunes/menu.php"; ?>

   <main>
      <div class="container-lg my-4">
         <div class="row">
            <div class="col-12">
               <div class="card shadow">
                  <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                     <h2 class="mb-0">Generar Reportes</h2>
                  </div>
                  <div class="card-body">
                     <form id="formReportes">
                        <div class="row">
                           <div class="col-md-6 mb-3">
                              <label for="tipoReporte" class="form-label">Tipo de Reporte:</label>
                              <select class="form-select" id="tipoReporte" name="tipoReporte">
                                 <option value="atletas">Atletas</option>
                                 <option value="reporteIndividualAtleta">Atletas (individual)</option>
                                 <option value="entrenadores">Entrenadores</option>
                                 <option value="eventos">Eventos</option>
                                 <option value="mensualidades">Mensualidades</option>
                                 <option value="wada">WADA</option>
                                 <option value="asistencias">Asistencias</option>
                              </select>
                           </div>
                           <div id="filtrosGenerales" class="col-12">
                              <div class="row">
                                 <div class="col-md-6 mb-3">
                                    <label for="fechaInicio" class="form-label">Fecha de Inicio:</label>
                                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                                 </div>
                                 <div class="col-md-6 mb-3">
                                    <label for="fechaFin" class="form-label">Fecha de Fin:</label>
                                    <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                                 </div>
                              </div>
                           </div>

                           <div id="filtrosAtletas" class="col-12 filtros-reporte">
                              <div class="row">
                                 <div class="col-md-6 mb-3">
                                    <label for="edadMin" class="form-label">Edad Mínima:</label>
                                    <input type="number" class="form-control" id="edadMin" name="edadMin">
                                 </div>
                                 <div class="col-md-6 mb-3">
                                    <label for="edadMax" class="form-label">Edad Máxima:</label>
                                    <input type="number" class="form-control" id="edadMax" name="edadMax">
                                 </div>
                                 <div class="col-md-6 mb-3">
                                    <label for="genero" class="form-label">Género:</label>
                                    <select class="form-select" id="genero" name="genero">
                                       <option value="">Todos</option>
                                       <option value="Masculino">Masculino</option>
                                       <option value="Femenino">Femenino</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <!-- Filtros para el reporte individual -->
                           <div id="filtrosIndividualAtleta" class="col-12 filtros-reporte" style="display: none;">
                              <div class="row">
                                 <div class="col-md-6 mb-3">
                                    <label for="cedulaAtleta" class="form-label">Cédula del Atleta:</label>
                                    <input type="text" class="form-control" id="cedulaAtleta" name="cedulaAtleta">
                                 </div>
                                 <div class="col-md-6 mb-3">
                                    <label for="fechaInicio" class="form-label">Fecha de Inicio:</label>
                                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                                 </div>
                                 <div class="col-md-6 mb-3">
                                    <label for="fechaFin" class="form-label">Fecha de Fin:</label>
                                    <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12 mb-3">
                                    <label class="form-label">Selecciona los datos a incluir en el reporte:</label>
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

                           <div id="filtrosEntrenadores" class="col-12 filtros-reporte">
                              <div class="row">
                                 <div class="col-md-6 mb-3">
                                    <label for="gradoInstruccion" class="form-label">Grado de Instrucción:</label>
                                    <select class="form-select" id="gradoInstruccion" name="gradoInstruccion">
                                       <option value="">Todos</option>
                                       <option value="Primaria">Primaria</option>
                                       <option value="Secundaria">Secundaria</option>
                                       <option value="Universidad">Universidad</option>
                                    </select>
                                 </div>
                              </div>
                           </div>

                           <div class="col-12">
                              <button type="button" class="btn btn-primary" id="btnGenerarReporte">Generar
                                 Reporte</button>
                           </div>
                        </div>
                     </form>
                     <div class="table-responsive mt-4">
                        <table class="table table-striped table-hover" id="tablaReportes">
                           <thead>
                              <tr>
                                 <th>ID</th>
                                 <th>Nombre</th>
                                 <th>Detalles</th>
                                 <th>Fecha</th>
                              </tr>
                           </thead>
                           <tbody id="listadoReportes"></tbody>
                        </table>
                     </div>
                     <div id="estadisticasReporte" class="mt-4">
                        <h3>Estadísticas Generadas</h3>
                        <ul id="listaEstadisticas"></ul>
                     </div>

                     <button class="btn btn-success mt-3" id="btnDescargarPDF">Descargar PDF</button>
                  </div>
               </div>
            </div>
         </div>

         <div class="row my-4">
            <div class="col-12">
               <h3>Reportes Estadísticos</h3>
               <div class="card shadow mb-4">
                  <div style="display: flex; justify-content: space-between;">
                     <div class="card-body" style="width:60%; height:100%">
                        <h4>Distribución de Edad de Atletas</h4>
                        <canvas id="edadAtletasChart"></canvas>
                     </div>
                     <div class="card-body" style="width:30%">
                        <h4>Proporción de Género</h4>
                        <canvas id="generoChart"></canvas>
                     </div>
                  </div>
               </div>
               <div class="card shadow mb-4">

               </div>
               <div class="card shadow mb-4">
                  <div style="display: flex; justify-content: space-between;">
                     <div class="card-body" style="width:50%; ">
                        <h4>Progreso de Asistencias Mensuales</h4>
                        <canvas id="asistenciasChart"></canvas>
                     </div>
                     <div class="card-body" style="width:20%; ">
                        <h4>Cumplimiento de WADA</h4>
                        <canvas id="wadaChart"></canvas>
                     </div>
                  </div>
               </div>
               <div class="card shadow mb-4">
                  <div class="card-body">
                     <h4>Pruebas WADA Próximas a Vencer</h4>
                     <table class="table table-striped" id="tablaVencimientos">
                        <thead>
                           <tr>
                              <th>Atleta</th>
                              <th>Fecha de Vencimiento</th>
                           </tr>
                        </thead>
                        <tbody></tbody>
                     </table>
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