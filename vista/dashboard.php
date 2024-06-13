<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
<?php require_once("comunes/menu.php"); ?>

<div class="container-fluid mt-4">
    <div class="row">
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header"><i class="fas fa-users"></i> Atletas</div>
                <div class="card-body">
                    <h5 class="card-title">120</h5>
                    <p class="card-text">Número total de atletas registrados.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header"><i class="fas fa-user-friends"></i> Entrenadores</div>
                <div class="card-body">
                    <h5 class="card-title">15</h5>
                    <p class="card-text">Número total de entrenadores registrados.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header"><i class="fas fa-chart-line"></i> Reportes</div>
                <div class="card-body">
                    <h5 class="card-title">35</h5>
                    <p class="card-text">Reportes generados este mes.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header"><i class="fas fa-cogs"></i> WADA</div>
                <div class="card-body">
                    <h5 class="card-title">5</h5>
                    <p class="card-text">WADAS pendientes.</p>
                </div>
            </div>
        </div>
    </div>

        <div class="col-lg-5">
            <h2>Estadísticas de Desarrollo de Atletas</h2>
            <canvas id="myChart"></canvas>
        </div>

        <div class="col-lg-4">
            <h2>Atletas Registrados</h2>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-table"></i> Tabla de Atletas
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Género</th>
                                <th>Edad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Juan</td>
                                <td>Pérez</td>
                                <td>Masculino</td>
                                <td>25</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-2">
            <h2>Notificaciones</h2>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-bell"></i> Notificaciones Recientes
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="my-0">¡Importante!</h6>
                            <small class="text-muted">Por favor, actualiza tu contraseña</small>
                        </div>
                        <span class="text-muted">Hace 3 mins</span>
                    </li>
                    
                </ul>
            </div>

            <div class="">
            <h2>Actividad Reciente</h2>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-history"></i> Actividad Reciente
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Juan Pérez</strong> se ha inscrito en el gimnasio.
                        <small class="text-muted">Hace 1 hora</small>
                    </li>
                    
                </ul>
            </div>
            
        </div>
    </div>
    
      
    <div class="row mt-2">
        
    </div>
</div>

    <?php require_once("comunes/footer.php"); ?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/chart.min.js"></script>
<script src="js/menu.js"></script>
<script src="public/js/dashboard.js"></script>

</body>

</html>
