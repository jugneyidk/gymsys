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
        <div class="row">
            <div class="card p-4" style="width: 40rem;">
                <h2 class="card-title text-center mb-4">Inscripción de Atletas</h2>
                <form action="submit_atleta.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombres" class="form-label">Nombres:</label>
                            <input type="text" class="form-control" id="nombres" name="nombres" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellidos" class="form-label">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                        </div>
                    </div>
                     <div class="row">
                    <div class="form-group col-md-6">
                        <label for="cedula">Cédula:</label>
                        <input type="text" class="form-control" id="cedula" name="cedula" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="peso">Peso (kg):</label>
                        <input type="number" class="form-control" id="peso" name="peso" step="0.01" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sexo">Sexo:</label>
                        <select class="form-control" id="sexo" name="sexo">
                            <option>Masculino</option>
                            <option>Femenino</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="edad">Edad:</label>
                        <input type="number" class="form-control" id="edad" name="edad" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="categoria">Categoría:</label>
                        <select class="form-control" id="categoria" name="categoria">
                            <option>Junior</option>
                            <option>Senior</option>
                            <option>Master</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="division">División:</label>
                        <input type="text" class="form-control" id="division" name="division" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="entrenador">Entrenador:</label>
                        <input type="text" class="form-control" id="entrenador" name="entrenador" required>
                    </div>
                </div>
                    <button type="submit" class="btn btn-primary btn-block">Registrar Atleta</button>
                </form>
            </div>
            <!-- Tarjeta para acciones adicionales -->
            <div class="card p-4 mx-2" style="width: 18rem;">
                <h4 class="card-title text-center mb-4">Acciones</h4>
                <button type="button" class="btn btn-secondary btn-block" onclick="resetForm()">Limpiar</button>
                <button type="button" class="btn btn-info btn-block" data-bs-toggle="modal" data-bs-target="#modalAtletas">Consultar Atletas</button>
            </div>
            <!-- Modal para mostrar los atletas inscritos -->
            <div class="modal fade" id="modalAtletas" tabindex="-1" aria-labelledby="modalAtletasLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
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
    </div>
    <footer>
    <?php require_once("comunes/footer.php"); ?>
    </footer>
    
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        function resetForm() {
            document.querySelector('form').reset();
        }
    </script>
</body>
</html>
