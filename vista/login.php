<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema</title>
    <?php require_once ("comunes/linkcss.php"); ?>
</head>

<body>
    <div class="login-body">
        <div class="login-container">
            <div class="login-card">
                <h2 class="text-center">Iniciar Sesión</h2>
                <form action="" method="POST" id="login">
                    <div class="form-group">
                        <label for="id_usuario">Usuario:</label>
                        <input type="text" class="form-control" id="id_usuario" name="id_usuario" required>
                    </div>
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="password">Contraseña:</label>
                            <small><a href="#"
                                    class="link-primary link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover">Olvidé
                                    mi contraseña</a></small>
                        </div>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary rounded-2" id="submit">Ingresar</button>
                        <button type="button" class="btn btn-outline-dark rounded-2"
                            onclick="history.back();">Volver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/sweetalert.js"></script>
    <script src="js/login.js"></script>
</body>

</html>