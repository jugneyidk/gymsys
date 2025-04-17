<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registro de Competencias - Sistema</title>
   <?php require_once("comunes/linkcss.php") ?>
</head>

<body class="bg-light">
   <script>
      var actualizar = <?php echo $permisos["actualizar"] ?>;
      var eliminar = <?php echo $permisos["eliminar"] ?>;
   </script>
   <?php require_once("comunes/menu.php"); ?>
   <main class="container-md my-3 my-md-5">
      <div class="row">
         <div class="col">
            <div class="d-flex justify-content-between align-items-center bg-dark text-white shadow rounded p-3">
               <h2 class="mb-0">Eventos</h2>
               <div>
                  <?php if ($permisos["crear"] == 1): ?>
                     <button class="btn btn-light" data-bs-toggle="modal"
                        data-bs-target="#modalRegistrarEvento">Registrar</button>
                  <?php endif; ?>
                  <button class="btn btn-outline-light" data-bs-toggle="modal"
                     data-bs-target="#modalEventoConsultaAnterior">Consultar
                     Anteriores</button>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col">
            <p class="lead mt-3"><strong>Eventos activos</strong></p>
            <div id="lista-eventos" class="row">
            </div>
         </div>
      </div>
      <div class="modal fade" id="modalRegistrarEvento" aria-hidden="true" aria-labelledby="modalRegistrarEvento"
         tabindex="-1">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h1 class="modal-title fs-5">Registrar Evento</h1>
                  <button class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar modal"></button>
               </div>
               <form id="fRegistrarEvento" method="post" action="#">
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-12 col-md-6">
                           <div class="form-floating">
                              <input type="text" class="form-control" id="in_nombre" name="nombre"
                                 placeholder="Nombre del Evento">
                              <label for="in_nombre" class="form-label">Nombre del Evento</label>
                              <div class="invalid-feedback" id="sin_nombre">Nombre del evento es obligatorio
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-md-6">
                           <div class="form-floating">
                              <input type="text" class="form-control" id="in_ubicacion" name="lugar_competencia"
                                 placeholder="Ubicación">
                              <label for="in_ubicacion" class="form-label">Ubicación</label>
                              <div class="invalid-feedback" id="sin_ubicacion">Ubicación es obligatoria</div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col">
                           <label for="in_date_start" class="form-label">Fecha de Apertura</label>
                           <input type="date" id="in_date_start" class="form-control" name="fecha_inicio">
                           <div class="invalid-feedback">Fecha de apertura es obligatoria</div>
                        </div>
                        <div class="col">
                           <label for="in_date_end" class="form-label">Fecha de Clausura</label>
                           <input type="date" id="in_date_end" class="form-control" name="fecha_fin">
                           <div class="invalid-feedback">Fecha de clausura es obligatoria</div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-4">
                           <label for="in_categoria" class="form-label">Categoria</label>
                           <select id="in_categoria" name="categoria" class="form-select form-control">
                              <option selected>Seleccione una</option>
                           </select>
                           <div class="invalid-feedback">Categoría es obligatoria</div>
                           <?php if ($permisos["leer"] && $permisos["crear"] && $permisos["actualizar"] && $permisos["leer"] && $permisos["eliminar"]): ?>
                              <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#modalRegistrarCategoria"
                                 type="button">Registrar
                                 Categoria</button>
                           <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                           <label for="in_subs" class="form-label">Subs</label>
                           <select id="in_subs" name="subs" class="form-select form-control">
                              <option selected>Seleccione una</option>
                           </select>
                           <div class="invalid-feedback">Subs es obligatorio</div>
                           <?php if ($permisos["leer"] && $permisos["crear"] && $permisos["actualizar"] && $permisos["leer"] && $permisos["eliminar"]): ?>
                              <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#modalRegistrarSubs"
                                 type="button">Registrar Subs</button>
                           <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                           <label for="in_tipo" class="form-label">Tipo</label>
                           <select id="in_tipo" name="tipo_competencia" class="form-select form-control">
                              <option selected>Seleccione una</option>
                           </select>
                           <div class="invalid-feedback">Tipo es obligatorio</div>
                           <?php if ($permisos["leer"] && $permisos["crear"] && $permisos["actualizar"] && $permisos["leer"] && $permisos["eliminar"]): ?>
                              <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#modalRegistrarTipo"
                                 type="button">Registrar Tipo</button>
                           <?php endif; ?>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <input type="submit" class="btn btn-primary" value="Registrar" type="button">
                     <input type="reset" class="btn btn-warning" value="Limpiar" type="button">
                     <button class="btn btn-danger" data-bs-dismiss="modal" type="button">Cancelar</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <div class="modal fade" id="modalRegistrarCategoria" tabindex="-1" aria-labelledby="modalRegistrarCategoriaLabel"
         aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="modalRegistrarCategoriaLabel">Registrar
                     Categoría</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
               </div>
               <div class="modal-body">
                  <!-- Formulario para registrar categorías -->
                  <form id="formRegistrarCategoria">
                     <div class="mb-3">
                        <div class="form-floating">
                           <input type="text" class="form-control" id="in_categoria_nombre" name="nombre"
                              placeholder="Nombre de la categoría">
                           <label for="in_categoria_nombre" class="form-label">Nombre de la categoría</label>
                           <div class="invalid-feedback" id="sin_categoria_nombre"></div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6 mb-3">
                           <div class="form-floating">
                              <input type="text" class="form-control" id="in_peso_minimo" name="pesoMinimo"
                                 placeholder="Peso mínimo">
                              <label for="in_peso_minimo" class="form-label">Peso mínimo</label>
                              <div class="invalid-feedback" id="sin_peso_minimo"></div>
                           </div>
                        </div>
                        <div class="col-md-6 mb-3">
                           <div class="form-floating">
                              <input type="text" class="form-control" id="in_peso_maximo" name="pesoMaximo"
                                 placeholder="Peso Máximo">
                              <label for="in_peso_maximo" class="form-label">Peso Máximo</label>
                              <div class="invalid-feedback" id="sin_peso_maximo"></div>
                           </div>
                        </div>
                     </div>
                     <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                        <button type="button" id="btnConsultarCategorias" class="btn btn-info">Consultar
                           Categorías</button>
                        <button type="button" id="btnRegresarCategorias" class="btn btn-secondary">Regresar</button>
                     </div>
                  </form>
                  <div class="table-responsive mt-3" id="contenedorTablaCategorias" style="display: none;">
                     <table class="table table-bordered" id="tablaCategorias">
                        <thead class="table-primary">
                           <tr>
                              <th>#</th>
                              <th>Nombre</th>
                              <th>Peso Mínimo</th>
                              <th>Peso Máximo</th>
                              <th>Acciones</th>
                           </tr>
                        </thead>
                        <tbody>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="modal fade" id="modalRegistrarSubs" tabindex="-1" aria-labelledby="modalRegistrarSubsLabel"
         aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="modalRegistrarSubsLabel">Registrar Subs</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar modal"></button>
               </div>
               <div class="modal-body">

                  <form id="formRegistrarSubs">
                     <div class="row">
                        <div class="col-md-6 mb-3">
                           <div class="form-floating">
                              <input type="text" class="form-control" id="in_subs_nombre" name="nombre"
                                 placeholder="Nombre del Sub">
                              <label for="in_subs_nombre" class="form-label">Nombre del Sub</label>
                              <div class="invalid-feedback" id="sin_subs_nombre"></div>
                           </div>
                        </div>
                        <div class="col-md-3 mb-3">
                           <div class="form-floating">
                              <input type="text" class="form-control" id="in_edad_minima" name="edadMinima"
                                 placeholder="Edad Mínima">
                              <label for="in_edad_minima" class="form-label">Edad Mínima</label>
                              <div class="invalid-feedback" id="sin_edad_minima"></div>
                           </div>
                        </div>
                        <div class="col-md-3 mb-3">
                           <div class="form-floating">
                              <input type="text" class="form-control" id="in_edad_maxima" name="edadMaxima"
                                 placeholder="Edad Máxima">
                              <label for="in_edad_maxima" class="form-label">Edad Máxima</label>
                              <div class="invalid-feedback" id="sin_edad_maxima"></div>
                           </div>
                        </div>
                     </div>
                     <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                        <button type="button" id="btnConsultarSubs" class="btn btn-info">Consultar
                           Subs</button>
                        <button type="button" id="btnRegresarSubs" class="btn btn-secondary">Regresar</button>
                     </div>
                  </form>

                  <div id="contenedorTablaSubs" class="mt-4" style="display: none;">
                     <h5 class="text-info">Subs Registrados</h5>
                     <table class="table table-bordered" id="tablaSubs">
                        <thead class="table-light">
                           <tr>
                              <th>#</th>
                              <th>Nombre</th>
                              <th>Edad Mínima</th>
                              <th>Edad Máxima</th>
                              <th>Acciones</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>


      <div class="modal fade" id="modalRegistrarTipo" tabindex="-1" aria-labelledby="modalRegistrarTipoLabel"
         aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="modalRegistrarTipoLabel">Registrar Tipo</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar modal"></button>
               </div>
               <div class="modal-body">

                  <form id="formRegistrarTipo">
                     <div class="mb-3">
                        <div class="form-floating">
                           <input type="text" class="form-control" id="in_tipo_nombre" name="nombre"
                              placeholder="Nombre del Tipo">
                           <label for="in_tipo_nombre" class="form-label">Nombre del Tipo</label>
                           <div class="invalid-feedback" id="sin_tipo_nombre"></div>
                        </div>
                     </div>
                     <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                        <button type="button" id="btnConsultarTipos" class="btn btn-info">Consultar
                           Tipos</button>
                        <button type="button" id="btnRegresarTipo" class="btn btn-secondary">Regresar</button>
                     </div>
                  </form>

                  <div id="contenedorTablaTipos" class="mt-4" style="display: none;">
                     <h5 class="text-info">Tipos Registrados</h5>
                     <table class="table table-bordered" id="tablaTipos">
                        <thead class="table-light">
                           <tr>
                              <th>#</th>
                              <th>Nombre</th>
                              <th>Acciones</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="modal" id="modalEditarTipo" tabindex="-1" aria-labelledby="modalEditarTipoLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="modalEditarTipoLabel">Editar Tipo</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar modal"></button>
               </div>
               <form id="formEditarTipo">
                  <div class="modal-body">
                     <input type="hidden" id="id_tipo_editar" name="id_tipo">
                     <div class="mb-3">
                        <label for="nombre_tipo_editar" class="form-label">Nombre del Tipo</label>
                        <input type="text" class="form-control" id="nombre_tipo_editar" name="nombre" required>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                     <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                  </div>
               </form>
            </div>
         </div>
      </div>


      <div class="modal fade" id="modalVerEventoActivo" tabindex="-1" aria-labelledby="modalVerEventoActivoLabel"
         aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="modalVerEventoActivoLabel">Detalles del Evento</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
               </div>
               <div class="modal-body">
                  <h6><strong>Nombre:</strong> <span id="detallesNombreEvento"></span></h6>
                  <h6><strong>Fecha:</strong> <span id="detallesFechaInicio"></span> - <span
                        id="detallesFechaFin"></span></h6>
                  <h6><strong>Ubicación:</strong> <span id="detallesUbicacion"></span></h6>
                  <h6><strong>Estado:</strong> <span id="detallesEstado"></span></h6>
                  <hr>
                  <h5>Atletas Inscritos</h5>
                  <table class="table table-bordered" id="tablaAtletasInscritos">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Nombre</th>
                           <th>Cédula</th>
                           <th>Acciones</th>
                        </tr>
                     </thead>
                     <tbody>

                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>



      <div class="modal fade" id="modalInscribirEvento" tabindex="-1" aria-labelledby="modalInscribirEventoLabel"
         aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="modalInscribirEventoLabel">Inscribir
                     Participante</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar modal"></button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class="col-md-6">
                        <strong>Nombre del Evento:</strong>
                        <span id="nombreEventoInscripcion"></span><br>
                        <strong>Fecha de Inicio:</strong>
                        <span id="fechaInicioEventoInscripcion"></span><br>
                     </div>
                     <div class="col-md-6">
                        <strong>Ubicación:</strong>
                        <span id="ubicacionEventoInscripcion"></span><br>
                        <strong>Fecha de Clausura:</strong>
                        <span id="fechaFinEventoInscripcion"></span><br>
                     </div>
                  </div>

                  <div class="row my-3">
                     <div class="table-responsive">
                        <table id="tablaParticipantesInscripcion" class="table table-bordered">
                           <thead class="table-primary">
                              <tr>
                                 <th>#</th>
                                 <th>Nombre</th>
                                 <th>Cédula</th>
                                 <th>Peso</th>
                                 <th>Edad</th>
                                 <th>Seleccionar</th>
                              </tr>
                           </thead>
                           <tbody>

                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <form id="formInscribirAtletas">
                  <div class="modal-footer">
                     <input type="submit" class="btn btn-primary" value="Inscribir">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
               </form>
            </div>
         </div>
      </div>


      <div class="modal fade" id="modalEventoConsultaAnterior" aria-hidden="true"
         aria-labelledby="modalEventoConsultaAnterior" tabindex="-1">
         <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title">Consulta eventos anteriores</h3>
                  <button class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar modal"></button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class="col my-3 mx-2">

                        <div id="eventosAnteriores" class="mt-3">
                           <h3 class="lead text-info">Eventos Anteriores</h3>
                           <div class="table-responsive">
                              <table id="tablaEventosAnteriores" class="table table-bordered">
                                 <thead>
                                    <tr>
                                       <th>#</th>
                                       <th>Nombre</th>
                                       <th>Fecha de Inicio</th>
                                       <th>Fecha Final</th>
                                       <th>Ubicación</th>
                                       <th>Estado</th>
                                    </tr>
                                 </thead>
                                 <tbody>

                                 </tbody>
                              </table>
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>

      <div class="modal fade" id="modalModificarResultados" tabindex="-1"
         aria-labelledby="modalModificarResultadosLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="modalModificarResultadosLabel">Modificar
                     Resultados del Atleta</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar modal"></button>
               </div>
               <form id="formModificarResultados">
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-6">
                           <strong>Nombre del Atleta:</strong>
                           <span id="nombreAtletaModificarResultados"></span><br>
                           <strong>Cédula:</strong>
                           <span id="cedulaAtletaModificarResultados"></span><br>
                        </div>
                        <div class="col-md-6">
                           <strong>Competencia:</strong>
                           <span id="nombreCompetenciaModificarResultados"></span><br>
                           <strong>Fecha:</strong>
                           <span id="fechaCompetenciaModificarResultados"></span><br>
                        </div>
                     </div>
                     <input type="hidden" class="form-control" id="id_competencia_modificar_resultado" name="id_competencia">
                     <input type="hidden" class="form-control" id="id_atleta_modificar" name="id_atleta">
                     <div class="row mt-3">
                        <div class="col-md-6">
                           <label for="arranque_modificar" class="form-label">Arranque</label>
                           <input type="number" class="form-control" id="arranque_modificar" name="arranque" required>
                        </div>
                        <div class="col-md-6">
                           <label for="envion_modificar" class="form-label">Envión</label>
                           <input type="number" class="form-control" id="envion_modificar" name="envion" required>
                        </div>
                     </div>
                     <div class="row mt-3">
                        <div class="col-md-4">
                           <label for="medalla_arranque_modificar" class="form-label">Medalla
                              Arranque</label>
                           <select id="medalla_arranque_modificar" name="medalla_arranque" class="form-select" required>
                              <option value="oro">Oro</option>
                              <option value="plata">Plata</option>
                              <option value="bronce">Bronce</option>
                              <option value="ninguna">Ninguna</option>
                           </select>
                        </div>
                        <div class="col-md-4">
                           <label for="medalla_envion_modificar" class="form-label">Medalla Envión</label>
                           <select id="medalla_envion_modificar" name="medalla_envion" class="form-select" required>
                              <option value="oro">Oro</option>
                              <option value="plata">Plata</option>
                              <option value="bronce">Bronce</option>
                              <option value="ninguna">Ninguna</option>
                           </select>
                        </div>
                        <div class="col-md-4">
                           <label for="medalla_total_modificar" class="form-label">Medalla Total</label>
                           <select id="medalla_total_modificar" name="medalla_total" class="form-select" required>
                              <option value="oro">Oro</option>
                              <option value="plata">Plata</option>
                              <option value="bronce">Bronce</option>
                              <option value="ninguna">Ninguna</option>
                           </select>
                        </div>
                     </div>
                     <div class="row mt-3">
                        <div class="col-md-12">
                           <label for="total_modificar" class="form-label">Total (Arranque +
                              Envión)</label>
                           <input type="number" class="form-control" id="total_modificar" name="total" readonly>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <input type="submit" class="btn btn-primary" value="Modificar Resultados">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
               </form>
            </div>
         </div>
      </div>

      <div class="modal fade" id="modalRegistrarResultados" tabindex="-1"
         aria-labelledby="modalRegistrarResultadosLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title fs-4 lead text-info" id="modalRegistrarResultadosLabel">Registrar
                     Resultados del Atleta</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar modal"></button>
               </div>
               <form id="formRegistrarResultados">
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-6">
                           <label for=""><strong>Nombre del Atleta:</strong></label>
                           <span id="nombreAtletaResultados"></span><br>
                           <label for=""><strong>Cédula:</strong></label>
                           <span id="cedulaAtletaResultados"></span><br>
                        </div>
                        <div class="col-md-6">
                           <label for=""><strong>Competencia:</strong></label>
                           <span id="nombreCompetenciaResultados"></span><br>
                           <label for=""><strong>Fecha:</strong></label>
                           <span id="fechaCompetenciaResultados"></span><br>
                        </div>
                     </div>
                     <div class="row mt-3">
                        <div class="col-md-6">
                           <label for="arranque" class="form-label">Arranque</label>
                           <input type="number" class="form-control" id="arranque" name="arranque" required>
                        </div>
                        <div class="col-md-6">
                           <label for="envion" class="form-label">Envión</label>
                           <input type="number" class="form-control" id="envion" name="envion" required>
                        </div>
                     </div>
                     <div class="row mt-3">
                        <div class="col-md-4">
                           <label for="medalla_arranque" class="form-label">Medalla Arranque</label>
                           <select id="medalla_arranque" name="medalla_arranque" class="form-select" required>
                              <option value="oro">Oro</option>
                              <option value="plata">Plata</option>
                              <option value="bronce">Bronce</option>
                              <option value="ninguna">Ninguna</option>
                           </select>
                        </div>
                        <div class="col-md-4">
                           <label for="medalla_envion" class="form-label">Medalla Envión</label>
                           <select id="medalla_envion" name="medalla_envion" class="form-select" required>
                              <option value="oro">Oro</option>
                              <option value="plata">Plata</option>
                              <option value="bronce">Bronce</option>
                              <option value="ninguna">Ninguna</option>
                           </select>
                        </div>
                        <div class="col-md-4">
                           <label for="medalla_total" class="form-label">Medalla Total</label>
                           <select id="medalla_total" name="medalla_total" class="form-select" required>
                              <option value="oro">Oro</option>
                              <option value="plata">Plata</option>
                              <option value="bronce">Bronce</option>
                              <option value="ninguna">Ninguna</option>
                           </select>
                        </div>
                     </div>
                     <div class="row mt-3">
                        <div class="col-md-12">
                           <label for="total" class="form-label">Total (Arranque + Envión)</label>
                           <input type="number" class="form-control" id="total" name="total" readonly>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <input type="submit" class="btn btn-primary" value="Registrar Resultados">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
               </form>
            </div>
         </div>
      </div>

      <div class="modal fade" id="modalConsultarEventoAnterior" tabindex="-1"
         aria-labelledby="modalConsultarEventoAnteriorLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="modalConsultarEventoAnteriorLabel">Detalles del Evento Anterior
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar modal"></button>
               </div>
               <div class="modal-body">

                  <p><strong>Nombre del Evento:</strong> <span id="detallesNombreEventoAnterior"></span></p>
                  <p><strong>Fecha de Inicio:</strong> <span id="detallesFechaInicioAnterior"></span></p>
                  <p><strong>Fecha de Fin:</strong> <span id="detallesFechaFinAnterior"></span></p>
                  <p><strong>Ubicación:</strong> <span id="detallesUbicacionAnterior"></span></p>
                  <p><strong>Estado:</strong> <span id="detallesEstadoAnterior"></span></p>
                  <div class="table-responsive">
                     <table class="table table-bordered" id="tablaTipos">
                        <thead class="table-light">
                           <tr>
                              <th>#</th>
                              <th>Nombre</th>
                              <th>Arranque</th>
                              <th>Envión</th>
                              <th>Total</th>
                              <th>Medalla Arranque</th>
                              <th>Medalla Envión</th>
                              <th>Medalla Total</th>
                           </tr>
                        </thead>
                        <tbody id="resultadosEventoAnterior">

                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>

      <div class="modal fade" id="modalModificarCompetencia" tabindex="-1"
         aria-labelledby="modalModificarCompetenciaLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="modalModificarCompetenciaLabel">Modificar
                     Competencia</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar modal"></button>
               </div>
               <form id="formModificarCompetencia">
                  <div class="modal-body">
                     <input type="hidden" id="id_competencia_modificar" name="id_competencia">
                     <div class="mb-3">
                        <label for="nombre_modificar" class="form-label">Nombre del Evento</label>
                        <input type="text" class="form-control" id="nombre_modificar" name="nombre">
                        <div class="invalid-feedback">El nombre es obligatorio</div>
                     </div>
                     <div class="mb-3">
                        <label for="ubicacion_modificar" class="form-label">Ubicación</label>
                        <input type="text" class="form-control" id="ubicacion_modificar" name="lugar_competencia">
                        <div class="invalid-feedback">La ubicación es obligatoria</div>
                     </div>
                     <div class="row">
                        <div class="col-md-6 mb-3">
                           <label for="fecha_inicio_modificar" class="form-label">Fecha de Apertura</label>
                           <input type="date" class="form-control" id="fecha_inicio_modificar" name="fecha_inicio">
                           <div class="invalid-feedback">La fecha de inicio es obligatoria</div>
                        </div>
                        <div class="col-md-6 mb-3">
                           <label for="fecha_fin_modificar" class="form-label">Fecha de Clausura</label>
                           <input type="date" class="form-control" id="fecha_fin_modificar" name="fecha_fin">
                           <div class="invalid-feedback">La fecha de fin es obligatoria</div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-4 mb-3">
                           <label for="categoria_modificar" class="form-label">Categoría</label>
                           <select id="categoria_modificar" name="categoria" class="form-select">
                              <option value="" selected>Seleccione una</option>

                           </select>
                           <div class="invalid-feedback">La categoría es obligatoria</div>
                        </div>
                        <div class="col-md-4 mb-3">
                           <label for="subs_modificar" class="form-label">Subs</label>
                           <select id="subs_modificar" name="subs" class="form-select">
                              <option value="" selected>Seleccione una</option>

                           </select>
                           <div class="invalid-feedback">El campo Subs es obligatorio</div>
                        </div>
                        <div class="col-md-4 mb-3">
                           <label for="tipo_modificar" class="form-label">Tipo</label>
                           <select id="tipo_modificar" name="tipo_competencia" class="form-select">
                              <option value="" selected>Seleccione una</option>

                           </select>
                           <div class="invalid-feedback">El tipo de competencia es obligatorio</div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <input type="submit" class="btn btn-primary" value="Modificar">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
               </form>
            </div>
         </div>
      </div>



      <div class="modal" id="modalConsultaAnteriorEsp" aria-hidden="true" aria-labelledby="modalConsultaAnteriorEsp"
         tabindex="-1">
         <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h1 class="modal-title fs-4 lead">Consulta Anteriores Especifica Evento</h1>
                  <button class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar modal"></button>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <div class="col my-3 mx-2">
                        <table id="tablaConsultaAnteriorEsp" class="table table-bordered">
                           <thead>
                              <tr>
                                 <th scope="col">#</th>
                                 <th scope="col">Nombre</th>
                                 <th scope="col">Cédula</th>
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
   </main>
   <?php require_once("comunes/footer.php"); ?>
   <script src="js/jquery.min.js"></script>
   <script src="datatables/datatables.min.js"></script>
   <script type="module" src="js/eventos.js"></script>

</body>

</html>