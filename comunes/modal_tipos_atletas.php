<div class="modal fade" id="modalRegistrarTipoAtleta" tabindex="-1" aria-labelledby="modalRegistrarTipoAtletaLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarTipoAtletaLabel">Registrar Tipo de Atleta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistrarTipoAtleta">
                    <div class="mb-3">
                        <label for="nombre_tipo_atleta" class="form-label">Nombre del Tipo de
                            Atleta:</label>
                        <input type="text" class="form-control" id="nombre_tipo_atleta" name="nombre_tipo_atleta">
                        <div id="snombre_tipo_atleta" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_cobro" class="form-label">Tipo de Cobro:</label>
                        <input type="number" class="form-control" id="tipo_cobro" name="tipo_cobro" step="0.01">
                        <div id="stipo_cobro" class="invalid-feedback"></div>
                    </div>
                    <button type="button" id="btnRegistrarTipoAtleta" class="btn btn-primary">Registrar</button>
                    <button type="button" id="btnConsultarTipos" class="btn btn-info">Consultar
                           Tipos</button>
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
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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