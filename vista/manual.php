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
    <nav class="navbar navbar-dark bg-primary sticky-top" id="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Manual de
                Usuario<?= isset($permisos["nombre_rol"]) ? " - {$permisos["nombre_rol"]}" : "" ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Menú de navegación">
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="inicioSesionDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Inicio de sesión
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="inicioSesionDropdown">
                                <li><a class="dropdown-item" href="#inicioSesion">Inicio de sesión</a></li>
                                <li><a class="dropdown-item" href="#recuperarContraseña">Recuperar contraseña</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#barraDeNavegacion">Barra de Navegación</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="gestionarEntrenadoresDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Gestionar Entrenadores
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="gestionarEntrenadoresDropdown">
                                <li><a class="dropdown-item" href="#gestionarEntrenadores">Gestionar Entrenadores</a>
                                </li>
                                <li><a class="dropdown-item" href="#registrarEntrenador">Registrar Entrenador</a></li>
                                <li><a class="dropdown-item" href="#modificarEntrenador">Modificar Entrenador</a></li>
                                <li><a class="dropdown-item" href="#eliminarEntrenador">Eliminar Entrenador</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="gestionarAtletasDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Gestionar Atletas
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="gestionarAtletasDropdown">
                                <li><a class="dropdown-item" href="#gestionarAtletas">Gestionar Atletas</a></li>
                                <li><a class="dropdown-item" href="#registrarAtleta">Registrar Atleta</a></li>
                                <li><a class="dropdown-item" href="#modificarAtleta">Modificar Atleta</a></li>
                                <li><a class="dropdown-item" href="#eliminarAtleta">Eliminar Atleta</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="gestionarRolespermisosDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Gestionar Roles y Permisos
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="gestionarRolespermisosDropdown">
                                <li><a class="dropdown-item" href="#gestionarRolespermisos">Gestionar Roles y
                                        Permisos</a></li>
                                <li><a class="dropdown-item" href="#crearRol">Crear Rol</a></li>
                                <li><a class="dropdown-item" href="#modificarRol">Modificar Rol</a></li>
                                <li><a class="dropdown-item" href="#eliminarRol">Eliminar Rol</a></li>
                                <li><a class="dropdown-item" href="#asignarRol">Asignar Rol</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="gestionarAsistenciasDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Gestionar Asistencias
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="gestionarAsistenciasDropdown">
                                <li><a class="dropdown-item" href="#gestionarAsistencias">Gestionar Asistencias</a></li>
                                <li><a class="dropdown-item" href="#guardarAsistencias">Guardar Asistencias</a></li>
                                <li><a class="dropdown-item" href="#eliminarAsistencias">Eliminar Asistencias del
                                        día</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="gestionarEventosDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Gestionar Eventos/Competencias
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="gestionarEventosDropdown">
                                <li><a class="dropdown-item" href="#gestionarEventos">Gestionar Eventos/Competencias</a>
                                </li>
                                <li><a class="dropdown-item" href="#registrarEvento">Registrar Evento</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#registrarCategoria">Registrar Categoría</a></li>
                                <li><a class="dropdown-item" href="#editarCategoria">Editar Categoría</a></li>
                                <li><a class="dropdown-item" href="#eliminarCategoria">Eliminar Categoría</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#registrarSubs">Registrar Subs</a></li>
                                <li><a class="dropdown-item" href="#editarSubs">Editar Subs</a></li>
                                <li><a class="dropdown-item" href="#eliminarSubs">Eliminar Subs</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#registrarTipoCompetencia">Registrar Tipo de
                                        Competencia</a></li>
                                <li><a class="dropdown-item" href="#editarTipoCompetencia">Editar Tipo de
                                        Competencia</a></li>
                                <li><a class="dropdown-item" href="#eliminarTipoCompetencia">Eliminar Tipo de
                                        Competencia</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#inscribirParticipante">Inscribir Atleta a una
                                        competencia</a></li>
                                <li><a class="dropdown-item" href="#registrarResultados">Registrar resultados de un
                                        Atleta</a></li>
                                <li><a class="dropdown-item" href="#modificarEvento">Modificar Evento</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#cerrarEvento">Cerrar Evento</a></li>
                                <li><a class="dropdown-item" href="#consultarEventosAnteriores">Consultar eventos
                                        anteriores</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="gestionarMensualidadDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Gestionar Mensualidad
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="gestionarMensualidadDropdown">
                                <li><a class="dropdown-item" href="#gestionarMensualidad">Gestionar Mensualidad</a></li>
                                <li><a class="dropdown-item" href="#registrarMensualidad">Registrar Mensualidad</a></li>
                                <li><a class="dropdown-item" href="#consultarPagosRegistrados">Consultar Pagos
                                        Registrados</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="gestionarWADADropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Gestionar WADA
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="gestionarWADADropdown">
                                <li><a class="dropdown-item" href="#gestionarWADA">Gestionar WADA</a></li>
                                <li><a class="dropdown-item" href="#registrarWADA">Registrar WADA</a></li>
                                <li><a class="dropdown-item" href="#modificarWADA">Modificar WADA</a></li>
                                <li><a class="dropdown-item" href="#eliminarWADA">Eliminar WADA</a></li>
                                <li><a class="dropdown-item" href="#consultarWADAPorVencer">Consultar WADA próximos a
                                        vencer</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="consultarBitacoraDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Consultar Bitácora
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="consultarBitacoraDropdown">
                                <li><a class="dropdown-item" href="#consultarBitacora">Consultar Bitácora</a></li>
                                <li><a class="dropdown-item" href="#consultarAccionBitacora">Consultar Acción en la
                                        Bitácora</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="verNotificacionesDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Ver Notificaciones
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="verNotificacionesDropdown">
                                <li><a class="dropdown-item" href="#verNotificaciones">Ver Notificaciones</a></li>
                                <li><a class="dropdown-item" href="#marcarTodoComoLeido">Marcar notificaciones como
                                        leídas</a></li>
                                <li><a class="dropdown-item" href="#verTodasLasNotificaciones">Ver todas las
                                        Notificaciones</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#cerrarSesion">Cerrar Sesión</a>
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
                    <img src="./img/manual/13.jpg" alt="Pantalla de gestión de roles y permisos"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Pantalla de gestión de roles y permisos.</figcaption>
                </figure>
            </div>
            <div class="col" id="crearRol">
                <h3><?= $indice . "." . $sub_indice ?>. Crear Rol</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Puedes crear nuevos roles personalizados, asignando un nombre definir su
                    propósito y las acciones que pueden realizar los usuarios que pertenezcan a ese rol.</p>
                <p>Se debe ingresar un nombre con letras y espacios, entre 3 y 50 caracteres. Además, se debe elegir
                    dependiendo del caso, los permisos correspondientes que se desea que tenga el usuario en la lista de
                    permisos.</p>
                <figure class="figure">
                    <img src="./img/manual/14.jpg" alt="Ventana de creacion de rol y selección de permisos"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de creacion de rol y selección de permisos.</figcaption>
                </figure>
            </div>
            <div class="col" id="modificarRol">
                <h3><?= $indice . "." . $sub_indice ?>. Modificar Rol</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para Modificar la información de un rol existente, se requieren los mismos datos que en la <a
                        href="#crearRol">creación de rol</a>.</p>
                <figure class="figure">
                    <img src="./img/manual/15.jpg" alt="Ventana de modificación de rol"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de modificación de rol.</figcaption>
                </figure>
            </div>
            <div class="col" id="eliminarRol">
                <h3><?= $indice . "." . $sub_indice ?>. Eliminar Rol</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para Eliminar un rol existente, haz clic en el boton "Eliminar rol"
                    del rol que deseas eliminar y confirma la accion.</p>
                <span class="bg-danger-subtle d-block mb-3 text-danger-emphasis p-2">No se podrá eliminar un rol si
                    tiene usuarios asignados.</span>
                <figure class="figure">
                    <img src="./img/manual/16.jpg" alt="Ventana de confirmación al eliminar un rol"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de confirmación al eliminar un rol.</figcaption>
                </figure>
            </div>
            <div class="col" id="asignarRol">
                <h3><?= $indice . "." . $sub_indice ?>. Asignar Rol</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para asignar un rol a un usuario existente, haz clic en el boton "Asignar Rol"
                    para mostrar la ventana de asignación.</p>
                <span class="bg-info-subtle d-block mb-3 text-info-emphasis p-2">Debes ingresar una cédula válida ya
                    registrada para poder asignar el rol.</span>
                <figure class="figure">
                    <img src="./img/manual/17.jpg" alt="Botón de asignación de rol"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón de asignación de rol.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/18.jpg" alt="Ventana de asignación de rol"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de asignación de rol.</figcaption>
                </figure>
            </div>
        </section>
        <?php
        $indice++;
        $sub_indice = 1; ?>
        <section class="row" id="gestionarAsistencias">
            <div class="col">
                <h2><?= $indice ?>. Gestionar Asistencias</h2>
            </div>
            <div class="col-12">
                <p>En la Pantalla de <strong>Gestión de Asistencias</strong>, puedes guardar y eliminar la asistencia de
                    los atletas a los entrenamientos. Esta pantalla permite hacer un seguimiento detallado de la
                    participación de cada atleta en las sesiones de entrenamiento programadas.</p>
                <p>Al seleccionar una fecha, se podrán observar las asistencias registradas en ese día.</p>
                <figure class="figure">
                    <img src="./img/manual/19.jpg" alt="Pantalla de gestión de asistencias"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Pantalla de gestión de asistencias.</figcaption>
                </figure>
            </div>
            <div class="col" id="guardarAsistencias">
                <h3><?= $indice . "." . $sub_indice ?>. Guardar Asistencias</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Puedes registrar la asistencia de cada atleta a un entrenamiento específico. Para hacerlo, selecciona
                    la fecha y marca los atletas que asistieron, indicando si estuvieron presentes o si se
                    ausentaron, además de poder dejar una nota.</p>
                <p>Si en el día ya se habían guardado asistencias, se sobreescribirán los registros guardados.</p>
                <span class="bg-info-subtle d-block mb-3 text-info-emphasis p-2">La fecha no puede ser anterior a la
                    actual.</span>
                <figure class="figure">
                    <img src="./img/manual/20.jpg" alt="Formulario de registro de asistencias"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Formulario de registro de asistencias.</figcaption>
                </figure>
            </div>
            <div class="col" id="eliminarAsistencias">
                <h3><?= $indice . "." . $sub_indice ?>. Eliminar Asistencias del día</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Para Eliminar las asistencias del día se debe hacer clic en el botón de "Eliminar asistencias del
                    día".</p>
                <span class="bg-danger-subtle d-block mb-3 text-danger-emphasis p-2">Esto eliminará todas las
                    asistencias del día.</span>
                <figure class="figure">
                    <img src="./img/manual/21.jpg" alt="Botón para eliminar asistencias"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón para eliminar asistencias.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/22.jpg" alt="Ventana de confirmación" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de confirmación.</figcaption>
                </figure>
            </div>
        </section>
        <?php
        $indice++;
        $sub_indice = 1; ?>
        <section class="row" id="gestionarEventos">
            <div class="col">
                <h2><?= $indice ?>. Gestionar Eventos/Competencias</h2>
            </div>
            <div class="col-12">
                <p>En la Pantalla de <Strong>Gestionar Eventos</Strong>, puedes administrar todos los aspectos
                    relacionados con los
                    eventos y competiciones en el sistema. Esta pantalla incluye varias funciones que permiten
                    organizar e inscribir a los atletas, gestionar resultados, categorías. tipos de competencias, subs,
                    así como cerrar o eliminar
                    eventos. A continuación se detallan las principales funciones disponibles</p>
                <figure class="figure">
                    <img src="./img/manual/23.jpg" alt="Pantalla de gestión de eventos y competencias"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Pantalla de gestión de eventos y competencias.</figcaption>
                </figure>
            </div>
            <div class="col" id="registrarEvento">
                <h3><?= $indice . "." . $sub_indice ?>. Registrar Evento</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p> Permite crear nuevos eventos o competencias, ingresando información relevante como nombre, fecha,
                    lugar, categoría, y otros detalles importantes.</p>
                <ul>
                    <li><strong>Nombre del evento: </strong>El nombre debe contener solo letras y/o números, y su
                        longitud debe ser de entre 3 y 100 caracteres.</li>
                    <li><strong>Ubicación: </strong>La ubicación del evento debe incluir letras y/o números, con una
                        longitud de entre 3 y 100 caracteres. Esto puede ser el nombre de un lugar, ciudad o gimnasio
                        donde se realizará el evento.</li>
                    <li><strong>Fecha de apertura: </strong>Ingresa la fecha de inicio del evento. Asegúrate de
                        seleccionar una fecha válida para el comienzo del evento.</li>
                    <li><strong>Fecha de clausura: </strong>La fecha de cierre del evento debe ser posterior a la fecha
                        de apertura. Esto asegura que la duración del evento sea lógica.</li>
                    <li><strong>Categoría: </strong>Selecciona la categoría del evento de una lista desplegable.</li>
                    <li><strong>Subs: </strong>Selecciona la sub del evento de una lista desplegable.</li>
                    <li><strong>Tipo de evento: </strong>Escoge el tipo de evento de una lista desplegable.</li>
                </ul>
                <figure class="figure">
                    <img src="./img/manual/24.jpg" alt="Formulario de registro de evento"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Formulario de registro de evento.</figcaption>
                </figure>
            </div>
            <div class="col" id="registrarCategoria">
                <h3><?= $indice . "." . $sub_indice . ".1" ?>. Registrar Categoría</h3>
            </div>
            <div class="col-12">
                <p> Permite crear una nueva categoría de peso corporal. Se debe ingresar el peso mínimo y peso máximo de
                    la categoría, ambos valores númericos con 2 decimales.</p>
                <figure class="figure">
                    <img src="./img/manual/25.jpg" alt="Botón para mostrar el registro de categorías"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón para mostrar el registro de categorías.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/26.jpg" alt="Ventana de registro de categorías"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de registro de categorías.</figcaption>
                </figure>
            </div>
            <div class="col" id="editarCategoria">
                <h3><?= $indice . "." . $sub_indice . ".2" ?>. Editar Categoría</h3>
            </div>
            <div class="col-12">
                <p>Para editar las categorías, se debe usar el botón "Editar" de la categoría, usando los mismos datos
                    del <a href="#registrarCategoria">registro de categorías</a>.</p>
                <figure class="figure">
                    <img src="./img/manual/27.jpg" alt="Botón para editar categoría"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón para editar categoría.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/28.jpg" alt="Ventana para editar categoría "
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana para editar categoría.</figcaption>
                </figure>
            </div>
            <div class="col" id="eliminarCategoria">
                <h3><?= $indice . "." . $sub_indice . ".3" ?>. Eliminar Categoría</h3>
            </div>
            <div class="col-12">
                <p>Para eliminar una categoría, se debe usar el botón "Eliminar" de la categoría correspondiente,
                    desplegando un mensaje de confirmación.</p>
                <figure class="figure">
                    <img src="./img/manual/29.jpg" alt="Mensaje de confirmación al eliminar categoría "
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Mensaje de confirmación al eliminar categoría.</figcaption>
                </figure>
            </div>
            <?php
            $sub_indice++; ?>
            <div class="col" id="registrarSubs">
                <h3><?= $indice . "." . $sub_indice . ".1" ?>. Registrar Subs</h3>
            </div>
            <div class="col-12">
                <p> Permite crear una nueva sub de edad. Se debe ingresar la edad mínima y edad máxima de
                    la sub, ambos valores númericos.</p>
                <figure class="figure">
                    <img src="./img/manual/30.jpg" alt="Botón para mostrar el registro de subs"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón para mostrar el registro de subs.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/31.jpg" alt="Ventana de registro de subs"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de registro de subs.</figcaption>
                </figure>
            </div>
            <div class="col" id="editarSubs">
                <h3><?= $indice . "." . $sub_indice . ".2" ?>. Editar Subs</h3>
            </div>
            <div class="col-12">
                <p>Para editar las subs, se debe usar el botón "Editar" de la sub, usando los mismos datos
                    del <a href="#registrarSubs">registro de subs</a>.</p>
                <figure class="figure">
                    <img src="./img/manual/32.jpg" alt="Botón para editar sub" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón para editar sub.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/33.jpg" alt="Ventana para editar sub " class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana para editar sub.</figcaption>
                </figure>
            </div>
            <div class="col" id="eliminarSubs">
                <h3><?= $indice . "." . $sub_indice . ".3" ?>. Eliminar Subs</h3>
            </div>
            <div class="col-12">
                <p>Para eliminar una sub, se debe usar el botón "Eliminar" de la sub correspondiente,
                    desplegando un mensaje de confirmación.</p>
                <figure class="figure">
                    <img src="./img/manual/34.jpg" alt="Mensaje de confirmación al eliminar sub "
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Mensaje de confirmación al eliminar sub.</figcaption>
                </figure>
            </div>
            <?php
            $sub_indice++; ?>
            <div class="col" id="registrarTipoCompetencia">
                <h3><?= $indice . "." . $sub_indice . ".1" ?>. Registrar Tipo de Competencia</h3>
            </div>
            <div class="col-12">
                <p> Permite crear un nuevo tipo de competencia. Se debe ingresar el nombre del tipo de evento, debe ser
                    letras y/o números (entre 3 y 50 caracteres).</p>
                <figure class="figure">
                    <img src="./img/manual/35.jpg" alt="Botón para mostrar el registro de tipos de competencia"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón para mostrar el registro de tipos de competencia.
                    </figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/36.jpg" alt="Ventana de registro de tipos de competencia"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de registro de tipos de competencia.</figcaption>
                </figure>
            </div>
            <div class="col" id="editarTipoCompetencia">
                <h3><?= $indice . "." . $sub_indice . ".2" ?>. Editar Tipo de Competencia</h3>
            </div>
            <div class="col-12">
                <p>Para editar los tipos de competencia, se debe usar el botón "Editar" el tipo de competencia, usando
                    los mismos datos
                    del <a href="#registrarTipoCompetencia">registro de tipos de competencia</a>.</p>
                <figure class="figure">
                    <img src="./img/manual/37.jpg" alt="Botón para editar tipo de competencia"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón para editar tipo de competencia.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/38.jpg" alt="Ventana para editar tipo de competencia "
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana para editar tipo de competencia.</figcaption>
                </figure>
            </div>
            <div class="col" id="eliminarTipoCompetencia">
                <h3><?= $indice . "." . $sub_indice . ".3" ?>. Eliminar Tipo de Competencia</h3>
            </div>
            <div class="col-12">
                <p>Para eliminar un tipo de competencia, se debe usar el botón "Eliminar" del tipo de competencia
                    correspondiente,
                    desplegando un mensaje de confirmación.</p>
                <figure class="figure">
                    <img src="./img/manual/39.jpg" alt="Mensaje de confirmación al eliminar el tipo de competencia "
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Mensaje de confirmación al eliminar el tipo de competencia.
                    </figcaption>
                </figure>
            </div>
            <?php
            $sub_indice++; ?>
            <div class="col" id="inscribirParticipante">
                <h3><?= $indice . "." . $sub_indice ?>. Inscribir Atleta a una competencia</h3>
            </div>
            <div class="col-12">
                <p>Desde esta pantalla puedes inscribir atletas a un evento o competencia. Para ello, seleccionas el
                    evento y eliges los atletas que participarán en esa competencia, registrando su inscripción en el
                    sistema.</p>
                <span class="bg-info-subtle d-block mb-3 text-info-emphasis p-2">Para que un atleta aparezca en la lista
                    de "Inscribir participante" debe cumplir los filtros asignados en la categoría y la sub.</span>
                <figure class="figure">
                    <img src="./img/manual/40.jpg" alt="Botón para inscribir un atleta en un evento"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón para inscribir un atleta en un evento.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/41.jpg" alt="Ventana para elegir los participantes del evento."
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana para elegir los participantes del evento.</figcaption>
                </figure>
            </div>
            <div class="col" id="registrarResultados">
                <h3><?= $indice . "." . $sub_indice . ".1" ?>. Registrar resultados de un Atleta a una competencia</h3>
            </div>
            <div class="col-12">
                <p>Después de que el evento o competencia finaliza, puedes registrar los resultados de los atletas. Esto
                    incluye detalles como las marcas obtenidas en envión, arranque y medallas.</p>
                <figure class="figure">
                    <img src="./img/manual/42.jpg" alt="Botón para ver el evento" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón para ver el evento.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/43.jpg"
                        alt="Ventana para elegir a que participante se le registra el resultado."
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana para elegir a que participante se le registra el
                        resultado.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/44.jpg"
                        alt="Ventana para introducir los resultados del atleta en la competencia."
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana para introducir los resultados del atleta en la
                        competencia.</figcaption>
                </figure>
            </div>
            <?php
            $sub_indice++; ?>
            <div class="col" id="modificarEvento">
                <h3><?= $indice . "." . $sub_indice ?>. Modificar Evento</h3>
            </div>
            <div class="col-12">
                <p>Permite editar los detalles de un evento ya creado, como cambiar la fecha, ubicación o tipo de
                    competencia si es necesario. Se utilizan los mismos datos usados en <a href="#registrarEvento">el
                        registro de eventos</a>.</p>
                <figure class="figure">
                    <img src="./img/manual/45.jpg" alt="Formulario de editar evento"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Formulario de editar evento.</figcaption>
                </figure>
            </div>
            <?php
            $sub_indice++; ?>
            <div class="col" id="cerrarEvento">
                <h3><?= $indice . "." . $sub_indice ?>. Cerrar Evento</h3>
            </div>
            <div class="col-12">
                <p>Permite cerrar la competencia para removerla del listado, se utiliza cuándo un evento ya no necesita
                    modificarse o registrar resultados.</p>
                <span class="bg-info-subtle d-block mb-3 text-info-emphasis p-2">El evento pasa a estar en el listado de
                    eventos anteriores.</span>
                <figure class="figure">
                    <img src="./img/manual/46.jpg" alt="Confirmación de cerrar evento"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Confirmación de cerrar evento.</figcaption>
                </figure>
            </div>
            <?php
            $sub_indice++; ?>
            <div class="col" id="consultarEventosAnteriores">
                <h3><?= $indice . "." . $sub_indice ?>. Consultar eventos anteriores</h3>
            </div>
            <div class="col-12">
                <p> Permite consultar eventos que ya pasó su fecha de clausura o fueron cerrados.</p>
                <figure class="figure">
                    <img src="./img/manual/47.jpg" alt="Botón de consulta de eventos anteriores"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón de consulta de eventos anteriores.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/48.jpg" alt="Ventana de consulta de eventos anteriores"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de consulta de eventos anteriores.</figcaption>
                </figure>
            </div>
        </section>
        <?php
        $indice++;
        $sub_indice = 1; ?>
        <section class="row" id="gestionarMensualidad">
            <div class="col">
                <h2><?= $indice ?>. Gestionar Mensualidad</h2>
            </div>
            <div class="col-12">
                <p>En la Pantalla de <strong>Gestión de Mensualidad</strong>, puedes gestionar y hacer seguimiento a los
                    pagos mensuales de los atletas, asegurando que sus cuotas estén actualizadas.</p>
                <figure class="figure">
                    <img src="./img/manual/49.jpg" alt="Pantalla de gestión de mensualidades"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Pantalla de gestión de mensualidades.</figcaption>
                </figure>
            </div>
            <div class="col" id="registrarMensualidad">
                <h3><?= $indice . "." . $sub_indice ?>. Registrar Mensualidad</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Puedes registrar un nuevo pago de mensualidad para un atleta. Los campos requeridos para completar el
                    registro son:</p>
                <ul>
                    <li><strong>Atleta:</strong>Selecciona al atleta de una lista desplegable con los atletas
                        registrados en el sistema.</li>
                    <li><strong>Detalles (opcional): </strong>Puedes agregar una breve descripción o nota sobre el pago,
                        como el concepto o motivo del pago.</li>
                    <li><strong>Fecha: </strong>Ingresa la fecha en que se realiza el pago. Este campo es importante
                        para llevar un control preciso de las fechas de los pagos.</li>
                    <li><strong>Monto: </strong>Introduce el monto del pago realizado por el atleta. Debe ser un número
                        con hasta 2 decimales</li>
                </ul>
                <figure class="figure">
                    <img src="./img/manual/50.jpg" alt="Formulario de registro de mensualidad"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Formulario de registro de mensualidad.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/51.jpg" alt="Ventana de confirmación del registro de mensualidad"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de confirmación del registro de mensualidad.</figcaption>
                </figure>
            </div>
            <div class="col" id="consultarPagosRegistrados">
                <h3><?= $indice . "." . $sub_indice ?>. Consultar Pagos Registrados</h3>
            </div>
            <div class="col-12">
                <p>Acá podrás ver el historial de pagos realizados por los atletas, con información detallada como el
                    monto, la fecha de pago y el atleta que realizó el pago.</p>
                <figure class="figure">
                    <img src="./img/manual/52.jpg" alt="Tabla de pagos registrados"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Tabla de pagos registrados.</figcaption>
                </figure>
            </div>
        </section>
        <?php
        $indice++;
        $sub_indice = 1; ?>
        <section class="row" id="gestionarWADA">
            <div class="col">
                <h2><?= $indice ?>. Gestionar WADA</h2>
            </div>
            <div class="col-12">
                <p>En la Pantalla de <strong>Gestión de WADA</strong>, puedes registrar y gestionar la información
                    relacionada con las pruebas y requisitos de la World Anti-Doping Agency (WADA) para los atletas.
                    Esta pantalla es importante para asegurar que los atletas cumplan con las normativas antidopaje y
                    para hacer un seguimiento adecuado de su estado en cuanto a las pruebas de WADA.</p>
                <figure class="figure">
                    <img src="./img/manual/53.jpg" alt="Pantalla de gestión de WADA"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Pantalla de gestión de WADA.</figcaption>
                </figure>
            </div>
            <div class="col" id="registrarWADA">
                <h3><?= $indice . "." . $sub_indice ?>. Registrar WADA</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>En esta sección puedes registrar si un atleta ha sido sometido a un control WADA. Sin embargo, ten en
                    cuenta que el atleta <strong>no puede ser menor de 15 años</strong> para registrar su estado de
                    WADA. Los siguientes campos deben completarse al registrar la información de WADA:</p>
                <ul>
                    <li><strong>Atleta:</strong>Selecciona al atleta de una lista desplegable con los atletas
                        registrados en el sistema.</li>
                    <li><strong>Fecha de inscripción: </strong>Registra la fecha en que el atleta fue inscrito en el
                        programa de control WADA.</li>
                    <li><strong>Fecha de última actualización: </strong>Debe ingresar la fecha en que se actualizó por
                        última vez la información relacionada con el control WADA. Esta fecha no puede ser anterior a la
                        fecha de inscripción.</li>
                    <li><strong>Fecha de vencimiento: </strong>La fecha de vencimiento del control WADA debe ser al
                        menos
                        un trimestre (tres meses) después de la fecha de la última actualización. Esto asegura que el
                        control WADA sea actualizado dentro de los plazos establecidos por la normativa.</li>
                    <li><strong>Cumple con los requisitos: </strong>Se debe registrar si el atleta cumple o no con los
                        requisitos de WADA.</li>
                </ul>
                <figure class="figure">
                    <img src="./img/manual/54.jpg" alt="Formulario de registro de WADA"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Formulario de registro de WADA.</figcaption>
                </figure>
            </div>
            <div class="col" id="modificarWADA">
                <h3><?= $indice . "." . $sub_indice ?>. Modificar WADA</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Si es necesario, puedes modificar los detalles de un control WADA registrado. Se usan los mismos
                    datos que en el <a href="#registrarWADA">registro de WADA</a>.</p>
                <figure class="figure">
                    <img src="./img/manual/55.jpg" alt="Botón de modificar WADA" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón de modificar WADA.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/56.jpg" alt="Ventana de modificación WADA"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de modificación WADA.</figcaption>
                </figure>
            </div>
            <div class="col" id="eliminarWADA">
                <h3><?= $indice . "." . $sub_indice ?>. Eliminar WADA</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Si un atleta ya no necesita estar en el programa WADA o si hubo un error en el registro, puedes
                    eliminar el registro correspondiente.</p>
                <figure class="figure">
                    <img src="./img/manual/57.jpg" alt="Ventana de confirmación para eliminar WADA"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de confirmación para eliminar WADA.</figcaption>
                </figure>
            </div>
            <div class="col" id="consultarWADAPorVencer">
                <h3><?= $indice . "." . $sub_indice ?>. Consultar WADA próximos a vencer</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>El módulo muestra un listado de atletas cuyo control WADA está próximo a vencer, es decir, aquellos
                    que tienen una fecha de vencimiento en los próximos 30 días o menos.</p>
                <figure class="figure">
                    <img src="./img/manual/58.jpg" alt="Tabla de WADA próximos a vencer"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Tabla de WADA próximos a vencer.</figcaption>
                </figure>
            </div>
        </section>
        <?php
        $indice++;
        $sub_indice = 1; ?>
        <section class="row" id="consultarBitacora">
            <div class="col">
                <h2><?= $indice ?>. Consultar Bitácora</h2>
            </div>
            <div class="col-12">
                <p>La <strong>Pantalla de Bitácora</strong> es una herramienta fundamental para realizar un seguimiento
                    de las acciones realizadas en el sistema, permitiendo auditar todas las actividades que los usuarios
                    realizan.
                </p>
                <ul>
                    <li><strong>Usuario:</strong> La cédula del usuario que realizó la acción en el sistema.</li>
                    <li><strong>Módulo:</strong> El módulo o sección en la que se realizó la acción (por ejemplo,
                        "Atletas", "Eventos", "Mensualidades", etc.).</li>
                    <li><strong>Fecha y Hora:</strong> La fecha y la hora exacta en que se realizó la acción.</li>
                    <li><strong>Registro afectado:</strong> El registro o entidad que fue afectado por la acción (por
                        ejemplo, un atleta, un evento, un pago, etc.).</li>
                    <li><strong>Acción realizada:</strong> El tipo de acción realizada (por ejemplo, creación,
                        modificación, eliminación).</li>
                </ul>
                <figure class="figure">
                    <img src="./img/manual/59.jpg" alt="Pantalla de bitácora" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Pantalla de bitácora.</figcaption>
                </figure>
            </div>
            <div class="col" id="consultarAccionBitacora">
                <h3><?= $indice . "." . $sub_indice ?>. Consultar Acción en la Bitácora</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Al seleccionar una acción específica en el registro de la bitácora, puedes ver más detalles sobre la
                    acción realizada</p>
                <figure class="figure">
                    <img src="./img/manual/60.jpg" alt="Consultar acción de la bitácora"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Consultar acción de la bitácora.</figcaption>
                </figure>
            </div>
        </section>
        <?php
        $indice++;
        $sub_indice = 1; ?>
        <section class="row" id="verNotificaciones">
            <div class="col">
                <h2><?= $indice ?>. Ver Notificaciones</h2>
            </div>
            <div class="col-12">
                <p>En esta sección, puedes ver todas las notificaciones generadas en el sistema.</p>
                <figure class="figure">
                    <img src="./img/manual/61.jpg" alt="Botón de notificaciones" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón de notificaciones.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/62.jpg" alt="Panel de notificaciones" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Panel de notificaciones.</figcaption>
                </figure>
            </div>
            <div class="col" id="marcarTodoComoLeido">
                <h3><?= $indice . "." . $sub_indice ?>. Marcar notificaciones como leídas</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Al hacer clic, todas las notificaciones que aún no han sido leídas se marcarán como ya leídas.</p>
                <figure class="figure">
                    <img src="./img/manual/63.jpg" alt="Botón de marcar todas las notificaciones como leídas"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón de marcar todas las notificaciones como leídas.
                    </figcaption>
                </figure>
            </div>
            <div class="col" id="verTodasLasNotificaciones">
                <h3><?= $indice . "." . $sub_indice ?>. Ver todas las Notificaciones</h3>
                <?php
                $sub_indice++; ?>
            </div>
            <div class="col-12">
                <p>Al hacer clic, se desplegará una ventana con todas las notificaciones.</p>
                <figure class="figure">
                    <img src="./img/manual/64.jpg" alt="Botón de ver todas las notificaciones"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón de ver todas las notificaciones.</figcaption>
                </figure>
                <figure class="figure">
                    <img src="./img/manual/65.jpg" alt="Ventana de todas las notificaciones"
                        class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Ventana de todas las notificaciones.</figcaption>
                </figure>
            </div>
        </section>
        <?php
        $indice++;
        $sub_indice = 1; ?>
        <section class="row" id="cerrarSesion">
            <div class="col">
                <h2><?= $indice ?>. Cerrar Sesión</h2>
            </div>
            <div class="col-12">
                <p>La opción de <strong>Cerrar Sesión</strong> te permite finalizar tu sesión de manera segura en el
                    sistema.</p>
                <figure class="figure">
                    <img src="./img/manual/66.jpg" alt="Botón de cerrar sesión" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Botón de cerrar sesión.</figcaption>
                </figure>
            </div>
        </section>
    </main>
    <?php require_once("comunes/footer.php"); ?>
    <script type="module" src="./js/manual.js"></script>
</body>

</html>