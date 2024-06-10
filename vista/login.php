<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <h2 class="text-center">Iniciar Sesión</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Usuario:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                <button type="button" class="btn btn-secondary btn-block" onclick="history.back();">Volver</button>
            </form>
        </div>
        <footer class="text-center mt-3">
            <p>&copy; 2024 Gimnasio Eddy Suarez UPTAEB. Todos los derechos reservados.</p>
        </footer>
    </div>
    <script src="jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
