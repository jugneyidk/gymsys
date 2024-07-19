<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Competencias - Sistema</title>
    <?php require_once ("comunes/linkcss.php") ?>
</head>

<body class="d-flex flex-column vh-100">
    <?php require_once ("comunes/menu.php"); ?>
    <div class="container mb-5">
        <div class="row">
            <div class="col">
                <p class="mt-2 lead fs-1 text-info"><strong>Eventos</strong></p>
                <nav class="navbar navbar-expand-lg border rounded bg-info mt-2">
                    <div class="container-fluid">
                        <a href="#" class="navbar-brand lead text-white" disabled><strong>Opciones</strong></a>
                        <button class="navbar-toggler" type="button" data-bs-toogle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <button class="nav-link btn btn-outline-light mx-2" data-bs-toggle="modal" data-bs-target="#modalRegistrarEvento">Registrar</button>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link btn btn-outline-light mx-2"  data-bs-toggle="modal" data-bs-target="#modalEventoConsultaAnterior">Consultar Anteriores</a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link btn btn-outline-light mx-2">Eliminar</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="row">
            <p class="lead mt-2"><strong>Eventos activos</strong></p>
            <!--definir los campos a consultar (div.col)-->
        </div>
        <div class="row">
            <div class="col">
                <div class="card border-primary">
                    <div class="card-header lead m-0 p-0 px-3 pb-1 bg-primary text-white"><small><strong>Evento Activo</strong></small></div>
                            
                    <div class="row g-0">
                        <div class="col-md-5 bg-secondary border-end border-primary">
                            <img src="" alt="" class="img-fluid rounded-start" >
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">
                                <div class="card-title lead text-primary"><strong>Evento Activo</strong></div>
                                <p class="card-text lead m-0"><small>Fecha: 19/07 al 21/07</small></p>
                                <p class="card-text lead m-0"><small>Cupos Disponibles: 30</small></p>
                                <p class="card-text lead m-0"><small>Participantes: 20</small></p>
                                <button class="btn btn-sm btn-secondary">opciones</button>
                            </div>
                            <div class="card-footer my-1 border-primary">
                                <div class="btn-group visible">
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEventoActivoVer">Ver</button>
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalInscribirEvento">Inscribir</button>
                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEventoActivoModificar">Modificar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-info">
                    <div class="card-header lead m-0 p-0 px-3 pb-1 bg-info text-white"><small><strong>Evento En Espera</strong></small></div>
                    <div class="card-body">
                        <div class="card-title lead text-primary"><strong>Evento en Espera</strong></div>
                        <p class="card-text lead m-0"><small>Fecha: 19/08 al 21/08</small></p>
                        <p class="card-text lead m-0"><small>Cupos Disponibles: 30</small></p>
                        <p class="card-text lead m-0"><small>Participantes: 5</small></p>
                        <button class="btn btn-sm btn-secondary">opciones</button>
                    </div>
                    <div class="card-footer my-1 border-primary">
                        <div class="btn-group visible">
                            <button class="btn btn-outline-primary btn-sm">Ver</button>
                            <button class="btn btn-outline-primary btn-sm">Inscribir</button>
                            <button class="btn btn-outline-primary btn-sm">Modificar</button>
                        </div>
                        <div class="btn-group invisible">
                            <button class="btn btn-outline-primary btn-sm">Calificar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-info">
                    <div class="card-header lead m-0 p-0 px-3 pb-1 bg-info text-white"><small><strong>Evento inactiva</strong></small></div>
                    <div class="card-body">
                        <div class="card-title lead text-primary"><strong>Evento Inactiva</strong></div>
                        <p class="card-text lead m-0"><small>Fecha: 19/10 al 21/10</small></p>
                        <p class="card-text lead m-0"><small>Cupos Disponibles: 30</small></p>
                        <p class="card-text lead m-0"><small>Participantes: 0</small></p>
                        <button class="btn btn-sm btn-secondary">opciones</button>
                    </div>
                    <div class="card-footer my-1 border-primary">
                        <div class="btn-group visible">
                            <button class="btn btn-outline-primary btn-sm">Ver</button>
                            <button class="btn btn-outline-primary btn-sm">Preinscribir</button>
                            <button class="btn btn-outline-primary btn-sm">Modificar</button>
                        </div>
                        <div class="btn-group invisible">
                            <button class="btn btn-outline-primary btn-sm">Calificar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--



