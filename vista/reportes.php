<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
    <link rel="stylesheet" href="datatables/datatables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="d-flex flex-column vh-100">
    <?php require_once("comunes/menu.php"); ?>

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
                                            <option value="entrenadores">Entrenadores</option>
                                            <option value="eventos">Eventos</option>
                                            <option value="mensualidades">Mensualidades</option>
                                            <option value="wada">WADA</option>
                                            <option value="asistencias">Asistencias</option>
                                        </select>
                                    </div>
                                
                                    <div id="filtrosAtletas" class="col-12">
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
                                            <div class="col-md-6 mb-3">
                                                <label for="tipoAtleta" class="form-label">Tipo de Atleta:</label>
                                                <select class="form-select" id="tipoAtleta" name="tipoAtleta">
                                                    <option value="">Todos</option>
                                                    <option value="1">Tipo 1</option>
                                                    <option value="2">Tipo 2</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="pesoMin" class="form-label">Peso Mínimo:</label>
                                                <input type="number" class="form-control" id="pesoMin" name="pesoMin">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="pesoMax" class="form-label">Peso Máximo:</label>
                                                <input type="number" class="form-control" id="pesoMax" name="pesoMax">
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div id="filtrosEntrenadores" class="col-12">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="edadMinEntrenador" class="form-label">Edad Mínima:</label>
                                                <input type="number" class="form-control" id="edadMinEntrenador" name="edadMinEntrenador">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="edadMaxEntrenador" class="form-label">Edad Máxima:</label>
                                                <input type="number" class="form-control" id="edadMaxEntrenador" name="edadMaxEntrenador">
                                            </div>
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
                                 
                                    <div id="filtrosEventos" class="col-12">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="fechaInicioEventos" class="form-label">Fecha de Inicio:</label>
                                                <input type="date" class="form-control" id="fechaInicioEventos" name="fechaInicioEventos">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="fechaFinEventos" class="form-label">Fecha de Fin:</label>
                                                <input type="date" class="form-control" id="fechaFinEventos" name="fechaFinEventos">
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div id="filtrosMensualidades" class="col-12">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="fechaInicioMensualidades" class="form-label">Fecha de Inicio:</label>
                                                <input type="date" class="form-control" id="fechaInicioMensualidades" name="fechaInicioMensualidades">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="fechaFinMensualidades" class="form-label">Fecha de Fin:</label>
                                                <input type="date" class="form-control" id="fechaFinMensualidades" name="fechaFinMensualidades">
                                            </div>
                                        </div>
                                    </div>
                                 
                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary" id="btnGenerarReporte">Generar Reporte</button>
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
                            <button class="btn btn-success mt-3" id="btnDescargarPDF">Descargar PDF</button>
                        </div>
                    </div>
                </div>
            </div>
          
            <div class="row my-4">
                <div class="col-12">
                    <h3>Reportes Estadísticos</h3>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h4>Distribución de Edad de Atletas</h4>
                            <canvas id="edadAtletasChart"></canvas>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h4>Proporción de Género</h4>
                            <canvas id="generoChart"></canvas>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h4>Progreso de Asistencias Mensuales</h4>
                            <canvas id="asistenciasChart"></canvas>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h4>Resultados de Pruebas WADA</h4>
                            <canvas id="wadaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require_once("comunes/footer.php"); ?>
    <script src="datatables/datatables.min.js"></script>
    <script type="module" src="js/reportes.js"></script>
</body>
</html>
