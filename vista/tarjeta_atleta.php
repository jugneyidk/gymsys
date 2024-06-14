<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarjeta de Atleta - Halterofilia</title>
    <?php require_once ("comunes/linkcss.php") ?>
</head>

<body>
    <?php require_once ("comunes/menu.php"); ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header text-center">
                        <img src="./img/atleta-foto.png" alt="Foto del Atleta" class="rounded-circle img-thumbnail"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        <h3 class="mt-3">Nombre Completo del Atleta</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="card-text"><strong>Cédula:</strong> 12345678</p>
                                <p class="card-text"><strong>Fecha de Nacimiento:</strong> 01/01/2000</p>
                                <p class="card-text"><strong>Edad:</strong> 22 años</p>
                                <p class="card-text"><strong>Peso:</strong> 70 kg</p>
                                <p class="card-text"><strong>Sexo:</strong> Masculino</p>
                                <p class="card-text"><strong>Categoría:</strong> Senior</p>
                                <p class="card-text"><strong>División:</strong> Peso Medio</p>
                            </div>
                            <div class="col-md-6">
                                <p class="card-text"><strong>Entrenador:</strong> Nombre del Entrenador</p>
                                <p class="card-text"><strong>Representante:</strong> Nombre del Representante</p>
                                <p class="card-text"><strong>Teléfono del Representante:</strong> 123456789</p>
                                <p class="card-text"><strong>Mejor Arranque:</strong> 120 kg</p>
                                <p class="card-text"><strong>Mejor Envión:</strong> 150 kg</p>
                                <p class="card-text"><strong>Total:</strong> 270 kg</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="" class="btn btn-primary">Editar</a>
                        <a href="" class="btn btn-secondary">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <?php require_once ("comunes/footer.php"); ?>
    </footer>
</body>

</html>