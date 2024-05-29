<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
</head>
<body>
<?php require_once("comunes/menu.php"); ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-8">
                <h2>Estadísticas de desarrollo de atletas</h2>
                <canvas id="myChart"></canvas>
            </div>
            <div class="col-lg-4">
                <h2>Notificaciones</h2>
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">¡Importante!</h5>
                            <small>Hace 3 mins</small>
                        </div>
                        <p class="mb-1"><b>Por favor, actualiza tu contraseña</b></p>
                    </a>
                    <!-- Más notificaciones aquí -->
                </div>
                <h2>Noticias</h2>
                <p>Sin noticias.</p>
            </div>
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/chart.min.js"></script>
    <script src="js/menu.js"></script>
</body>
</html>
