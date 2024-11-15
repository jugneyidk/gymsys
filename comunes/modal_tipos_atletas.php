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
                </form>
            </div>
        </div>
    </div>
</div>