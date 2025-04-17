<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
    <link rel="stylesheet" href="css/all.min.css">
</head>

<body class="d-flex flex-column vh-100">
    <?php require_once("comunes/menu.php"); ?>
    <main class="mb-4">
        <div class="container-lg mt-4">
            <div class="row justify-content-center">
                <div class="container-lg mt-4">
                    <div class="row">
                        <!-- Atletas -->
                        <div class="col-12 col-sm-6 col-lg-3 mb-3 d-flex align-items-stretch">
                            <div class="card text-white bg-primary flex-grow-1">
                                <div class="card-header"><i class="fas fa-users"></i> Atletas</div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $atletas; ?></h5>
                                    <small class="card-text">Total de atletas registrados.</small>
                                </div>
                            </div>
                        </div>
                        <!-- Entrenadores -->
                        <div class="col-12 col-sm-6 col-lg-3 mb-3 d-flex align-items-stretch">
                            <div class="card text-white bg-success flex-grow-1">
                                <div class="card-header"><i class="fas fa-user-friends"></i> Entrenadores</div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $entrenadores; ?></h5>
                                    <small class="card-text">Entrenadores registrados.</small>
                                </div>
                            </div>
                        </div>
                        <!-- Reportes -->
                        <div class="col-12 col-sm-6 col-lg-3 mb-3 d-flex align-items-stretch">
                            <div class="card text-white bg-warning flex-grow-1">
                                <div class="card-header"><i class="fas fa-chart-line"></i> Acciones</div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $reportes; ?></h5>
                                    <small class="card-text">Acciones realizadas este mes.</small>
                                </div>
                            </div>
                        </div>

                        <!-- WADA -->
                        <div class="col-12 col-sm-6 col-lg-3 mb-3 d-flex align-items-stretch">
                            <div class="card text-white bg-danger flex-grow-1">
                                <div class="card-header"><i class="fas fa-cogs"></i> WADA</div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $wadas_pendientes; ?></h5>
                                    <small class="card-text">WADAS pendientes.</small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4 col-lg-4">
                    <h5>Estadísticas de asistencias</h5>
                   <canvas id="asistenciasChart"></canvas>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <h5>Edades comprendidas de Atletas</h5>
                    <canvas id="edadAtletasChart"></canvas>
                </div>
                <div class="col-12 col-lg-4"  style="width:18%">
                    <h5>Genero predominante</h5>
                    <canvas id="generoChart"></canvas>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6 d-flex align-self-stretch my-4">
                    <div class="card w-100">
                        <div class="card-header">
                            <i class="fas fa-bell"></i> Notificaciones Recientes
                        </div>
                        <ul class="list-group list-group-flush card-body p-0 d-flex justify-content-center">
                            <?php
                            if (!empty($ultimas_notificaciones)):
                                foreach ($ultimas_notificaciones as $notificacion): ?>
                                    <li
                                        class="list-group-item d-flex justify-content-between<?= !$notificacion["leida"] ? " list-group-item-warning" : "" ?>">
                                        <div>
                                            <strong><?= $notificacion['titulo']; ?></strong>
                                            <small class="d-block"><?= $notificacion['mensaje']; ?></small>
                                        </div>
                                        <small class="text-muted text-nowrap"
                                            title="<?= $notificacion["fecha_creacion"] ?>"><?= $notificacion['fecha_corta']; ?></small>
                                    </li>
                                <?php endforeach;
                            else: ?>
                                <li class="list-group-item d-flex justify-content-center align-items-center h-100">
                                    <span class="h6 text-muted">No hay notificaciones</span>
                                </li>
                                <?php
                            endif; ?>
                        </ul>
                    </div>
                </div>
                <?php if (isset($ultimas_acciones) && $ultimas_acciones["leer"]): ?>
                    <div class="col-12 col-lg-6 d-flex align-self-stretch my-4">
                        <div class="card w-100">
                            <div class="card-header">
                                <i class="fas fa-history"></i> Actividad Reciente
                            </div>
                            <ul class="list-group list-group-flush card-body p-0 d-flex justify-content-center">
                                <?php
                                if (!empty($ultimas_acciones["acciones"])):
                                    foreach ($ultimas_acciones["acciones"] as $accion): ?>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div class="text-wrap">
                                                <strong><?php echo $accion['nombre'] . ' ' . $accion['apellido']; ?></strong>
                                                realizó la acción <strong><?php echo $accion['accion']; ?></strong> en el modulo
                                                <strong><?php echo $accion['modulo']; ?></strong>.
                                            </div>
                                            <small class="text-muted text-nowrap"
                                                title="<?= $accion["fecha"] ?>"><?= $accion['fecha_corta']; ?></small>
                                        </li>
                                    <?php endforeach;
                                else: ?>
                                    <li class="list-group-item d-flex justify-content-center align-items-center">
                                        <span class="h6 text-muted">No hay actividad reciente</span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col d-flex align-self-stretch my-4">
                    <div class="card w-100">
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
            </div>

    </main>
    <?php require_once("comunes/footer.php"); ?>

    <script src="js/chart.min.js"></script>
    <script type="module" src="js/dashboard.js"></script>
</body>

</html>