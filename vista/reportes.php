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
                                <div id="filtrosMensualidades" class="col-12">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="periodoMensualidades" class="form-label">Periodo:</label>
                                            <select class="form-select" id="periodoMensualidades" name="periodoMensualidades">
                                                <option value="mes">Mensual</option>
                                                <option value="trimestre">Trimestral</option>
                                                <option value="año">Anual</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="filtrosWada" class="col-12">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="periodoWada" class="form-label">Periodo:</label>
                                            <select class="form-select" id="periodoWada" name="periodoWada">
                                                <option value="mes">Mensual</option>
                                                <option value="trimestre">Trimestral</option>
                                                <option value="año">Anual</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="filtrosEventos" class="col-12">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="periodoEventos" class="form-label">Periodo:</label>
                                            <select class="form-select" id="periodoEventos" name="periodoEventos">
                                                <option value="mes">Mensual</option>
                                                <option value="trimestre">Trimestral</option>
                                                <option value="año">Anual</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="filtrosAsistencias" class="col-12">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="periodoAsistencias" class="form-label">Periodo:</label>
                                            <select class="form-select" id="periodoAsistencias" name="periodoAsistencias">
                                                <option value="semana">Semanal</option>
                                                <option value="mes">Mensual</option>
                                                <option value="trimestre">Trimestral</option>
                                                <option value="año">Anual</option>
                                            </select>
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
    <script src="js/reportes.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('controlador/reportes.php?tipo=edad_atletas')
                .then(response => response.json())
                .then(data => {
                    var ctx = document.getElementById('edadAtletasChart').getContext('2d');
                    var edadAtletasChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,  
                            datasets: [{
                                label: 'Número de Atletas',
                                data: data.values,  
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }).catch(error => {
                    console.error('Error fetching edad_atletas data:', error);
                });

            fetch('controlador/reportes.php?tipo=genero')
                .then(response => response.json())
                .then(data => {
                    var ctx = document.getElementById('generoChart').getContext('2d');
                    var generoChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: data.labels, 
                            datasets: [{
                                label: 'Proporción de Género',
                                data: data.values, 
                                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
                                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Proporción de Género'
                                }
                            }
                        }
                    });
                }).catch(error => {
                    console.error('Error fetching genero data:', error);
                });

            fetch('controlador/reportes.php?tipo=asistencias')
                .then(response => response.json())
                .then(data => {
                    var ctx = document.getElementById('asistenciasChart').getContext('2d');
                    var asistenciasChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,     
                            datasets: [{
                                label: 'Asistencias',
                                data: data.values,  
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }).catch(error => {
                    console.error('Error fetching asistencias data:', error);
                });

            fetch('controlador/reportes.php?tipo=wada')
                .then(response => response.json())
                .then(data => {
                    var ctx = document.getElementById('wadaChart').getContext('2d');
                    var wadaChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,  
                            datasets: [{
                                label: 'Resultados de Pruebas WADA',
                                data: data.values, 
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }).catch(error => {
                    console.error('Error fetching wada data:', error);
                });
        });
    </script>
</body>
</html>