-->
    
    <!-- Modal Registrar Evento -->
    
    <div class="modal " id="modalInscribirEvento" aria-hidden="true" aria-labelledby="modalInscribirEvento" tabindex="-1">
        <div class="modal-dialog  modal-lg modal-dialog-centered modal-dialog-scrollable">
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
                            <div class="col-6">
                                
                            </div>
                        </div>
                        <div class="row my-3">
                            <table id="tablaParticipantes" class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Op</th>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Cedula</th>
                                        <th scope="col">Edad</th>
                                        <th scope="col">Peso</th>
                                        <th scope="col">Altura</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><div class="form-check"><input class="form-check-input" type="radio" name="in_participante" value="cedula"></div></td>
                                        <td>1</td>
                                        <td>Juan Jimenez</td>
                                        <td>25.123.231</td>
                                        <td>18</td>
                                        <td>55 kg</td>
                                        <td>1.35 cm</td>
                                    </tr>
                                    <tr>
                                        <td><div class="form-check"><input class="form-check-input" type="radio" name="in_participante" value="cedula"></div></td>
                                        <td>2</td>
                                        <td>Ricardo Sanchez</td>
                                        <td>30.315.412</td>
                                        <td>20</td>
                                        <td>60 kg</td>
                                        <td>1.25 cm</td>
                                    </tr>
                                </tbody>
                            </table>
                            </select>
                        </div>
                    </div>                    
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Registrar">
                    <input type="reset" class="btn btn-warning" value="Limpiar">
                    <button class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Fin -->

    <!-- Modal Consulta Anteriores Evento -->


    <div class="modal" id="modalEventoConsultaAnterior" aria-hidden="true" aria-labelledby="modalEventoConsultaAnterior" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4 lead">Consulta anteriores</h1>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col my-3 mx-2">
                            <table id="tablaParticipantes" class="table">
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
                                    <tr data-bs-target="#modalConsultaAnteriorEsp" data-bs-toggle="modal">
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


    <!-- Fin -->

    <!-- Modal Consulta Esp-->

    <div class="modal" id="modalConsultaAnteriorEsp" aria-hidden="true" aria-labelledby="modalConsultaAnteriorEsp" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4 lead">Consulta anterioresSS</h1>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col my-3 mx-2">
                            <table id="tablaParticipantes" class="table">
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
                                    <tr data-bs-target="#modalConsultaAnteriorEsp" data-bs-toggle="modal">
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

    <!-- Fin -->
    
    
    <!--  Modal Registrar Evento  -->

    <div class="modal" id="modalRegistrarEvento" aria-hidden="true" aria-labelledby="modalRegistrarEvento" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Registrar Evento</h1>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="" id="fRegistrarEvento" method="post" action=#>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <label for="in_nombre" class="form-label">Nombre del Evento</label>
                                <input type="text" class="form-control form-control-sm" id="in_nombre" name="in_name">
                                <div class="invalid-feedback">Nombre del evento es obligatorio</div>
                                <label for="in_ubicacion" class="form-label">Ubicación</label>
                                <input type="text" class="form-control form-control-sm" id="in_ubicacion" name="in_ubicacion">
                                <div class="invalid-feedback">Ubicación es obligatoria</div>
                                <div class="row">
                                    <div class="col">
                                        <label for="in_date_start" class="form-label">Fecha de Apertura</label>
                                        <input type="date" id="in_date_start" class="form-control form-control-sm" name="in_date_start">
                                        <div class="invalid-feedback">Fecha de apertura es obligatoria</div>
                                    </div>
                                    <div class="col">
                                        <label for="in_date_end" class="form-label">Fecha de Clausura</label>
                                        <input type="date" id="in_date_end" class="form-control form-control-sm" name="in_date_end">
                                        <div class="invalid-feedback">Fecha de clausura es obligatoria</div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="in_categoria" class="form-label">Categoria</label>
                                    <select id="in_categoria" name="in_categoria" class="form-select form-control form-select-sm">
                                        <option selected>Seleccione una</option>
                                        <!--Datos a consultar-->
                                        <option data-bs-target="#modalRegistrarCategoria" data-bs-toggle="modal" value="">Registrar categoria</option>
                                    </select>
                                    <div class="invalid-feedback">Categoría es obligatoria</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="in_subs" class="form-label">Subs</label>
                                    <select id="in_subs" name="in_subs" class="form-select form-control form-select-sm">
                                        <option selected>Seleccione una</option>
                                        <option data-bs-target="#modalRegistrarSubs" data-bs-toggle="modal" value="">Registrar Subs</option>
                                    </select>
                                    <div class="invalid-feedback">Subs es obligatorio</div>
                                </div>
                                    <div class="col-md-4 mb-3">
                                    <label for="in_categoria" class="form-label">Tipo</label>
                                    <select id="in_tipo" class="form-select form-control form-select-sm">
                                        <option selected>Seleccione una</option>
                                        <option data-bs-target="#modalRegistrarTipo" data-bs-toggle="modal" value="">Registrar tipo</option>
                                    </select>
                                    <div class="invalid-feedback">Tipo es obligatorio</div>
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

    <!--

    <div class="modal modal-xl " id="modalEventoActivoVer" aria-hidden="true" aria-labelledby="modalEventoActivoVer" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Evento Activo</h1>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-target="#ModalEventoParticipante" data-bs-toggle="modal">BOTON</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-xl" id="ModalEventoParticipante" aria-hidden="true" aria-labelledby="ModalEventoParticipante" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Segundo Modal</h1>
                    <button class="btn-close" data-bs-target="#modalEventoActivoVer" data-bs-toggle="modal"></button>
                </div>
                <div class="modal-body">
                    Segundo Modal
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-target="#modalEventoActivoVer" data-bs-toggle="modal">Regresar</button>
                </div>
            </div>
        </div>
    </div>
