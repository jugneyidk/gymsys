<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias de Atletas - Sistema</title>
    <?php require_once ("comunes/linkcss.php"); ?>
    <link rel="stylesheet" href="css/all.min.css">
</head>

<body class="d-flex flex-column vh-100">
    <?php require_once ("comunes/menu.php"); ?>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2>Asistencias de Atletas</h2>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-primary" id="crearAsistenciaBtn"><i class="fas fa-plus"></i> Crear
                        Asistencia</button>
                    <input type="date" id="fechaAsistencia" class="form-control w-auto">
                </div>
                <div id="listaAsistencias">

                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-lg-10">
                <div id="formTomarAsistencia" style="display: none;">
                    <h2>Tomar Asistencia - <span id="fechaAsistenciaLabel"></span></h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Asistencia</th>
                                </tr>
                            </thead>
                            <tbody id="listaAtletas">

                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-primary" id="guardarAsistenciaBtn">Guardar Asistencia</button>
                </div>
            </div>
        </div>
    </div>
    <?php require_once ("comunes/footer.php"); ?>
    <script src="js/asistencias.js"></script>
    <script type="text/javascript" src="datatables/datatables.min.js"></script>
</body>

</html>