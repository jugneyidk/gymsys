<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Mensualidad - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
    <link rel="stylesheet" href="css/all.min.css">
</head>
<body style="background-color: #f8f9fa;">
<?php require_once("comunes/menu.php"); ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header text-center">
                    <h2>Registrar Mensualidad</h2>
                </div>
                <div class="card-body">
                    <form id="mensualidadForm" action="" method="POST">
                        <div class="mb-3">
                            <label for="id_atleta" class="form-label">Atleta:</label>
                            <input type="text" class="form-control" id="id_atleta" name="id_atleta" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_mensualidad" class="form-label">Tipo de Mensualidad:</label>
                            <select class="form-control" id="tipo_mensualidad" name="tipo_mensualidad" required>
                                <option value="">Seleccione el tipo</option>
                                <option value="normal">Normal</option>
                                <option value="especial">Especial</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cobro" class="form-label">Cobro:</label>
                            <input type="number" class="form-control" id="cobro" name="cobro" required>
                        </div>
                        <div class="mb-3">
                            <label for="pago" class="form-label">Pago:</label>
                            <input type="number" class="form-control" id="pago" name="pago" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha:</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Registrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once("comunes/footer.php"); ?>
<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/mensualidad.js"></script>
</body>
</html>
