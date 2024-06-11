<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción de Atletas - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
    
</head>
<body>
<?php require_once("comunes/menu.php"); ?>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="row justify-content-center w-100">
            <div class="card p-4 col-lg-8 col-md-10 mb-3">
                <h2 class="card-title text-center mb-4">Inscripción de Atletas</h2>
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombres" class="form-label">Nombres:</label>
                            <input type="text" class="form-control" id="nombres" name="nombres" >
                            <div id="snombres" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellidos" class="form-label">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" >
                            <div id="sapellidos" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cedula" class="form-label">Cédula:</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" >
                            <div id="scedula" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="genero" class="form-label">Género:</label>
                            <select class="form-control" id="genero" name="genero">
                                <option>Masculino</option>
                                <option>Femenino</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" >
                            <div id="sfecha_nacimiento" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edad" class="form-label">Edad:</label>
                            <input type="number" class="form-control" id="edad" name="edad"  readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="lugar_nacimiento" class="form-label">Lugar de Nacimiento:</label>
                            <input type="text" class="form-control" id="lugar_nacimiento" name="lugar_nacimiento" >
                            <div id="slugarnacimiento" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="peso" class="form-label">Peso (kg):</label>
                            <input type="number" class="form-control" id="peso" name="peso" step="0.01" >
                            <div id="speso" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="altura" class="form-label">Altura (cm):</label>
                            <input type="number" class="form-control" id="altura" name="altura" step="0.01" >
                            <div id="saltura" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tipo_atleta" class="form-label">Tipo de Atleta:</label>
                            <input type="text" class="form-control" id="tipo_atleta" name="tipo_atleta" >
                            <div id="stipo_atleta" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estado_civil" class="form-label">Estado Civil:</label>
                            <select class="form-control" id="estado_civil" name="estado_civil">
                                <option>Soltero</option>
                                <option>Casado</option>
                                <option>Divorciado</option>
                                <option>Viudo</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" >
                            <div id="stelefono" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">Correo:</label>
                            <input type="email" class="form-control" id="correo" name="correo" >
                            <div id="scorreo" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="entrenador_asignado" class="form-label">Entrenador Asignado:</label>
                            <input type="text" class="form-control" id="entrenador_asignado" name="entrenador_asignado" >
                            <div id="sentrenador_asignado" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <!-- Campos adicionales para el representante, ocultos inicialmente -->
                    <div id="representanteInfo" class="row" style="display:none;">
                        <div class="col-md-6 mb-3">
                            <label for="nombre_representante" class="form-label">Nombre del Representante:</label>
                            <input type="text" class="form-control" id="nombre_representante" name="nombre_representante">
                            <div id="snombre_representante" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono_representante" class="form-label">Teléfono del Representante:</label>
                            <input type="tel" class="form-control" id="telefono_representante" name="telefono_representante">
                            <div id="stelefono_representante" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Registrar Atleta</button>
                </form>
            </div>
            
            <div class="card p-4 col-lg-3 col-md-2 mx-2" id="card-acciones">
                <h4 class="card-title text-center mb-4">Acciones</h4>
                <button type="button" class="btn btn-secondary btn-block" onclick="resetForm()">Limpiar</button>
                <button type="button" class="btn btn-info btn-block" data-bs-toggle="modal" data-bs-target="#modalAtletas">Consultar Atletas</button>
            </div>
        </div>
        <!-- Modal para consultar atletas inscritos -->
        <div class="modal fade" id="modalAtletas" tabindex="-1" aria-labelledby="modalAtletasLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">s
                        <h5 class="modal-title" id="modalAtletasLabel">Atletas Inscritos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Lista de atletas inscritos aquí.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
    <?php require_once("comunes/footer.php"); ?>
    </footer>
    <script src="js/jquery.min.js"></script>
    <script src="js/atletas.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
