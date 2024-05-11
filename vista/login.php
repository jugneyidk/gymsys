<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema</title>
    <?php require_once("comunes/linkcss.php"); ?>
</head>
<body class="bodystylelog">

<div class="card">
    <div class="row g-0">
        <div class="col-md-6 card-img-left"></div> <!-- Imagen del condominio -->
        <div class="col-md-4">
            <div class="card-body">
                <h3 class="card-title text-center">Iniciar Sesión</h3>
                <form>
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="user" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-info">Entrar</button>
                    </div>
                </form>
                <a href="#">¿Ha olvidado su contraseña?</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
