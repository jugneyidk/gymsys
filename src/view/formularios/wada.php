<form method="post" id="f1" autocomplete="off">
    <input type="hidden" name="accion" id="accion" value="">
    <div class="container-lg">
        <div class="row mt-3">
            <div class="col-12 col-md-8 mb-3">
                <label for="atleta" class="form-label">Seleccionar Atleta:</label>
                <select class="form-select" id="atleta" name="atleta">
                    <option value="">Seleccione un atleta</option>
                </select>
                <div id="satleta" class="invalid-feedback"></div>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label for="status" class="form-label">Status WADA:</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Seleccione el status</option>
                    <option value="1">Cumple</option>
                    <option value="0">No Cumple</option>
                </select>
                <div id="sstatus" class="invalid-feedback"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md mb-3">
                <label for="inscrito" class="form-label">Inscrito:</label>
                <input type="date" class="form-control" id="inscrito" name="inscrito">
                <div id="sinscrito" class="invalid-feedback"></div>
            </div>
            <div class="col-12 col-md mb-3">
                <label for="ultima_actualizacion" class="form-label">Última Actualización:</label>
                <input type="date" class="form-control" id="ultima_actualizacion" name="ultima_actualizacion">
                <div id="sultima_actualizacion" class="invalid-feedback"></div>
            </div>
            <div class="col-12 col-md mb-3">
                <label for="vencimiento" class="form-label">Vencimiento:</label>
                <input type="date" class="form-control" id="vencimiento" name="vencimiento">
                <div id="svencimiento" class="invalid-feedback"></div>
            </div>
        </div>
        <div class="row m-2">
            <button type="submit" id="incluir" class="btn btn-primary btn-block">Registrar</button>
        </div>
    </div>
</form>