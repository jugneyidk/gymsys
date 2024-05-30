<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción de Entrenadores - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
</head>
<body>
<?php require_once("comunes/menu.php"); ?>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
       
        <div class="row justify-content-center w-100">
         
            <div class="card p-4 col-lg-8 col-md-10 mb-3">
                <h2 class="card-title text-center mb-4">Inscripción de Entrenadores</h2>
              
                <form action="submit_entrenador.php" method="POST">
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
                        <div class="col-md-6 mb-3">
                            <label for="cedula" class="form-label">Cédula:</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo Electrónico:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="experiencia" class="form-label">Años de Experiencia:</label>
                            <input type="number" class="form-control" id="experiencia" name="experiencia" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="especialidad" class="form-label">Especialidad:</label>
                            <input type="text" class="form-control" id="especialidad" name="especialidad" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Registrar Entrenador</button>
                </form>
            </div>
            <div class="card p-4 col-lg-3 col-md-4 mx-2" id="card-acciones">
                <h4 class="card-title text-center mb-4">Acciones</h4>
                <button type="button" class="btn btn-secondary btn-block" onclick="resetForm()">Limpiar</button>
                <button type="button" class="btn btn-info btn-block" data-bs-toggle="modal" data-bs-target="#modalEntrenadores">Consultar Entrenadores</button>
            </div>
        </div>
        <div class="modal fade" id="modalEntrenadores" tabindex="-1" aria-labelledby="modalEntrenadoresLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEntrenadoresLabel">Entrenadores Inscritos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Lista de entrenadores inscritos aquí.</p>
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
