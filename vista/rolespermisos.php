<?php $formulario = "rolespermisos"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Roles y Permisos - Sistema</title>
        <?php require_once("comunes/linkcss.php");
        ?>
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
                                        <div
                                                class="card-header d-flex justify-content-between align-items-center bg-info text-white">
                                                <h2 class="mb-0">Gestionar Roles y Permisos</h2>
                                                <?php
                                                if ($permisos["crear"] === 1):
                                                        ?>
                                                        <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                                                data-bs-target="#modal" id="btnCrearRol">
                                                                Crear Rol+
                                                        </button>
                                                        <?php
                                                endif;
                                                ?>
                                        </div>
                                        <div class="p-4 shadow">
                                                <div class="p-4">
                                                        <h2 class="text-center mb-4">Roles</h2>
                                                        <div class="table-responsive">
                                                                <table class="table table-striped table-hover"
                                                                        id="tablaroles">
                                                                        <thead>
                                                                                <tr>
                                                                                        <th class="d-none">Id</th>
                                                                                        <th>Rol</th>
                                                                                        <th>Acción</th>
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
                        <?php
                        if ($permisos["actualizar"] === 1 || $permisos["crear"] === 1):
                                require_once("comunes/modal.php");
                        endif;
                        ?>
                </div>
        </main>
        <br>
        <?php require_once("comunes/footer.php"); ?>
        <script type="text/javascript" src="datatables/datatables.min.js"></script>
        <script type="module" src="js/rolespermisos.js"></script>
</body>

</html>