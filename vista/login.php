<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
</head>
<body class="bodystylelog">

<div class="card login-card">
    <div class="row no-gutters">
        <div class="col-md-6 login-card-img"></div> <!-- Columna para la imagen -->
        <div class="col-md-6">
            <div class="card-body">
                <h3 class="card-title text-center">Iniciar Sesión</h3>
                <form action="tu-script-de-login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
