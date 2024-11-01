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
                            <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                data-bs-target="#modalInscripcion">
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

            <!-- Modal de Inscripción -->
            <div class="modal fade" id="modalInscripcion" tabindex="-1" aria-labelledby="modalInscripcionLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title" id="modalInscripcionLabel">Nuevo Entrenador</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="f1" autocomplete="off">
                                <input type="hidden" name="accion" id="accion" value="incluir">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombres" class="form-label">Nombres:</label>
                                        <input type="text" class="form-control" id="nombres" name="nombres">
                                        <div id="snombres" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="apellidos" class="form-label">Apellidos:</label>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos">
                                        <div id="sapellidos" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cedula" class="form-label">Cédula:</label>
                                        <input type="text" class="form-control" id="cedula" name="cedula">
                                        <div id="scedula" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="genero" class="form-label">Género:</label>
                                        <select class="form-select" id="genero" name="genero">
                                            <option>Masculino</option>
                                            <option>Femenino</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento"
                                            name="fecha_nacimiento">
                                        <div id="sfecha_nacimiento" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lugar_nacimiento" class="form-label">Lugar de Nacimiento:</label>
                                        <input type="text" class="form-control" id="lugar_nacimiento"
                                            name="lugar_nacimiento">
                                        <div id="slugarnacimiento" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="estado_civil" class="form-label">Estado Civil:</label>
                                        <select class="form-select" id="estado_civil" name="estado_civil">
                                            <option>Soltero</option>
                                            <option>Casado</option>
                                            <option>Divorciado</option>
                                            <option>Viudo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="telefono" class="form-label">Teléfono:</label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono">
                                        <div id="stelefono" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="correo" class="form-label">Correo:</label>
                                        <input type="email" class="form-control" id="correo" name="correo">
                                        <div id="scorreo" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="grado_instruccion" class="form-label">Grado de Instrucción:</label>
                                        <input type="text" class="form-control" id="grado_instruccion"
                                            name="grado_instruccion">
                                        <div id="sgrado_instruccion" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Contraseña:</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                        <div id="spassword" class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <button type="submit" id="incluir" class="btn btn-primary btn-block">Registrar
                                    Entrenador</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Modificación -->
            <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modalModificarLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="modalModificarLabel">Modificar Entrenador</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="f2" autocomplete="off">
                                <input type="hidden" name="accion" id="accion_modificar" value="modificar">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombres_modificar" class="form-label">Nombres:</label>
                                        <input type="text" class="form-control" id="nombres_modificar"
                                            name="nombres_modificar">
                                        <div id="snombres_modificar" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="apellidos_modificar" class="form-label">Apellidos:</label>
                                        <input type="text" class="form-control" id="apellidos_modificar"
                                            name="apellidos_modificar">
                                        <div id="sapellidos_modificar" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cedula_modificar" class="form-label">Cédula:</label>
                                        <input type="text" class="form-control" id="cedula_modificar"
                                            name="cedula_modificar">
                                        <div id="scedula_modificar" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="genero_modificar" class="form-label">Género:</label>
                                        <select class="form-select" id="genero_modificar" name="genero_modificar">
                                            <option>Masculino</option>
                                            <option>Femenino</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha_nacimiento_modificar" class="form-label">Fecha de
                                            Nacimiento:</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento_modificar"
                                            name="fecha_nacimiento_modificar">
                                        <div id="sfecha_nacimiento_modificar" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="lugar_nacimiento_modificar" class="form-label">Lugar de
                                            Nacimiento:</label>
                                        <input type="text" class="form-control" id="lugar_nacimiento_modificar"
                                            name="lugar_nacimiento_modificar">
                                        <div id="slugarnacimiento_modificar" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="estado_civil_modificar" class="form-label">Estado Civil:</label>
                                        <select class="form-select" id="estado_civil_modificar"
                                            name="estado_civil_modificar">
                                            <option>Soltero</option>
                                            <option>Casado</option>
                                            <option>Divorciado</option>
                                            <option>Viudo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="telefono_modificar" class="form-label">Teléfono:</label>
                                        <input type="tel" class="form-control" id="telefono_modificar"
                                            name="telefono_modificar">
                                        <div id="stelefono_modificar" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="correo_modificar" class="form-label">Correo:</label>
                                        <input type="email" class="form-control" id="correo_modificar"
                                            name="correo_modificar">
                                        <div id="scorreo_modificar" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="grado_instruccion_modificar" class="form-label">Grado de
                                            Instrucción:</label>
                                        <input type="text" class="form-control" id="grado_instruccion_modificar"
                                            name="grado_instruccion_modificar">
                                        <div id="sgrado_instruccion_modificar" class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="modificar_contraseña"
                                                name="modificar_contraseña">
                                            <label class="form-check-label" for="modificar_contraseña">Modificar
                                                contraseña</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_modificar" class="form-label">Nueva Contraseña:</label>
                                        <input type="password" class="form-control" id="password_modificar"
                                            name="password_modificar" disabled>
                                        <div id="spassword_modificar" class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <button type="submit" id="modificar" class="btn btn-primary btn-block">Modificar
                                    Entrenador</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require_once("comunes/footer.php"); ?>
    <script type="text/javascript" src="datatables/datatables.min.js"></script>
    <script type="module" src="js/entrenadores.js"></script>
</body>

</html>