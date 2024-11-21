<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarjeta de Atleta - Halterofilia</title>
    <?php require_once("comunes/linkcss.php") ?>
</head>

<body class="bg-light">
    <?php require_once("comunes/menu.php") ?>
    <main class="container-md my-3 my-md-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header text-center bg-white">
                        <img src="./img/atleta-foto.png" alt="Foto del Atleta" class="rounded-circle img-thumbnail"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        <h3 class="mt-3"><?php echo $atleta["atleta"]["nombre"] . " " . $atleta["atleta"]["apellido"] ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="card-text"><strong>Cédula: </strong><?php echo $atleta["atleta"]["cedula"] ?>
                                </p>
                                <p class="card-text"><strong>Fecha de Nacimiento:
                                    </strong><?php echo $atleta["atleta"]["fecha_nacimiento"] ?></p>
                                <p class="card-text"><strong>Edad: </strong>
                                    </strong><?php echo $edad = (new DateTime())->diff(new DateTime($atleta["atleta"]["fecha_nacimiento"]))->y; ?>
                                    años
                                </p>
                                <p class="card-text"><strong>Peso: </strong><?php echo $atleta["atleta"]["peso"] ?> kg
                                </p>
                                <p class="card-text"><strong>Altura: </strong><?php echo $atleta["atleta"]["altura"] ?>
                                    cm</p>
                                <p class="card-text"><strong>Sexo: </strong><?php echo $atleta["atleta"]["genero"] ?>
                                </p>
                                <p class="card-text"><strong>Categoría:</strong> Senior</p>
                                <p class="card-text"><strong>División:</strong> Peso Medio</p>
                            </div>
                            <div class="col-md-6">
                                <p class="card-text"><strong>Entrenador:</strong>
                                    <?php echo $atleta["atleta"]["nombre_entrenador"] ?></p>
                                <p class="card-text"><strong>Representante:</strong> Nombre del Representante</p>
                                <p class="card-text"><strong>Teléfono del Representante:</strong> 123456789</p>
                                <p class="card-text"><strong>Mejor Arranque:</strong> N/A</p>
                                <p class="card-text"><strong>Mejor Envión:</strong> N/A</p>
                                <p class="card-text"><strong>Total:</strong> N/A</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="" class="btn btn-secondary">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require_once("comunes/footer.php"); ?>
</body>

</html>