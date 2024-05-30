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
                            <label for="peso" class="form-label">Peso (kg):</label>
                            <input type="number" class="form-control" id="peso" name="peso" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sexo" class="form-label">Sexo:</label>
                            <select class="form-control" id="sexo" name="sexo">
                                <option>Masculino</option>
                                <option>Femenino</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edad" class="form-label">Edad:</label>
                            <input type="number" class="form-control" id="edad" name="edad" required onchange="checkAge()">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="categoria" class="form-label">Categoría:</label>
                            <select class="form-control" id="categoria" name="categoria">
                                <option>Junior</option>
                                <option>Senior</option>
                                <option>Master</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="division" class="form-label">División:</label>
                            <input type="text" class="form-control" id="division" name="division" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="entrenador" class="form-label">Entrenador:</label>
                            <input type="text" class="form-control" id="entrenador" name="entrenador" required>
                        </div>
                    </div>
                    <!-- Campos adicionales para el representante, ocultos inicialmente -->
                    <div id="representanteInfo" class="row" style="display:none;">
                        <div class="col-md-6 mb-3">
                            <label for="nombre_representante" class="form-label">Nombre del Representante:</label>
                            <input type="text" class="form-control" id="nombre_representante" name="nombre_representante">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono_representante" class="form-label">Teléfono del Representante:</label>
                            <input type="tel" class="form-control" id="telefono_representante" name="telefono_representante">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Registrar Atleta</button>
                </form>
            </div>
            
            <div class="card p-4 col-lg-3 col-md-4 mx-2" id="card-acciones">
                <h4 class="card-title text-center mb-4">Acciones</h4>
                <button type="button" class="btn btn-secondary btn-block" onclick="resetForm()">Limpiar</button>
                <button type="button" class="btn btn-info btn-block" data-bs-toggle="modal" data-bs-target="#modalAtletas">Consultar Atletas</button>
            </div>
        </div>
        <!-- Modal para consultar atletas inscritos -->
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
    <footer>
    <?php require_once("comunes/footer.php"); ?>
    </footer>
    
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        function resetForm() {
            document.querySelector('form').reset();
        }
        
        function checkAge() {
            var edad = document.getElementById('edad').value;
            var representanteInfo = document.getElementById('representanteInfo');
            representanteInfo.style.display = edad < 18 ? 'flex' : 'none';
        }
    </script>
</body>
</html>
