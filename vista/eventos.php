<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Competencias - Sistema</title>
    <?php require_once("comunes/linkcss.php") ?>
</head>
<body>
<?php require_once("comunes/menu.php"); ?>
    <div class="container align-items-top pt-3" style="min-height: 85vh;">
        <div class="row justify-content-start ">
                <div class="col-lg-9 col-md-5">
                    <div class="row">
                        <div class="col">

                            <div class="card border-primary p-0" style="">
                                <div class="card-header bg-primary"></div>
                                <div class="card-body">
                                <h4 class="card-title text-info"><strong>Nacional Sub-15</strong></h4>
                                <blockquote class="card-text"><em>Fecha: 15-10-23</br>Hora: 10:00 am</br>Participantes: 20</em></blockquote>
                                </div>
                                <div class="card-footer bg-primary">
                                <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalEventOne">Ver</button> <a href="#" class="btn btn-outline-light">Modificar</a>
                                </div>
                            </div>

                        </div>
                        <div class="col">

                            <div class="card border-secondary p-0" style="">
                                <div class="card-header bg-secondary"></div>
                                <div class="card-body">
                                <h4 class="card-title text-secondary"><strong>Nacional Sub-15</strong></h4>
                                <blockquote class="card-text"><em>Fecha: 15-10-23</br>Hora: 10:00 am</br>Participantes: 20</em></blockquote>
                                </div>
                                <div class="card-footer bg-secondary">
                                    <a href="#" class="btn btn-outline-dark disabled">Ver</a> <a href="#" class="btn btn-outline-dark disabled">Modificar</a>
                                </div>
                            </div>

                        </div>
                        <div class="col">

                             <div class="card border-primary p-0" style="">
                                <div class="card-header bg-primary"></div>
                                <div class="card-body">
                                <h4 class="card-title text-info"><strong>Nacional Sub-15</strong></h4>
                                <blockquote class="card-text"><em>Fecha: 15-10-23</br>Hora: 10:00 am</br>Participantes: 20</em></blockquote>
                                </div>
                                <div class="card-footer bg-primary">
                                    <a href="#" class="btn btn-outline-light">Ver</a> <a href="#" class="btn btn-outline-light">Modificar</a>
                                </div>
                            </div>

                        </div>
                    </div>





                </div>
                
                <div class="col-lg-3 col-md-4">
                    <div class="card p-4 mb-3 " id="">
                        <h4 class="card-title text-center mb-4">Acciones</h4>
                        <button type="button" class="btn btn-secondary btn-block" onclick="resetForm()">Limpiar</button>
                        <button type="button" class="btn btn-info btn-block" data-bs-toggle="modal" data-bs-target="#modalEntrenadores">Consultar anteriores (solo vista)</button>
                    </div>
                 </div>
            
                
                
        </div>
    </div>

                <div class="modal" tabindex="-1" role="dialog" id="modalEventOne">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card p-4 col-lg-10 col-md-12 mb-3">
                                    <h2 class="card-title text-center mb-4">Registro de Competencia</h2>
                                    <form action="submit_competencia.php" method="POST">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="arranque1" class="form-label">1er Arranque:</label>
                                                <input type="number" class="form-control" id="arranque1" name="arranque1" required>
                                                <select class="form-control mt-1" id="resultado_arranque1" name="resultado_arranque1">
                                                    <option>Bueno</option>
                                                    <option>Malo</option>
                                                </select>
                                                <select class="form-control mt-1" id="medalla_arranque1" name="medalla_arranque1">
                                                    <option value="">Sin Medalla</option>
                                                    <option>Oro</option>
                                                    <option>Plata</option>
                                                    <option>Bronce</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="arranque2" class="form-label">2do Arranque:</label>
                                                <input type="number" class="form-control" id="arranque2" name="arranque2" required>
                                                <select class="form-control mt-1" id="resultado_arranque2" name="resultado_arranque2">
                                                    <option>Bueno</option>
                                                    <option>Malo</option>
                                                </select>
                                                <select class="form-control mt-1" id="medalla_arranque2" name="medalla_arranque2">
                                                    <option value="">Sin Medalla</option>
                                                    <option>Oro</option>
                                                    <option>Plata</option>
                                                    <option>Bronce</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="arranque3" class="form-label">3er Arranque:</label>
                                                <input type="number" class="form-control" id="arranque3" name="arranque3" required>
                                                <select class="form-control mt-1" id="resultado_arranque3" name="resultado_arranque3">
                                                    <option>Bueno</option>
                                                    <option>Malo</option>
                                                </select>
                                                <select class="form-control mt-1" id="medalla_arranque3" name="medalla_arranque3">
                                                    <option value="">Sin Medalla</option>
                                                    <option>Oro</option>
                                                    <option>Plata</option>
                                                    <option>Bronce</option>
                                                </select>
                                            </div>
                                        
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="envion1" class="form-label">1er envion:</label>
                                                <input type="number" class="form-control" id="envion1" name="arranenvion1que1" required>
                                                <select class="form-control mt-1" id="resultado_envion1" name="resultado_envion1">
                                                    <option>Bueno</option>
                                                    <option>Malo</option>
                                                </select>
                                                <select class="form-control mt-1" id="medalla_envion1" name="medalla_envion1">
                                                    <option value="">Sin Medalla</option>
                                                    <option>Oro</option>
                                                    <option>Plata</option>
                                                    <option>Bronce</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="envion2" class="form-label">2do envion:</label>
                                                <input type="number" class="form-control" id="envion2" name="envion2" required>
                                                <select class="form-control mt-1" id="resultado_envion2" name="resultado_envion2">
                                                    <option>Bueno</option>
                                                    <option>Malo</option>
                                                </select>
                                                <select class="form-control mt-1" id="medalla_envion2" name="medalla_envion2">
                                                    <option value="">Sin Medalla</option>
                                                    <option>Oro</option>
                                                    <option>Plata</option>
                                                    <option>Bronce</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="envion3" class="form-label">3er envion:</label>
                                                <input type="number" class="form-control" id="envion3" name="envion3" required>
                                                <select class="form-control mt-1" id="resultado_envion3" name="resultado_envion3">
                                                    <option>Bueno</option>
                                                    <option>Malo</option>
                                                </select>
                                                <select class="form-control mt-1" id="medalla_envion3" name="medalla_envion3">
                                                    <option value="">Sin Medalla</option>
                                                    <option>Oro</option>
                                                    <option>Plata</option>
                                                    <option>Bronce</option>
                                                </select>
                                            </div>
                                        
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="total_arranque" class="form-label">Total Arranque:</label>
                                                <input type="number" class="form-control" id="total_arranque" name="total_arranque" readonly>
                                                <select class="form-control mt-1" id="medalla_total_arranque" name="medalla_total_arranque">
                                                    <option value="">Sin Medalla</option>
                                                    <option>Oro</option>
                                                    <option>Plata</option>
                                                    <option>Bronce</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="total_envion" class="form-label">Total Envión:</label>
                                                <input type="number" class="form-control" id="total_envion" name="total_envion" readonly>
                                                <select class="form-control mt-1" id="medalla_total_envion" name="medalla_total_envion">
                                                    <option value="">Sin Medalla</option>
                                                    <option>Oro</option>
                                                    <option>Plata</option>
                                                    <option>Bronce</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="total_general" class="form-label">Total General:</label>
                                                <input type="number" class="form-control" id="total_general" name="total_general" readonly>
                                                <select class="form-control mt-1" id="medalla_total_general" name="medalla_total_general">
                                                    <option value="">Sin Medalla</option>
                                                    <option>Oro</option>
                                                    <option>Plata</option>
                                                    <option>Bronce</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">Registrar Competencia</button>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary">Save changes</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        function calculateTotals() {
           
            let arranque1 = parseInt(document.getElementById('arranque1').value) || 0;
            let arranque2 = parseInt(document.getElementById('arranque2').value) || 0;
            let arranque3 = parseInt(document.getElementById('arranque3').value) || 0;
            let envion1 = parseInt(document.getElementById('envion1').value) || 0;
            let envion2 = parseInt(document.getElementById('envion2').value) || 0;
            let envion3 = parseInt(document.getElementById('envion3').value) || 0;

            document.getElementById('total_arranque').value = arranque1 + arranque2 + arranque3;
            document.getElementById('total_envion').value = envion1 + envion2 + envion3;
            document.getElementById('total_general').value = document.getElementById('total_arranque').value + document.getElementById('total_envion').value;
        }

        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('change', calculateTotals);
        });
    </script>
</body>
</html>
