<?php $formulario = "atletas"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción de Atletas - Sistema</title>
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
                    <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
                        <h2 class="mb-0">Gestionar Atletas</h2>
                        <?php if ($permisos["crear"] === 1): ?>
                            <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                data-bs-target="#modal">
                                Registrar <i class="fa-solid fa-plus"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="p-4 shadow">
                        <div class="p-4">
                            <h2 class="text-center mb-4">Atletas Inscritos</h2>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tablaatleta">
                                    <thead>
                                        <tr>
                                            <th>Cédula</th>
                                            <th>Nombre</th>
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

            <!-- Modal de Registro de Tipos de Atleta -->
            <div class="modal fade" id="modalRegistrarTipoAtleta" tabindex="-1"
                aria-labelledby="modalRegistrarTipoAtletaLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalRegistrarTipoAtletaLabel">Registrar Tipo de Atleta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formRegistrarTipoAtleta">
                                <div class="mb-3">
                                    <label for="nombre_tipo_atleta" class="form-label">Nombre del Tipo de
                                        Atleta:</label>
                                    <input type="text" class="form-control" id="nombre_tipo_atleta"
                                        name="nombre_tipo_atleta">
                                    <div id="snombre_tipo_atleta" class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="tipo_cobro" class="form-label">Tipo de Cobro:</label>
                                    <input type="number" class="form-control" id="tipo_cobro" name="tipo_cobro"
                                        step="0.01">
                                    <div id="stipo_cobro" class="invalid-feedback"></div>
                                </div>
                                <button type="button" id="btnRegistrarTipoAtleta"
                                    class="btn btn-primary">Registrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once("comunes/modal.php"); ?>
    </main>
    <?php
    require_once("comunes/footer.php");
    ?>
    <script type="text/javascript" src="datatables/datatables.min.js"></script>
    <script type="module" src="js/atletas.js"></script>
</body>

</html>