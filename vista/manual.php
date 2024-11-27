<?php
$indice = 1;
$sub_indice = 1;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual de Usuario - Gimnasio de Halterofilia "Eddie Suarez"</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        ol {
            list-style-position: inside;
        }

        mark {
            background-color: yellow;
            color: black;
        }

        @media screen and (min-width:992px) {
            img {
                width: 75%;
            }
        }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Manual de
                Usuario<?= isset($permisos["nombre_rol"]) ? " - {$permisos["nombre_rol"]}" : "" ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header bg-primary">
                    <h5 class="offcanvas-title text-white" id="offcanvasNavbarLabel">Menú</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body bg-dark">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Buscar" aria-label="Buscar en el manual"
                            id="buscar">
                        <button class="btn btn-success" id="btnBuscar">Buscar</button>
                        <button class="btn btn-secondary" id="btnSiguiente">Siguiente</button>
                    </div>
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" href="./">Volver a la página principal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="offcanvasNavbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="offcanvasNavbarDropdown">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <main class="container-lg my-3 my-md-5" id="manual">
        <section class="row" id="inicioSesion">
            <div class="col">
                <h2><?= $indice ?>. Inicio de sesión</h2>
            </div>
            <div class="col-12">
                <p>Para acceder al sistema, puedes iniciar sesión de dos maneras:</p>
                <ol>
                    <li>Haciendo <a href="./?p=login" class="fw-bold">clic aquí</a>.</li>
                    <li><strong>A través de las áreas destacadas en la siguiente imagen:</strong> En la página principal
                        del
                        sistema, verás las siguientes zonas resaltadas donde también podrás hacer clic para acceder a la
                        pantalla de inicio
                        de sesión</li>
                </ol>
                <figure class="figure">
                    <img src="./img/manual/1.jpg" alt="Enlaces de inicio de sesión"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Enlaces de inicio de sesión en la página principal.</figcaption>
                </figure>
            </div>
            <div class="col-12">
                <p>En la pantalla de inicio de sesión, encontrarás los siguientes campos que debes completar para
                    acceder al sistema:</p>
                <ol>
                    <li><strong>Usuario:</strong> Ingresa el número de cédula asignado a tu usuario. Asegúrate de que el
                        valor introducido sea un número compuesto únicamente por entre <Strong>7 y 9 dígitos</Strong>.
                    </li>
                    <li><strong>Contraseña:</strong> La contraseña debe cumplir con los siguientes requisitos:
                        <ul>
                            <li>Tener entre <strong>8 y 15 caracteres</strong>.</li>
                            <li>Incluir al menos <strong>1 letra mayúscula</strong>.</li>
                            <li>Incluir al menos <strong>1 letra minúscula</strong>.</li>
                            <li>Incluir al menos <strong>1 número</strong></li>
                            <li>Incluir al menos 1 símbolo de los siguientes: <strong>$ @ ! % * ? &</strong></li>
                        </ul>
                    </li>
                    <li><strong>Olvidé mi contraseña:</strong> Si has olvidado tu contraseña, puedes restablecerla
                        haciendo <a href="./?p=recovery" class="fw-bold">clic aquí</a> o en el enlace "<strong>Olvidé mi
                            contraseña</strong>". Este enlace te llevará a una página donde
                        podrás seguir los pasos para recuperar el acceso a tu cuenta.
                    </li>
                </ol>
                <figure class="figure">
                    <img src="./img/manual/2.jpg" alt="Pantalla de inicio de sesión"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Página de inicio de sesión.</figcaption>
                </figure>
            </div>
            <div class="col" id="recuperarContraseña">
                <h3><?= $indice . "." . $sub_indice ?>. Recuperar contraseña</h3>
                <?php $indice++ ?>
            </div>
            <div class="col-12">
                <p>En la pantalla de recuperar contraseña, podrás solicitar un correo electrónico con un enlace para
                    restablecer tu contraseña. Ten en cuenta lo siguiente:</p>
                <ul>
                    <li>
                        <strong>El enlace enviado será válido solo por 1 hora</strong>, por lo que debes hacer clic en
                        él dentro de ese tiempo para restablecer tu contraseña.
                    </li>
                </ul>
                <p>Para recibir el correo de recuperación, debes ingresar los siguientes datos:</p>
                <ol>
                    <li><strong>Correo electrónico válido registrado en el sistema:</strong> Ingresa la dirección de
                        correo electrónico asociada a tu cuenta en el sistema.
                    </li>
                    <li><strong>Cédula del usuario:</strong> Introduce el número de cédula de la persona registrada con
                        ese correo. Recuerda que el número debe tener entre <strong>7 y 9 dígitos.</strong>
                    </li>
                </ol>
                <figure class="figure">
                    <img src="./img/manual/3.jpg" alt="Pantalla de recuperar contraseña"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Página de recuperar contraseña.</figcaption>
                </figure>
            </div>
        </section>
        <section class="row" id="barraDeNavegacion">
            <div class="col">
                <h2><?= $indice ?>. Barra de Navegación</h2>
                <?php $indice++ ?>
            </div>
            <div class="col-12">
                <p>La barra de navegación te permite acceder rápidamente a las diferentes secciones del sistema. Los
                    elementos principales que encontrarás son:</p>
                <ol>
                    <li><strong>Página Principal:</strong> Te lleva de vuelta a la pantalla inicial del sistema, donde
                        puedes visualizar un resumen general de la información más relevante.</li>
                    <li><strong>Gestionar Usuarios:</strong> Aquí puedes administrar los usuarios del sistema. Puedes
                        gestionar entrenadores, atletas, así como roles y permisos para definir qué acciones puede
                        realizar cada usuario.
                    </li>
                    <li><strong>Asistencias:</strong> Accede a la sección donde puedes registrar y gestionar las
                        asistencias de los atletas a los entrenamientos.</li>
                    <li><strong>Eventos:</strong> Permite gestionar los eventos programados, como competiciones, choques
                        entre otros.</li>
                    <li><strong>Mensualidad: </strong> En esta sección puedes gestionar las mensualidades de los
                        usuarios, realizando registros y consultas relacionadas con los pagos mensuales.</li>
                    <li><strong>WADA:</strong> Accede a la gestión de registro y consultas relacionados con la World
                        Anti-Doping Agency (WADA), para monitorear el cumplimiento de las regulaciones antidopaje de los
                        atletas.</li>
                    <li><strong>Reportes:</strong> Aquí puedes generar y consultar diferentes informes sobre el estado
                        del sistema, los usuarios, eventos, entre otros.</li>
                    <li><strong>Bitácora:</strong> Registra y consulta las actividades realizadas dentro del sistema,
                        con detalles sobre qué usuarios realizaron cada acción.</li>
                    <li><strong>Notificaciones:</strong> Muestra los mensajes o alertas importantes para los usuarios,
                        como recordatorios o eventos próximos.</li>
                    <li><strong>Cerrar Sesión:</strong> Permite cerrar tu sesión de manera segura y salir del sistema.
                    </li>
                </ol>
                <figure class="figure">
                    <img src="./img/manual/5.jpg" alt="Barra de navegación" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Barra de navegación del sistema.</figcaption>
                </figure>
            </div>
        </section>
        <section class="row" id="dashboard">
            <div class="col">
                <h2><?= $indice ?>. Dashboard</h2>
                <?php $indice++ ?>
            </div>
            <div class="col-12">
                <p>En la pantalla principal del <strong>Dashboard</strong> luego de iniciar sesión, se muestra
                    información clave como el número de atletas y entrenadores registrados, las WADAs pendientes, las
                    estadísticas de medallas obtenidas, las notificaciones recientes, y las actividades recientes de la
                    bitácora. Esta vista te permite acceder rápidamente a los datos más relevantes del sistema.</p>
                <figure class="figure">
                    <img src="./img/manual/4.jpg" alt="Pantalla del dashboard" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Pantalla del dashboard.</figcaption>
                </figure>
            </div>
        </section>
        <section class="row" id="gestionarEntrenadores">
            <div class="col">
                <h2><?= $indice ?>. Gestionar Entrenadores</h2>
            </div>
            <div class="col-12">
                <p>En la pantalla de Gestionar Entrenadores, puedes ver la lista de entrenadores registrados y realizar
                    las siguientes acciones: crear, modificar o eliminar registros.</p>
                <figure class="figure">
                    <img src="./img/manual/6.jpg" alt="Pantalla de gestión de entrenadores"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Pantalla de gestión de entrenadores.</figcaption>
                </figure>
            </div>
            <div class="col" id="registrarEntrenador">
                <h3><?= $indice . "." . $sub_indice ?>. Registrar Entrenador</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para registrar la información de un nuevo entrenador, los datos requeridos son:</p>
                <ul>
                    <li><strong>Cédula:</strong> Entre 7 y 9 dígitos numéricos.</li>
                    <li><strong>Nombres:</strong> Solo letras y espacios, con una longitud de 3 a 50 caracteres.</li>
                    <li><strong>Apellidos:</strong> Solo letras y espacios, con una longitud de 3 a 50 caracteres.</li>
                    <li><strong>Género:</strong> Selecciona masculino o femenino.</li>
                    <li><strong>Estado civil:</strong> Soltero, casado, viudo o divorciado.</li>
                    <li><strong>Fecha de nacimiento:</strong> ingresar la fecha en formato estándar.</li>
                    <li><strong>Lugar de nacimiento:</strong> Solo letras y espacios, hasta 100 caracteres.</li>
                    <li><strong>Teléfono:</strong> El formato debe ser 04XXXXXXXXX (10 dígitos, comenzando con "04").
                    </li>
                    <li><strong>Correo electrónico:</strong> Dirección de correo electrónico válida</li>
                    <li><strong>Grado de instrucción:</strong> Solo letras y espacios, hasta 49 caracteres.</li>
                    <li><strong>Contraseña:</strong> Debe tener entre 8 y 15 caracteres, incluyendo al menos:
                        <ul>
                            <li>Tener entre <strong>8 y 15 caracteres</strong>.</li>
                            <li>Incluir al menos <strong>1 letra mayúscula</strong>.</li>
                            <li>Incluir al menos <strong>1 letra minúscula</strong>.</li>
                            <li>Incluir al menos <strong>1 número</strong></li>
                            <li>Incluir al menos 1 símbolo de los siguientes: <strong>$ @ ! % * ? &</strong></li>
                        </ul>
                    </li>
                </ul>
                <figure class="figure">
                    <img src="./img/manual/7.jpg" alt="Ventana de registro de entrenador"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de registro de entrenador.</figcaption>
                </figure>
            </div>
            <div class="col" id="modificarEntrenador">
                <h3><?= $indice . "." . $sub_indice ?>. Modificar Entrenador</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para Modificar la información de un entrenador existente, se requieren los mismos datos que en el <a
                        href="#registrarEntrenador">registro de entrenador</a>.</p>
                <figure class="figure">
                    <img src="./img/manual/8.jpg" alt="Ventana de modificación de entrenador"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de modificación de entrenador.</figcaption>
                </figure>
            </div>
            <div class="col" id="eliminarEntrenador">
                <h3><?= $indice . "." . $sub_indice ?>. Eliminar Entrenador</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para Eliminar la información de un entrenador existente, haz clic en el boton "Eliminar Entrenador"
                    del entrenador que deseas eliminar y confirma la accion.</p>
                <span class="bg-danger-subtle d-block mb-3 text-danger-emphasis p-2">No se podrá eliminar el entrenador
                    si tiene atletas asignados.</span>
                <figure class="figure">
                    <img src="./img/manual/9.jpg" alt="Ventana de confirmación al eliminar entrenador"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de confirmación al eliminar entrenador.</figcaption>
                </figure>
            </div>
        </section>
        <?php
        $indice++;
        $sub_indice = 1; ?>
        <section class="row" id="gestionarAtletas">
            <div class="col">
                <h2><?= $indice ?>. Gestionar Atletas</h2>
            </div>
            <div class="col-12">
                <p>En la pantalla de Gestionar Atletas, puedes ver la lista de atletas registrados y realizar
                    las siguientes acciones: crear, modificar o eliminar registros.</p>
                <figure class="figure">
                    <img src="./img/manual/10.jpg" alt="Pantalla de gestión de atletas"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Pantalla de gestión de atletas.</figcaption>
                </figure>
            </div>
            <div class="col" id="registrarAtleta">
                <h3><?= $indice . "." . $sub_indice ?>. Registrar Atleta</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para registrar la información de un nuevo atleta, los datos requeridos son:</p>
                <ul>
                    <li><strong>Cédula:</strong> Entre 7 y 9 dígitos numéricos.</li>
                    <li><strong>Nombres:</strong> Solo letras y espacios, con una longitud de 3 a 50 caracteres.</li>
                    <li><strong>Apellidos:</strong> Solo letras y espacios, con una longitud de 3 a 50 caracteres.</li>
                    <li><strong>Género:</strong> Selecciona masculino o femenino.</li>
                    <li><strong>Estado civil:</strong> Soltero, casado, viudo o divorciado.</li>
                    <li><strong>Fecha de nacimiento:</strong> ingresar la fecha en formato estándar.</li>
                    <li><strong>Lugar de nacimiento:</strong> Solo letras y espacios, hasta 100 caracteres.</li>
                    <li><strong>Peso:</strong> Números hasta con hasta 2 decimales.</li>
                    <li><strong>Altura:</strong> Números hasta con hasta 2 decimales.</li>
                    <li><strong>Tipo de Atleta:</strong> El tipo de atleta asignado para el cobro de las mensualidades.
                    </li>
                    <li><strong>Entrenador asignado:</strong> El entrenador asignado a este atleta.</li>
                    <li><strong>Teléfono:</strong> El formato debe ser 04XXXXXXXXX (10 dígitos, comenzando con "04").
                    </li>
                    <li><strong>Correo electrónico:</strong> Dirección de correo electrónico válida</li>
                    <li><strong>Contraseña:</strong> Debe tener entre 8 y 15 caracteres, incluyendo al menos:
                        <ul>
                            <li>Tener entre <strong>8 y 15 caracteres</strong>.</li>
                            <li>Incluir al menos <strong>1 letra mayúscula</strong>.</li>
                            <li>Incluir al menos <strong>1 letra minúscula</strong>.</li>
                            <li>Incluir al menos <strong>1 número</strong></li>
                            <li>Incluir al menos 1 símbolo de los siguientes: <strong>$ @ ! % * ? &</strong></li>
                        </ul>
                    </li>
                </ul>
                <figure class="figure">
                    <img src="./img/manual/11.jpg" alt="Ventana de registro de atleta"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de registro de atleta.</figcaption>
                </figure>
            </div>
            <div class="col" id="modificarAtleta">
                <h3><?= $indice . "." . $sub_indice ?>. Modificar Atleta</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para Modificar la información de un atleta existente, se requieren los mismos datos que en el <a
                        href="#registrarAtleta">registro de atleta</a>.</p>
                <figure class="figure">
                    <img src="./img/manual/12.jpg" alt="Ventana de modificación de atleta"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de modificación de atleta.</figcaption>
                </figure>
            </div>
            <div class="col" id="eliminarAtleta">
                <h3><?= $indice . "." . $sub_indice ?>. Eliminar Atleta</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para Eliminar la información de un atleta existente, haz clic en el boton "Eliminar Atleta"
                    del atleta que deseas eliminar y confirma la accion.</p>
                <span class="bg-danger-subtle d-block mb-3 text-danger-emphasis p-2">No se podrá eliminar el atleta
                    si tiene mensualidades registradas.</span>
                <figure class="figure">
                    <img src="./img/manual/9.jpg" alt="Ventana de confirmación al eliminar atleta"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de confirmación al eliminar atleta.</figcaption>
                </figure>
            </div>
        </section>
        <?php
        $indice++;
        $sub_indice = 1; ?>
        <section class="row" id="gestionarRolespermisos">
            <div class="col">
                <h2><?= $indice ?>. Gestionar Roles y Permisos</h2>
            </div>
            <div class="col-12">
                <p>En la Pantalla de <strong>Gestionar Roles y Permisos</strong>, puedes definir y modificar los roles
                    de los usuarios dentro del sistema, así como asignarles los permisos específicos que determinarán
                    qué acciones pueden realizar. Los roles y permisos son fundamentales para asegurar que los usuarios
                    tengan acceso solo a las funciones que necesitan para cumplir con sus tareas.</p>
                <figure class="figure">
                    <img src="./img/manual/10.jpg" alt="Pantalla de gestión de roles y permisos"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Pantalla de gestión de roles y permisos.</figcaption>
                </figure>
            </div>
            <div class="col" id="registrarAtleta">
                <h3><?= $indice . "." . $sub_indice ?>. Registrar Atleta</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para registrar la información de un nuevo atleta, los datos requeridos son:</p>
                <ul>
                    <li><strong>Cédula:</strong> Entre 7 y 9 dígitos numéricos.</li>
                    <li><strong>Nombres:</strong> Solo letras y espacios, con una longitud de 3 a 50 caracteres.</li>
                    <li><strong>Apellidos:</strong> Solo letras y espacios, con una longitud de 3 a 50 caracteres.</li>
                    <li><strong>Género:</strong> Selecciona masculino o femenino.</li>
                    <li><strong>Estado civil:</strong> Soltero, casado, viudo o divorciado.</li>
                    <li><strong>Fecha de nacimiento:</strong> ingresar la fecha en formato estándar.</li>
                    <li><strong>Lugar de nacimiento:</strong> Solo letras y espacios, hasta 100 caracteres.</li>
                    <li><strong>Peso:</strong> Números hasta con hasta 2 decimales.</li>
                    <li><strong>Altura:</strong> Números hasta con hasta 2 decimales.</li>
                    <li><strong>Tipo de Atleta:</strong> El tipo de atleta asignado para el cobro de las mensualidades.
                    </li>
                    <li><strong>Entrenador asignado:</strong> El entrenador asignado a este atleta.</li>
                    <li><strong>Teléfono:</strong> El formato debe ser 04XXXXXXXXX (10 dígitos, comenzando con "04").
                    </li>
                    <li><strong>Correo electrónico:</strong> Dirección de correo electrónico válida</li>
                    <li><strong>Contraseña:</strong> Debe tener entre 8 y 15 caracteres, incluyendo al menos:
                        <ul>
                            <li>Tener entre <strong>8 y 15 caracteres</strong>.</li>
                            <li>Incluir al menos <strong>1 letra mayúscula</strong>.</li>
                            <li>Incluir al menos <strong>1 letra minúscula</strong>.</li>
                            <li>Incluir al menos <strong>1 número</strong></li>
                            <li>Incluir al menos 1 símbolo de los siguientes: <strong>$ @ ! % * ? &</strong></li>
                        </ul>
                    </li>
                </ul>
                <figure class="figure">
                    <img src="./img/manual/11.jpg" alt="Ventana de registro de atleta"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de registro de atleta.</figcaption>
                </figure>
            </div>
            <div class="col" id="modificarAtleta">
                <h3><?= $indice . "." . $sub_indice ?>. Modificar Atleta</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para Modificar la información de un atleta existente, se requieren los mismos datos que en el <a
                        href="#registrarAtleta">registro de atleta</a>.</p>
                <figure class="figure">
                    <img src="./img/manual/12.jpg" alt="Ventana de modificación de atleta"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de modificación de atleta.</figcaption>
                </figure>
            </div>
            <div class="col" id="eliminarAtleta">
                <h3><?= $indice . "." . $sub_indice ?>. Eliminar Atleta</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para Eliminar la información de un atleta existente, haz clic en el boton "Eliminar Atleta"
                    del atleta que deseas eliminar y confirma la accion.</p>
                <span class="bg-danger-subtle d-block mb-3 text-danger-emphasis p-2">No se podrá eliminar el atleta
                    si tiene mensualidades registradas.</span>
                <figure class="figure">
                    <img src="./img/manual/9.jpg" alt="Ventana de confirmación al eliminar atleta"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de confirmación al eliminar atleta.</figcaption>
                </figure>
            </div>
        </section>
    </main>
    <?php require_once("comunes/footer.php"); ?>
    <script type="module" src="./js/manual.js"></script>
</body>

</html>