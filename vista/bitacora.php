<!DOCTYPE html>
<html lang="es">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bitacora - Sistema</title>
        <?php require_once("comunes/linkcss.php"); ?>
</head>

<body class="d-flex flex-column vh-100">
        <?php require_once("comunes/menu.php"); ?>
        <br>
        <main>
        <div class="container-lg d-flex justify-content-center align-items-center">
                <div class="row justify-content-center w-100">
                        <div class="col-12 col-md-8 col-lg-9">
                                <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
                                        <h2 class="mb-0">Bitacora</h2>
                                </div>
                                <div class="p-4 shadow">
                                        <div class="p-4">
                                                <h2 class="text-center mb-4">Bitacora</h2>
                                                <div class="table-responsive">
                                                        <table class="table table-striped table-hover" id="tablabitacora">
                                                                <thead>
                                                                        <tr>
                                                                                <th class="d-none">Usuario</th>
                                                                                <th>Usuario</th>
                                                                                <th>Accion</th>
                                                                                <th>Modulo</th>
                                                                                <th>Fecha</th>
                                                                                <th>Registro Modificado</th>
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
        </div>
        </main>
        <?php require_once "comunes/modal.php"; ?>
        <?php require_once("comunes/footer.php"); ?>
        <script type="text/javascript" src="datatables/datatables.min.js"></script>
        <script src="js/bitacora.js"></script>
</body>

</html>