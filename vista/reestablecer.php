<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Gimnasio 'Eddie Suarez'</title>
    <?php require_once("comunes/linkcss.php"); ?>
    <link href="css/login.css" rel="stylesheet">
</head>
<body>

<div class="login-body">
    <div class="login-container">
        <div class="logo-container text-center mb-4">
            <h1 class="gym-title">
                <i class="fas fa-dumbbell"></i> Gimnasio 'Eddie Suarez'
            </h1>
        </div>

        <div class="login-card">
            <h2 class="text-center">Restablecer Contraseña</h2>
            <form  method="POST" id="reset-form">
                <input type="hidden" id="token" name="token" value="<?php echo $_GET['token']; ?>">

                <div class="form-group">
                    <label for="nueva_contraseña">Nueva Contraseña:</label>
                    <input type="password" class="form-control" id="nueva_contraseña" name="nueva_contraseña" required>
                    <div id="snueva_contraseña" class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="confirmar_contraseña">Confirmar Contraseña:</label>
                    <input type="password" class="form-control" id="confirmar_contraseña" name="confirmar_contraseña" required>
                    <div id="sconfirmar_contraseña" class="invalid-feedback"></div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-info rounded-2" id="submit-reset">Restablecer Contraseña</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/sweetalert.js"></script>
<script type="module" src="js/reestablecer.js"></script>
</body>
</html>
