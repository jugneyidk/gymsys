<form id="formPago" method="POST" class="h-100">
   <div class="d-flex flex-column justify-content-center h-100">
      <div class="mb-3">
         <label for="atleta" class="form-label">Atleta:</label>
         <select class="form-select" id="atleta" name="id_atleta">
         </select>
         <div id="satleta" class="invalid-feedback"></div>
      </div>
      <div class="mb-3">
         <label for="monto" class="form-label">Monto:</label>
         <input type="number" class="form-control" id="monto" name="monto">
         <div id="smonto" class="invalid-feedback"></div>
      </div>

      <div class="mb-3">
         <label for="detalles" class="form-label">Detalles</label>
         <input type="text" class="form-control" id="detalles" name="detalles" maxlength="20"
            minlength="4">
         <div id="sdetalles" class="invalid-feedback"></div>
      </div>
      <div class="mb-3">
         <label for="fecha" class="form-label">Fecha:</label>
         <input type="date" class="form-control" id="fecha" name="fecha">
         <div id="sfecha" class="invalid-feedback"></div>
      </div>
      <button type="submit" class="btn btn-primary w-100" id="registrarPago"
         name="registrarPago">Registrar Pago</button>
   </div>
</form>