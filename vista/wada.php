<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Status WADA - Sistema</title>
    <?php require_once ("comunes/linkcss.php"); ?>
    <link rel="stylesheet" href="css/all.min.css">
    <style>
        .table-wrapper {
            max-width: 100%;
            overflow-x: auto;
        }

        .card-custom {
            margin: 20px 0;
        }

        .header-custom {
            background-color: #17a2b8;
            color: white;
            padding: 10px;
            font-size: 1.2em;
        }

        .modal-xl {
            max-width: 90%;
        }

        .btn-large {
            font-size: 1.2em;
        }
    </style>
</head>
<body class="d-flex flex-column vh-100">
    <?php require_once ("comunes/menu.php"); ?>
    <div class="container-lg">
        <div class="row">
            <div class="col-10 col-lg-8">
                <div class="card shadow card-custom">
                    <div class="card-header header-custom d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Gestionar WADA</h2>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalInscripcion">
                            Registrar+
                        </button>
                    </div>
                    <div class="p-4">
                        <h2 class="text-center mb-4">Atletas Registrados en WADA</h2>
                        <div class="table-wrapper">
                            <table class="table table-striped table-hover" id="tablaWada">
                                <thead>
                                    <tr>
                                        <th>Atleta</th>
                                        <th>Status</th>
                                        <th>Inscrito</th>
                                        <th>Última Actualización</th>
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
            <div class="col-12 col-lg-4">
                <div class="card shadow card-custom">
                    <div class="card-header header-custom">
                        <h3>Próximos a Vencer</h3>
                    </div>
                    <div class="p-4">
                        <div class="table-wrapper">
                            <table class="table table-striped table-hover" id="tablaProximosVencer">
                                <thead>
                                    <tr>
                                        <th>Atleta</th>
                                        <th>Cedula</th>
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
        <div class="modal fade" id="modalInscripcion" tabindex="-1" aria-labelledby="modalInscripcionLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalInscripcionLabel">Nuevo Registro WADA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="f1" autocomplete="off">
                            <input type="hidden" name="accion" id="accion" value="incluir">
                            <div class="mb-3">
                                <label for="atleta" class="form-label">Seleccionar Atleta:</label>
                                <select class="form-select" id="atleta" name="atleta" required>
                                    <option value="">Seleccione un atleta</option>
                                </select>
                                <div id="satleta" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status WADA:</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Seleccione el status</option>
                                    <option value="1">Cumple</option>
                                    <option value="2">No Cumple</option>
                                </select>
                                <div id="sstatus" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="inscrito" class="form-label">Inscrito:</label>
                                <input type="date" class="form-control" id="inscrito" name="inscrito" required>
                                <div id="sinscrito" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="ultima_actualizacion" class="form-label">Última Actualización:</label>
                                <input type="date" class="form-control" id="ultima_actualizacion" name="ultima_actualizacion" required>
                                <div id="sultima_actualizacion" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="vencimiento" class="form-label">Vencimiento:</label>
                                <input type="date" class="form-control" id="vencimiento" name="vencimiento" required>
                                <div id="svencimiento" class="invalid-feedback"></div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-large btn-block">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modalModificarLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalModificarLabel">Modificar Registro WADA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="f2" autocomplete="off">
                            <input type="hidden" name="accion" id="accion_modificar" value="modificar">
                            <div class="mb-3">
                                <label for="atleta_modificar" class="form-label">Seleccionar Atleta:</label>
                                <select class="form-select" id="atleta_modificar" name="atleta_modificar" required>
                                    <option value="">Seleccione un atleta</option>
                                </select>
                                <div id="satleta_modificar" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="status_modificar" class="form-label">Status WADA:</label>
                                <select class="form-select" id="status_modificar" name="status_modificar" required>
                                    <option value="">Seleccione el status</option>
                                    <option value="1">Cumple</option>
                                    <option value="2">No Cumple</option>
                                </select>
                                <div id="sstatus_modificar" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="inscrito_modificar" class="form-label">Inscrito:</label>
                                <input type="date" class="form-control" id="inscrito_modificar" name="inscrito_modificar" required>
                                <div id="sinscrito_modificar" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="ultima_actualizacion_modificar" class="form-label">Última Actualización:</label>
                                <input type="date" class="form-control" id="ultima_actualizacion_modificar" name="ultima_actualizacion_modificar" required>
                                <div id="sultima_actualizacion_modificar" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="vencimiento_modificar" class="form-label">Vencimiento:</label>
                                <input type="date" class="form-control" id="vencimiento_modificar" name="vencimiento_modificar" required>
                                <div id="svencimiento_modificar" class="invalid-feedback"></div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-large btn-block">Modificar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once ("comunes/footer.php"); ?>
    <script type="text/javascript" src="datatables/datatables.min.js"></script>
   
    <script src="js/wada.js"></script>
</body>
</html>
