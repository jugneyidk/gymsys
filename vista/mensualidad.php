<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Mensualidad - Sistema</title>
    <?php require_once ("comunes/linkcss.php"); ?>
    <link rel="stylesheet" href="css/all.min.css">
</head>
<body class="d-flex flex-column vh-100">
    <?php require_once ("comunes/menu.php"); ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
        <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Deudores</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaDeudores">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Cédula</th>
                                        <th>Tipo</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="listadoDeudores">
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Registrar Mensualidad</h2>
                    </div>
                    <div class="card-body">
                        <form id="formPago" method="POST">
                            <input autocomplete="off" type="hidden" name="accion" id="accion" value="incluir">
                            <div class="mb-3">
                                <label for="atleta" class="form-label">Atleta:</label>
                                <select class="form-select" id="atleta" name="id_atleta" required>
                                    <!-- Aquí se llenarán dinámicamente los atletas -->
                                </select>
                                <div id="satleta" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="monto" class="form-label">Monto:</label>
                                <input type="number" class="form-control" id="monto" name="monto" required>
                                <div id="smonto" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha:</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" required>
                                <div id="sfecha" class="invalid-feedback"></div>
                            </div>
                            <button type="button" class="btn btn-primary w-100" id="registrarPago" name="registrarPago">Registrar Pago</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                    <div class="card-header text-center">
                        <h2>Pagos Registrados</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaPagosRegistrados">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Cédula</th>
                                        <th>Tipo</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody id="listadoPagosRegistrados">
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <?php require_once ("comunes/footer.php"); ?>
    <script type="text/javascript" src="datatables/datatables.min.js"></script>
    <script src="js/mensualidad.js"></script>
</body>
</html>