-->

<!-- Modal Registrar Categoria -->

    <div class="modal" id="modalRegistrarCategoria" aria-hidden="true" aria-labelledby="modalRegistrarCategoria" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Registrar Categoria</h1>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="registrarCategoria" method="post" action=#>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <label for="in_desc" class="form-label">Descripcion</label>
                            <input type="text" class="form-control form-control-sm" id="in_desc" name="in_desc">

                            <label for="in_peso_minimo" class="form-label">Peso minimo</label>
                            <input type="text" class="form-control form-control-sm" id="in_peso_minimo" name="in_peso_minimo">

                            <label for="in_peso_maximo" class="form-label">Peso maximo</label>
                            <input type="text" class="form-control form-control-sm" id="in_peso_minimo" name="in_peso_minimo">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn px-2 btn-primary" value="Registrar">
                    <input type="reset" class="btn px-2 btn-danger" value="Limpiar">
                    <button class="btn btn-warning px-2" data-bs-target="#modalRegistrarEvento" data-bs-toggle="modal">Regresar</button>
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
                <form id="registrarCategoria needs-validation" method="post" action=# novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <label for="in_desc" class="form-label">Descripcion</label>
                            <input type="text" class="form-control form-control-sm" id="in_desc" name="in_desc">
                            <div class="valid-feedback">
                                Todo bien
                            </div>
                            <div class="invalid-feedback">
                                Solo puede usar letras y numeros
                            </div>
                            <label for="in_edad_minima" class="form-label">Edad minima</label>
                            <div class="input-group flex-nowrap mb-3">    
                                <input type="text" class="form-control form-control-sm"  aria-label="in_edad_minima" aria-describedby="addon" id="in_peso_minimo" name="in_peso_minimo">
                                <span class="input-group-text" id="addon">Años</span>  
                            </div>
                            <div class="valid-feedback">
                                Todo bien
                            </div>
                            <div class="invalid-feedback">
                                Solo puede usar numeros
                            </div>
                            <label for="in_edad_maxima" class="form-label">Edad maxima</label>
                            <div class="input-group flex-nowrap mb-3">
                                <input type="text" class="form-control form-control-sm" aria-label="in_edad_minima" aria-describedby="addon" id="in_peso_minimo" name="in_peso_minimo">
                                <span class="input-group-text">Años</span>
                            </div>
                            <div class="valid-feedback">
                                Todo bien
                            </div>
                            <div class="invalid-feedback">
                                Solo puede usar numeros
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn px-2 btn-primary" value="Registrar">
                    <input type="reset" class="btn px-2 btn-danger" value="Limpiar">
                    <button class="btn btn-warning px-2" data-bs-target="#modalRegistrarEvento" data-bs-toggle="modal">Regresar</button>
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
                <form id="registrarCategoria" method="post" action=#>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <label for="in_desc" class="form-label">Descripcion</label>
                            <input type="text" class="form-control form-control-sm" id="in_desc" name="in_desc">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn px-2 btn-primary" value="Registrar">
                    <input type="reset" class="btn px-2 btn-danger" value="Limpiar">
                    <button class="btn btn-warning px-2" data-bs-target="#modalRegistrarEvento" data-bs-toggle="modal">Regresar</button>
                </div>
                </form>
            </div>
        </div>
    </div>


<!--



