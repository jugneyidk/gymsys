<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema</title>
    <?php require_once ("comunes/linkcss.php"); ?>
    <link href="css/login.css" rel="stylesheet">
</head>

<body>
  
    <div class="login-body">
       
        <div class="login-container">
        <div class="logo-container text-center mb-4">
            <h1 class="gym-title">
        <i class="fas fa-dumbbell"></i> Gimnasio 'Eddy Suarez'
    </h1>
    </div>

            <div class="login-card">
                <h2 class="text-center">Iniciar Sesión</h2>
                <form action="" method="POST" id="login">
                    <div class="form-group">
                        <label for="id_usuario">Usuario:</label>
                        <input type="text" class="form-control" id="id_usuario" name="id_usuario" tabindex="1">
                        <div id="susuario" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="password">Contraseña:</label>
                            <small><a href="#"
                                    class="link-primary link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover" tabindex="3">Olvidé
                                    mi contraseña</a></small>
                        </div>
                        <input type="password" class="form-control" id="password" name="password" tabindex="2">
                        <div id="spassword" class="invalid-feedback"></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-info rounded-2" id="submit">Ingresar</button>
                        <button type="button" class="btn btn-light rounded-2" onclick="history.back();">Volver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="mini-menu">
        <div class="menu-icons">
            <a href="./" title="Ir al inicio"><i class="fas fa-home"></i></a>
            <a href="https://wa.me/584245681343" target="_blank" title="Contactar por WhatsApp"><i class="fab fa-whatsapp"></i></a>
            <a href="mailto:jugneycontacto@correo.com" title="Enviar un correo"><i class="fas fa-envelope"></i></a>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/sweetalert.js"></script>
    <script src="js/login.js"></script>
</body>

</html>
