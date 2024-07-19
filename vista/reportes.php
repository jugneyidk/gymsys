<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Sistema</title>
    <?php require_once ("comunes/linkcss.php"); ?>
    <link rel="stylesheet" href="datatables/datatables.min.css">
</head>
<body class="d-flex flex-column vh-100">
    <?php require_once ("comunes/menu.php"); ?>
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
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fechaInicio" class="form-label">Fecha de Inicio:</label>
                                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fechaFin" class="form-label">Fecha de Fin:</label>
                                    <input type="date" class="form-control" id="fechaFin" name="fechaFin">
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
    </div>
    <?php require_once ("comunes/footer.php"); ?>
    <script src="datatables/datatables.min.js"></script>
    <script src="js/reportes.js"></script>
</body>
</html>
