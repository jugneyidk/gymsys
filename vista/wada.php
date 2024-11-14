<?php $formulario = "wada"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Status WADA - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
</head>

<body class="d-flex flex-column vh-100">
    <script>
        var actualizar = <?php echo $permisos["actualizar"] ?>;
        var eliminar = <?php echo $permisos["eliminar"] ?>;
    </script>
    <?php require_once("comunes/menu.php"); ?>
    <main>
        <div class="container-lg">
            <div class="row">
                <div class="col-10 col-lg-8">
                    <div class="card shadow card-custom">
                        <div class="card-header header-custom d-flex justify-content-between align-items-center">
                            <h2 class="mb-0">Gestionar WADA</h2>
                            <?php
                            if ($permisos["crear"] === 1):
                                ?>
                                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modal">
                                    Registrar+
                                </button>
                                <?php
                            endif;
                            ?>
                        </div>
                        <h2 class="text-center mb-4">Atletas Registrados en WADA</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaWada">
                                <thead>
                                    <tr>
                                        <th class="d-none">Cedula</th>
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
                <div class="col-12 col-lg-4">
                    <div class="card shadow card-custom">
                        <div class="card-header header-custom">
                            <h3>Próximos a Vencer</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaProximosVencer">
                                <thead>
                                    <tr>
                                        <th class="d-none">Cedula</th>
                                        <th>Atleta</th>
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
            <?php require_once("comunes/modal.php"); ?>
        </div>
    </main>
    <?php require_once("comunes/footer.php"); ?>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.1.8/datatables.min.js"></script>
    <script type="module" src="js/wada.js"></script>
</body>

</html>