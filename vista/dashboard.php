<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema</title>
    <?php require_once ("comunes/linkcss.php"); ?>
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body class="d-flex flex-column vh-100">
    <?php require_once ("comunes/menu.php"); ?>
    <main class="mb-4">
        <div class="container-lg mt-4">
            <div class="row justify-content-center">
            <div class="container-lg mt-4">
    <div class="row">
     <!-- Atletas -->
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-header"><i class="fas fa-users"></i> Atletas</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $atletas; ?></h5>
                    <p class="card-text">Total de atletas registrados.</p>
                </div>
            </div>
        </div>

        <!-- Entrenadores -->
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-header"><i class="fas fa-user-friends"></i> Entrenadores</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $entrenadores; ?></h5>
                    <p class="card-text">Entrenadores registrados.</p>
                </div>
            </div>
        </div>

        <!-- Reportes -->
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-header"><i class="fas fa-chart-line"></i> Reportes</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $reportes; ?></h5>
                    <p class="card-text">Reportes generados este mes.</p>
                </div>
            </div>
        </div>

        <!-- WADA -->
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-header"><i class="fas fa-cogs"></i> WADA</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $wadas_pendientes; ?></h5>
                    <p class="card-text">WADAS pendientes.</p>
                </div>
            </div>
        </div>

    </div>
</div>
                <div class="col-12 col-lg-4">
                    <h5>Estadísticas de Desarrollo de Atletas</h5>
                    <canvas id="myChart"></canvas>
                </div>
                <div class="col-12 col-lg-4 mt-4">
                <h5>Estadísticas de Desarrollo de Atletas</h5>
                <canvas id="myChart1"></canvas>
                </div>
                <div class="col-12 col-lg-4 mt-4">
                 <h5>Progreso Semanal de Atletas</h5>
                  <canvas id="progressChart"></canvas>
                </div>

                <div class="col-12 col-md-6 col-lg-5">
                <h4>Últimos Atletas Registrados</h4>
    <div class="card">
        <div class="card-header">
            <i class="fas fa-table"></i> Tabla de Atletas
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Género</th>
                        <th>Fecha de Nacimiento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimos_atletas as $index => $atleta): ?>
                        <tr>
                            <th scope="row"><?php echo $index + 1; ?></th>
                            <td><?php echo $atleta['nombre']; ?></td>
                            <td><?php echo $atleta['apellido']; ?></td>
                            <td><?php echo $atleta['genero']; ?></td>
                            <td><?php echo $atleta['fecha_nacimiento']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                      </div>
                 </div>
            </div>
                
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="mt-3 mt-lg-0">
                        <h4>Notificaciones</h4>
                        <div class="card w-100">
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
                    </div>
                </div>
                <div class="col-12 col-md-2 col-lg-4">
                     <div class="mt-3">
                        <h4>Actividad Reciente</h4>
                     <div class="card w-100">
                      <div class="card-header">
                         <i class="fas fa-history"></i> Últimas Acciones
                    </div>
            <ul class="list-group list-group-flush">
                <?php foreach ($ultimas_acciones as $accion): ?>
                    <li class="list-group-item">
                        <strong><?php echo $accion['nombre'] . ' ' . $accion['apellido']; ?></strong>
                        realizó la acción <strong><?php echo $accion['accion']; ?></strong>.
                        <small class="text-muted"><?php echo $accion['fecha']; ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
            </div>
    </main>
    <?php require_once ("comunes/footer.php"); ?>

    <script src="js/chart.min.js"></script>
    <script src="js/menu.js"></script>
</body>

</html>