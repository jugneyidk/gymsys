<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Gimnasio 'Eddie Suarez'</title>
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
                <h2 class="text-center">Reestablecer Contraseña</h2>
                <form action="" method="POST" id="recovery-form">
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="email" name="email">
                        <div id="semail" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="cedula">Cédula:</label>
                        <input type="text" class="form-control" id="cedula" name="cedula">
                        <div id="scedula" class="invalid-feedback"></div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-info rounded-2" id="recover-submit">Enviar</button>
                        <button type="button" class="btn btn-light rounded-2" onclick="history.back();">Volver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/popper.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/sweetalert.js"></script>
    <script type="module" src="js/recovery.js"></script>
</body>

</html>