-->



    <!-- Modal Incluir-->
    <!--
    <div class="modal fade" id="modalIncluir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Evento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <form method="POST" id="IncluirEvento" >
                        <input autocomplete="off" type="text" class="form-control" name="accion" id="accion"
                        style="display: none;">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Tipo</label>
                                        <select
                                            class="form-select"
                                            name="inpTipo"
                                            id=""
                                        >
                                            <option selected>Seleccione Uno</option>
                                            <option value="1">Interno</option>
                                            <option value="2">Externo</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Nombre</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="inpName"
                                            id=""
                                            aria-describedby="helpId"
                                            placeholder=""
                                        />
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Categoria</label>
                                        <select
                                            class="form-select"
                                            name="inpCategoria"
                                            id=""
                                        >
                                            <option selected>Seleccione Uno</option>
                                            <option value="Liviano">Liviano</option>
                                            <option value="Medio">Medio</option>
                                            <option value="Pesado">Pesado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Subs</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="inpSubs"
                                            id=""
                                            aria-describedby="helpId"
                                            placeholder=""
                                        />
                                        
                                    </div>
                                </div>
                            </div>   
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Edad Minima</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            name="inpEdad"
                                            id=""
                                            aria-describedby="helpId"
                                            placeholder=""
                                        />
                                        
                                    </div>
                                    
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Direccion de la competencia</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="inpDireccion"
                                            id=""
                                            aria-describedby="helpId"
                                            placeholder=""
                                        />
                                        
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Fecha de Apertura</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            name="inpDateApertura"
                                            id=""
                                            aria-describedby="helpId"
                                            placeholder=""
                                        />
                                        
                                    </div>
                                    
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Fecha de clausura</label>
                                        <input
                                            type="Date"
                                            class="form-control"
                                            name="inpDateClose"
                                            id=""
                                            aria-describedby="helpId"
                                            placeholder=""
                                        />
                                        
                                    </div>
                                    
                                </div>
                            </div>
                                               
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="incluir" name="incluir" class="btn btn-primary">Crear Evento</button>
                <input type="reset" class="btn btn-secondary" >limpiar</button>
                
            </div>
            </form>    
        </div>
            
        </div>
        </div>
    -->

    <!--Modal de la competencia-->


    <div class="modal fade" tabindex="-1" aria-hidden="true" role="dialog" id="modalEventOne"
        style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Evento Nacional Sub15</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-3 d-flex justify-content-center">
                                <button class="btn" data-bs-toggle="modal" data-bs-target="#offcanvasExample"
                                    id="btnParticipante">
                                    <div class="card " style="width: 10rem;">
                                        <img src="img/atleta-foto.png" class="card-img-top img-thumbnail " alt="...">
                                        <div class="card-body">
                                            <p class="card-text">Jugney Vargas</p>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            <div class="col-2 d-flex justify-content-center">
                                <button class="btn ">
                                    <div class="card" style="width: 10rem;">
                                        <img src="" class="card-img-top img-thumbnail " alt="...">
                                        <div class="card-body">
                                            <p class="card-text">Enny Torres</p>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            <div class="col-3 d-flex justify-content-center">
                                <button class="btn">
                                    <div class="card" style="width: 10rem;">
                                        <img src="" class="card-img-top img-thumbnail " alt="...">
                                        <div class="card-body">
                                            <p class="card-text">Jesus Perez</p>
                                        </div>
                                    </div>
                                </button>
                            </div>
                            <div class="col-4">
                                <div class="card w-100">

                                    <div class="card-body">
                                        <h4 class="card-title">Datos de la competencia</h4>
                                        <p class="card-text">Jugney Vargas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!--Modal del atleta-->

    
    

    <div class="modal fade" id="ParticipanteJugney" style="display: none;" tabindex="-1"
        aria-labelledby="ParticipanteJugneylabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card p-4 col-lg-10 col-md-12 mb-3">
                        <h2 class="card-title text-center mb-4">Registro de Competencia</h2>
                        <form action="submit_competencia.php" method="POST">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="arranque1" class="form-label">1er Arranque:</label>
                                    <input type="number" class="form-control" id="arranque1" name="arranque1" required>
                                    <select class="form-control mt-1" id="resultado_arranque1"
                                        name="resultado_arranque1">
                                        <option>Bueno</option>
                                        <option>Malo</option>
                                    </select>
                                    <select class="form-control mt-1" id="medalla_arranque1" name="medalla_arranque1">
                                        <option value="">Sin Medalla</option>
                                        <option>Oro</option>
                                        <option>Plata</option>
                                        <option>Bronce</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="arranque2" class="form-label">2do Arranque:</label>
                                    <input type="number" class="form-control" id="arranque2" name="arranque2" required>
                                    <select class="form-control mt-1" id="resultado_arranque2"
                                        name="resultado_arranque2">
                                        <option>Bueno</option>
                                        <option>Malo</option>
                                    </select>
                                    <select class="form-control mt-1" id="medalla_arranque2" name="medalla_arranque2">
                                        <option value="">Sin Medalla</option>
                                        <option>Oro</option>
                                        <option>Plata</option>
                                        <option>Bronce</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="arranque3" class="form-label">3er Arranque:</label>
                                    <input type="number" class="form-control" id="arranque3" name="arranque3" required>
                                    <select class="form-control mt-1" id="resultado_arranque3"
                                        name="resultado_arranque3">
                                        <option>Bueno</option>
                                        <option>Malo</option>
                                    </select>
                                    <select class="form-control mt-1" id="medalla_arranque3" name="medalla_arranque3">
                                        <option value="">Sin Medalla</option>
                                        <option>Oro</option>
                                        <option>Plata</option>
                                        <option>Bronce</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="envion1" class="form-label">1er envion:</label>
                                    <input type="number" class="form-control" id="envion1" name="arranenvion1que1"
                                        required>
                                    <select class="form-control mt-1" id="resultado_envion1" name="resultado_envion1">
                                        <option>Bueno</option>
                                        <option>Malo</option>
                                    </select>
                                    <select class="form-control mt-1" id="medalla_envion1" name="medalla_envion1">
                                        <option value="">Sin Medalla</option>
                                        <option>Oro</option>
                                        <option>Plata</option>
                                        <option>Bronce</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="envion2" class="form-label">2do envion:</label>
                                    <input type="number" class="form-control" id="envion2" name="envion2" required>
                                    <select class="form-control mt-1" id="resultado_envion2" name="resultado_envion2">
                                        <option>Bueno</option>
                                        <option>Malo</option>
                                    </select>
                                    <select class="form-control mt-1" id="medalla_envion2" name="medalla_envion2">
                                        <option value="">Sin Medalla</option>
                                        <option>Oro</option>
                                        <option>Plata</option>
                                        <option>Bronce</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="envion3" class="form-label">3er envion:</label>
                                    <input type="number" class="form-control" id="envion3" name="envion3" required>
                                    <select class="form-control mt-1" id="resultado_envion3" name="resultado_envion3">
                                        <option>Bueno</option>
                                        <option>Malo</option>
                                    </select>
                                    <select class="form-control mt-1" id="medalla_envion3" name="medalla_envion3">
                                        <option value="">Sin Medalla</option>
                                        <option>Oro</option>
                                        <option>Plata</option>
                                        <option>Bronce</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="total_arranque" class="form-label">Total Arranque:</label>
                                    <input type="number" class="form-control" id="total_arranque" name="total_arranque"
                                        readonly>
                                    <select class="form-control mt-1" id="medalla_total_arranque"
                                        name="medalla_total_arranque">
                                        <option value="">Sin Medalla</option>
                                        <option>Oro</option>
                                        <option>Plata</option>
                                        <option>Bronce</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="total_envion" class="form-label">Total Envión:</label>
                                    <input type="number" class="form-control" id="total_envion" name="total_envion"
                                        readonly>
                                    <select class="form-control mt-1" id="medalla_total_envion"
                                        name="medalla_total_envion">
                                        <option value="">Sin Medalla</option>
                                        <option>Oro</option>
                                        <option>Plata</option>
                                        <option>Bronce</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="total_general" class="form-label">Total General:</label>
                                    <input type="number" class="form-control" id="total_general" name="total_general"
                                        readonly>
                                    <select class="form-control mt-1" id="medalla_total_general"
                                        name="medalla_total_general">
                                        <option value="">Sin Medalla</option>
                                        <option>Oro</option>
                                        <option>Plata</option>
                                        <option>Bronce</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Registrar Competencia</button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <?php require_once ("comunes/footer.php"); ?>
    <!--
    <script src="js/eventos.js"></script>
    <script>
        function calculateTotals() {

            let arranque1 = parseInt(document.getElementById('arranque1').value) || 0;
            let arranque2 = parseInt(document.getElementById('arranque2').value) || 0;
            let arranque3 = parseInt(document.getElementById('arranque3').value) || 0;
            let envion1 = parseInt(document.getElementById('envion1').value) || 0;
            let envion2 = parseInt(document.getElementById('envion2').value) || 0;
            let envion3 = parseInt(document.getElementById('envion3').value) || 0;

            document.getElementById('total_arranque').value = arranque1 + arranque2 + arranque3;
            document.getElementById('total_envion').value = envion1 + envion2 + envion3;
            document.getElementById('total_general').value = document.getElementById('total_arranque').value + document.getElementById('total_envion').value;
        }

        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('change', calculateTotals);
        });
    </script>-->
</body>

</html>