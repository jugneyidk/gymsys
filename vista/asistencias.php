<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias de Atletas - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
</head>
<body class="d-flex flex-column vh-100">
    <?php require_once("comunes/menu.php"); ?>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2>Registro de Asistencias</h2>
                <div class="input-group mb-3">
                    <input type="date" id="fechaAsistencia" class="form-control" placeholder="Selecciona una fecha">
                    <button class="btn btn-primary" id="crearAsistenciaBtn"><i class="fas fa-plus"></i> Crear Asistencia</button>
                </div>
                <div id="listaAsistencias"></div>
                <div id="formularioAsistencia" style="display: none;">
                    <h4>Asistencia para: <span id="fechaAsistenciaLabel"></span></h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Asistencia</th>
                                <th>Comentario</th>
                            </tr>
                        </thead>
                        <tbody id="listaAtletas"></tbody>
                    </table>
                    <button class="btn btn-success" id="guardarAsistenciaBtn">Guardar Asistencia</button>
                </div>
            </div>
        </div>
    </div>
    <?php require_once ("comunes/footer.php"); ?>
    <script type="text/javascript" src="datatables/datatables.min.js"></script>
    <script src="js/asistencias.js"></script>
</body>
</html>
