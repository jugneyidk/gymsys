<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias Diarias - Sistema</title>
    <?php require_once ("comunes/linkcss.php"); ?>
    <link rel="stylesheet" href="datatables/datatables.min.css">
</head>

<body class="d-flex flex-column vh-100">
    <script>
        var actualizar = <?php echo $permisos["actualizar"] ?>;
        var eliminar = <?php echo $permisos["eliminar"] ?>;
    </script>
    <?php require_once ("comunes/menu.php"); ?>
     <main>
    <div class="container-lg my-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Asistencias Diarias</h2>
                    </div>
                    <div class="card-body">
                        <form id="formAsistencias">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fechaAsistencia" class="form-label">Fecha:</label>
                                    <input type="date" class="form-control" id="fechaAsistencia" name="fechaAsistencia">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tablaAsistencias">
                                    <thead>
                                        <tr>
                                            <th>Cédula</th>
                                            <th>Nombre</th>
                                            <th>Apellidos</th>
                                            <th>Asistió</th>
                                            <th>Comentario</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listadoAsistencias"></tbody>
                                </table>
                            </div>
                            <?php
                            if ($permisos["crear"] === 1):
                                ?>
                                <button type="button" class="btn btn-primary mt-3" id="btnGuardarAsistencias">Guardar
                                    Asistencias
                                </button>
                                <?php
                            endif;
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
   </main>
    <?php require_once ("comunes/footer.php"); ?>
    <script src="datatables/datatables.min.js"></script>
    <script src="js/asistencias.js"></script>
</body>

</html>