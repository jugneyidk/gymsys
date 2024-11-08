<?php $formulario = "entrenadores"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción de Entrenadores - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
</head>

<body class="d-flex flex-column vh-100">
    <script>
        var actualizar = <?php echo $permisos["actualizar"] ?>;
        var eliminar = <?php echo $permisos["eliminar"] ?>;
    </script>

    <?php require_once("comunes/menu.php"); ?>
    <br>
    <main>
        <div class="container-lg d-flex justify-content-center align-items-center">
            <div class="row justify-content-center w-100">
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="card-header d-flex justify-content-between align-items-center bg-info text-white w-100">
                        <h2 class="mb-0">Gestionar Entrenadores</h2>
                        <?php
                        if ($permisos["crear"] === 1):
                            ?>
                            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modal">
                                Registrar <i class="fa-solid fa-plus"></i>
                            </button>
                            <?php
                        endif;
                        ?>
                    </div>
                    <div class="p-4 shadow">
                        <div class="p-4">
                            <h2 class="text-center mb-4">Entrenadores Inscritos</h2>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tablaentrenador">
                                    <thead>
                                        <tr>
                                            <th>Cédula</th>
                                            <th>Nombre</th>
                                            <th>Teléfono</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listado">
                                        <!-- Aquí se listan los entrenadores -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once("comunes/modal.php"); ?>
    </main>
    <?php require_once("comunes/footer.php"); ?>
    <script type="text/javascript" src="datatables/datatables.min.js"></script>
    <script type="module" src="js/entrenadores.js"></script>
</body>

</html>