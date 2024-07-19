<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles y Permisos - Sistema</title>
    <?php require_once ("comunes/linkcss.php"); ?>
</head>

<body class="d-flex flex-column vh-100">
    <?php require_once ("comunes/menu.php"); ?>
    <div class="container-lg d-flex justify-content-center align-items-center">
        <div class="row justify-content-center w-100">
            <div class="col-12 col-md-8 col-lg-9">
                <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
                    <h2 class="mb-0">Gestionar Roles y Permisos</h2>
                    <button type="button" class="btn btn-light" data-bs-toggle="modal"
                        data-bs-target="#modalCrear">
                        Crear Rol+
                    </button>
                </div>
                <div class="p-4 shadow">
                    <div class="p-4">
                        <h2 class="text-center mb-4">Roles</h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaroles">
                                <thead>
                                    <tr>
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

        <!-- Modal de Registro de Rol -->
        <div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearLabel">Nuevo Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="form_incluir" autocomplete="off">
                            <div class="row">
                                <div class="col">
                                    <label for="nombre" class="form-label">Nombre del Rol:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre">
                                    <div id="snombre" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <span class="fw-bold">Permisos</span>
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Modulo</th>
                                                <th scope="col" class="text-center">Crear</th>
                                                <th scope="col" class="text-center">Leer</th>
                                                <th scope="col" class="text-center">Modificar</th>
                                                <th scope="col" class="text-center">Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Gestionar Entrenadores</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="centrenadores" id="centrenadores" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="rentrenadores" id="rentrenadores" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="uentrenadores" id="uentrenadores" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="dentrenadores" id="dentrenadores" value="1"></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Atletas</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="catletas" id="catletas" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="ratletas" id="ratletas" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="uatletas" id="uatletas" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="datletas" id="datletas" value="1"></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Roles y Permisos</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="crolespermisos" id="crolespermisos" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="rrolespermisos" id="rrolespermisos" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="urolespermisos" id="urolespermisos" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="drolespermisos" id="drolespermisos" value="1"></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Asistencias</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="casistencias" id="casistencias" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="rasistencias" id="rasistencias" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="uasistencias" id="uasistencias" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="dasistencias" id="dasistencias" value="1"></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Eventos/Competencias</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="ceventos" id="ceventos" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="reventos" id="reventos" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="ueventos" id="ueventos" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="deventos" id="deventos" value="1"></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Mensualidad</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="cmensualidad" id="cmensualidad" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="rmensualidad" id="rmensualidad" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="umensualidad" id="umensualidad" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="dmensualidad" id="dmensualidad" value="1"></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Wada</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="cwada" id="cwada" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="rwada" id="rwada" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="uwada" id="uwada" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="dwada" id="dwada" value="1"></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Reportes</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="creportes" id="creportes" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="rreportes" id="rreportes" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="ureportes" id="ureportes" value="1"></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="dreportes" id="dreportes" value="1"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <button type="button" id="btnIncluir" class="btn btn-primary btn-block">Registrar
                                Rol</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Modificación -->
        <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modalModificarLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalInscripcionLabel">Modificar Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="f1" autocomplete="off">
                            <input type="hidden" name="accion" id="accion" value="incluir">
                            <div class="row">
                                <div class="col">
                                    <label for="nombre" class="form-label">Nombre del Rol:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre">
                                    <div id="snombre" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <span class="fw-bold">Permisos</span>
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Modulo</th>
                                                <th scope="col" class="text-center">Crear</th>
                                                <th scope="col" class="text-center">Leer</th>
                                                <th scope="col" class="text-center">Modificar</th>
                                                <th scope="col" class="text-center">Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Gestionar Entrenadores</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Atletas</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Roles y Permisos</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Asistencias</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Eventos/Competencias</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Mensualidad</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Wada</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                            </tr>
                                            <tr>
                                                <td>Gestionar Reportes</td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                                <td class="text-center"><input class="form-check-input" type="checkbox"
                                                        name="" id=""></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <button type="button" id="btnIncluir" class="btn btn-primary btn-block">Registrar
                                Atleta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once ("comunes/footer.php"); ?>
    <script type="text/javascript" src="datatables/datatables.min.js"></script>
    <script src="js/rolespermisos.js"></script>
</body>

</html>