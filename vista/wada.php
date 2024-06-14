<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Status WADA - Sistema</title>
    <?php require_once ("comunes/linkcss.php"); ?>
    <link rel="stylesheet" href="css/all.min.css">
</head>

<body class="d-flex flex-column vh-100">
    <?php require_once ("comunes/menu.php"); ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h2>Registrar Status WADA</h2>
                    </div>
                    <div class="card-body">
                        <form id="f" method="post" autocomplete="off">
                            <input autocomplete="off" type="text" class="form-control" name="accion" id="accion"
                                style="display: none;">
                            <div class="mb-3">
                                <label for="atleta" class="form-label">Seleccionar Atleta:</label>
                                <select class="form-select" id="atleta" name="atleta" required>
                                    <option value="">Seleccione un atleta</option>
                                    <option value=28>andre</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status WADA:</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Seleccione el status</option>
                                    <option value="1">Cumple</option>
                                    <option value="2">No Cumple</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="inscrito" class="form-label">Inscrito:</label>
                                <input type="date" class="form-control" id="inscrito" name="inscrito" required>
                            </div>
                            <div class="mb-3">
                                <label for="ultima_actualizacion" class="form-label">Última Actualización:</label>
                                <input type="date" class="form-control" id="ultima_actualizacion"
                                    name="ultima_actualizacion" required>
                            </div>
                            <div class="mb-3">
                                <label for="vencimiento" class="form-label">Vencimiento:</label>
                                <input type="date" class="form-control" id="vencimiento" name="vencimiento" required>
                            </div>
                            <button type="button" class="btn btn-primary" id="incluir">Registrar</button>
                        </form>
                        <div id="mensaje" class="mt-3"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Estadísticas de Status WADA</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-12 mb-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Atletas que Cumplen</h6>
                                        <p class="card-text" id="totalCumplen">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Atletas que No Cumplen</h6>
                                        <p class="card-text" id="totalNoCumplen">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Total Atletas</h6>
                                        <p class="card-text" id="totalAtletas">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Últimos Registros WADA</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group" id="listaUltimosRegistros">
                            <!-- Últimos registros aquí -->
                        </ul>
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