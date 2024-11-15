<!DOCTYPE html>
<html lang="es">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bitacora - Sistema</title>
        <?php require_once("comunes/linkcss.php"); ?>
</head>

<body class="bg-light">
        <?php require_once("comunes/menu.php"); ?>
        <main class="container-md my-3 my-md-5">
                <div class="row">
                        <div class="col">
                                <div class="card shadow">
                                        <div
                                                class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                                                <h2 class="mb-0">Bitacora</h2>
                                        </div>
                                        <div class="card-body">
                                                <h3 class="text-center mb-2">Bitacora</h3>
                                                <div class="table-responsive">
                                                        <table class="table table-striped table-hover"
                                                                id="tablabitacora">
                                                                <thead>
                                                                        <tr>
                                                                                <th class="d-none">Usuario</th>
                                                                                <th>Usuario</th>
                                                                                <th>Accion</th>
                                                                                <th>Modulo</th>
                                                                                <th class="d-none d-md-table-cell">Fecha</th>
                                                                                <th class="d-none d-md-table-cell">Registro Modificado</th>
                                                                                <th>Detalles</th>
                                                                        </tr>
                                                                </thead>
                                                                <tbody id="listado">

                                                                </tbody>
                                                        </table>
                                                </div>

                                        </div>
                                </div>
                        </div>
                </div>

        </main>
        <?php require_once "comunes/modal.php"; ?>
        <?php require_once "comunes/footer.php"; ?>
        <script type="text/javascript" src="datatables/datatables.min.js"></script>
        <script type="module" src="js/bitacora.js"></script>
</body>

</html>