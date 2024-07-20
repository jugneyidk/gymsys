<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Competencias - Sistema</title>
    <?php require_once("comunes/linkcss.php") ?>
</head>
<body class="d-flex flex-column vh-100">
    <?php require_once("comunes/menu.php"); ?>
    <div class="container mb-5">
        <div class="row">
            <div class="col">
                <p class="mt-2 lead fs-1 text-info"><strong>Eventos</strong></p>
                <div class="row">
                    <div class="col">
                        <nav class="navbar navbar-expand-lg border rounded bg-info mt-2 shadow">
                            <div class="container-fluid">
                                <span class="navbar-brand lead text-white" disabled><strong>Opciones</strong></span>
                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                        <li class="nav-item">
                                            <button class="nav-link btn btn-outline-light mx-2 rounded-1" data-bs-toggle="modal" data-bs-target="#modalRegistrarEvento" type = "button">Registrar</button>
                                        </li>
                                        <li class="nav-item">
                                            <a href="" class="nav-link btn btn-outline-light mx-2 rounded-1" data-bs-toggle="modal" data-bs-target="#modalEventoConsultaAnterior">Consultar Anteriores</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="" class="nav-link btn btn-outline-light mx-2 rounded-1">Eliminar</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container1 mb-8">
        <div class="row">
            <p class="lead mt-2"><strong>Eventos activos</strong></p>
            <!-- Aquí se mostrarán los eventos activos -->
        </div>
        </div>
        
    </div>

    <!-- Modales -->
    <!-- Modal Registrar Evento -->
    <div class="modal" id="modalRegistrarEvento" aria-hidden="true" aria-labelledby="modalRegistrarEvento" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Registrar Evento</h1>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="fRegistrarEvento" method="post" action="#">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <label for="in_nombre" class="form-label">Nombre del Evento</label>
                                <input type="text" class="form-control form-control-sm" id="in_nombre" name="nombre">
                                <div class="invalid-feedback">Nombre del evento es obligatorio</div>
                                <label for="in_ubicacion" class="form-label">Ubicación</label>
                                <input type="text" class="form-control form-control-sm" id="in_ubicacion" name="lugar_competencia">
                                <div class="invalid-feedback">Ubicación es obligatoria</div>
                                <div class="row">
                                    <div class="col">
                                        <label for="in_date_start" class="form-label">Fecha de Apertura</label>
                                        <input type="date" id="in_date_start" class="form-control form-control-sm" name="fecha_inicio">
                                        <div class="invalid-feedback">Fecha de apertura es obligatoria</div>
                                    </div>
                                    <div class="col">
                                        <label for="in_date_end" class="form-label">Fecha de Clausura</label>
                                        <input type="date" id="in_date_end" class="form-control form-control-sm" name="fecha_fin">
                                        <div class="invalid-feedback">Fecha de clausura es obligatoria</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="in_categoria" class="form-label">Categoria</label>
                                    <select id="in_categoria" name="categoria" class="form-select form-control form-select-sm">
                                        <option selected>Seleccione una</option>
                                    </select>
                                    <div class="invalid-feedback">Categoría es obligatoria</div>
                                    <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#modalRegistrarCategoria" type = "button">Registrar Categoria</button>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="in_subs" class="form-label">Subs</label>
                                    <select id="in_subs" name="subs" class="form-select form-control form-select-sm">
                                        <option selected>Seleccione una</option>
                                    </select>
                                    <div class="invalid-feedback">Subs es obligatorio</div>
                                    <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#modalRegistrarSubs" type = "button">Registrar Subs</button>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="in_tipo" class="form-label">Tipo</label>
                                    <select id="in_tipo" name="tipo_competencia" class="form-select form-control form-select-sm">
                                        <option selected>Seleccione una</option>
                                    </select>
                                    <div class="invalid-feedback">Tipo es obligatorio</div>
                                    <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#modalRegistrarTipo" type = "button">Registrar Tipo</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Registrar">
                        <input type="reset" class="btn btn-warning" value="Limpiar">
                        <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modales de Registro para Categorias, Subs y Tipos -->
    <!-- Modal Registrar Categoria -->
    <div class="modal" id="modalRegistrarCategoria" aria-hidden="true" aria-labelledby="modalRegistrarCategoria" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Registrar Categoria</h1>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="registrarCategoria" method="post" action="#">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <label for="in_categoria_nombre" class="form-label">Descripcion</label>
                                <input type="text" class="form-control form-control-sm" id="in_categoria_nombre" name="nombre">
                                <label for="in_peso_minimo" class="form-label">Peso Minimo</label>
                                <input type="text" class="form-control form-control-sm" id="in_peso_minimo" name="pesoMinimo">
                                <label for="in_peso_maximo" class="form-label">Peso Maximo</label>
                                <input type="text" class="form-control form-control-sm" id="in_peso_maximo" name="pesoMaximo">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn px-2 btn-primary" value="Registrar">
                        <input type="reset" class="btn px-2 btn-danger" value="Limpiar" >
                        <button class="btn btn-warning px-2" data-bs-dismiss="modal" type="button">Regresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Subs -->
    <div class="modal" id="modalRegistrarSubs" aria-hidden="true" aria-labelledby="modalRegistrarSubs" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Registrar Subs</h1>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="registrarSubs" method="post" action="#">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <label for="in_subs_nombre" class="form-label">Descripcion</label>
                                <input type="text" class="form-control form-control-sm" id="in_subs_nombre" name="nombre">
                                <label for="in_edad_minima" class="form-label">Edad Minima</label>
                                <input type="text" class="form-control form-control-sm" id="in_edad_minima" name="edadMinima">
                                <label for="in_edad_maxima" class="form-label">Edad Maxima</label>
                                <input type="text" class="form-control form-control-sm" id="in_edad_maxima" name="edadMaxima">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <input type="submit" class="btn px-2 btn-primary" value="Registrar">
                        <input type="reset" class="btn px-2 btn-danger" value="Limpiar" >
                        <button class="btn btn-warning px-2" data-bs-dismiss="modal" type="button">Regresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Tipo -->
    <div class="modal" id="modalRegistrarTipo" aria-hidden="true" aria-labelledby="modalRegistrarTipo" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Registrar Tipo</h1>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="registrarTipo" method="post" action="#">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <label for="in_tipo_nombre" class="form-label">Descripcion</label>
                                <input type="text" class="form-control form-control-sm" id="in_tipo_nombre" name="nombre">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <input type="submit" class="btn px-2 btn-primary" value="Registrar">
                        <input type="reset" class="btn px-2 btn-danger" value="Limpiar" >
                        <button class="btn btn-warning px-2" data-bs-dismiss="modal" type="button">Regresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ver Evento Activo -->
    <div class="modal" id="modalVerEventoActivo" aria-hidden="true" aria-labelledby="modalVerEventoActivo" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Ver Evento Activo</h1>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <span class="lead fs-4 text-info"><strong>Informacion del Evento</strong></span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <label for="" class="form-label">Nombre del Evento:</label>
                            <span class=""><u>Evento Activo</u></span><br>
                            <label for="" class="form-label">Fecha de Inicio:</label>
                            <span class=""><u><?php echo date("d/m/y")?></u></span><br>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Ubicacion:</label>
                            <span><u>Gimnasio UPTAEB</u></span><br>
                            <label for="" class="form-label">Fecha de Clausura:</label>
                            <span class=""><u><?php echo date("d/m/y")?></u></span><br>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col table-responsive">
                            <table id="tablaParticipantes" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Cedula</th>
                                        <th scope="col">Edad</th>
                                        <th scope="col">Peso</th>
                                        <th scope="col">Altura</th>
                                        <th scope="col">Op</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Juan Jimenez</td>
                                        <td>25.123.231</td>
                                        <td>18</td>
                                        <td>55 kg</td>
                                        <td>1.35 cm</td>
                                        <td><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalParicipante">Ver</button></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Ricardo Sanchez</td>
                                        <td>30.315.412</td>
                                        <td>20</td>
                                        <td>60 kg</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn px-2 btn-primary" value="Registrar">
                    <input type="reset" class="btn px-2 btn-danger" value="Limpiar">
                    <button class="btn btn-warning px-2" data-bs-dismiss="modal">Regresar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Inscribir Evento -->
    <div class="modal" id="modalInscribirEvento" aria-hidden="true" aria-labelledby="modalInscribirEvento" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4 lead">Inscribir Participante</h1>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="#" class="form-control" id="registrarEvento">
                    <div class="modal-body mx-5">
                        <div class="row">
                            <div class="col-6">
                                <label for="in_cedula" class="form-label">Cedula</label>
                                <input type="text" class="form-control form-control-sm" name="cedula" id="in_cedula">
                            </div>
                        </div>
                        <div class="row my-3">
                            <table id="tablaParticipantesInscripcion" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Cedula</th>
                                        <th scope="col">Edad</th>
                                        <th scope="col">Peso</th>
                                        <th scope="col">Altura</th>
                                        <th scope="col">Seleccionar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Fila de ejemplo, será reemplazada dinámicamente por DataTables -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Inscribir">
                        <input type="reset" class="btn btn-warning" value="Limpiar">
                        <button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Consulta Anteriores Evento -->
    <div class="modal" id="modalEventoConsultaAnterior" aria-hidden="true" aria-labelledby="modalEventoConsultaAnterior" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4 lead">Consulta eventos anteriores</h1>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col my-3 mx-2">
                            <table id="tablaEventosAnteriores" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Fecha de inicio</th>
                                        <th scope="col">Fecha final</th>
                                        <th scope="col">Op</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Evento Sub 17</td>
                                        <td>10/12/23</td>
                                        <td>11/12/23</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Evento Sub 15</td>
                                        <td>10/12/23</td>
                                        <td>11/12/23</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Consulta Anteriores Especifica Evento -->
    <div class="modal" id="modalConsultaAnteriorEsp" aria-hidden="true" aria-labelledby="modalConsultaAnteriorEsp" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4 lead">Consulta Anteriores Especifica Evento</h1>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col my-3 mx-2">
                            <table id="tablaConsultaAnteriorEsp" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Cedula</th>
                                        <th scope="col">Edad</th>
                                        <th scope="col">Peso</th>
                                        <th scope="col">Altura</th>
                                        <th scope="col">Op</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Juan Jimenez</td>
                                        <td>25.123.231</td>
                                        <td>18</td>
                                        <td>55 kg</td>
                                        <td>1.35 cm</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Ricardo Sanchez</td>
                                        <td>30.315.412</td>
                                        <td>20</td>
                                        <td>60 kg</td>
                                        <td>1.25 cm</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div>
                
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
   
    <?php require_once("comunes/footer.php"); ?>
    <script type="text/javascript" src="js/eventos.js"></script>
</body>
</html>

