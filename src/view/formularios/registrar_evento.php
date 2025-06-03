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
            <?php if ($permisosModulo["leer"] && $permisosModulo["crear"] && $permisosModulo["actualizar"] && $permisosModulo["leer"] && $permisosModulo["eliminar"]): ?>
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
            <?php if ($permisosModulo["leer"] && $permisosModulo["crear"] && $permisosModulo["actualizar"] && $permisosModulo["leer"] && $permisosModulo["eliminar"]): ?>
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
            <?php if ($permisosModulo["leer"] && $permisosModulo["crear"] && $permisosModulo["actualizar"] && $permisosModulo["leer"] && $permisosModulo["eliminar"]): ?>
               <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#modalRegistrarTipo"
                  type="button">Registrar Tipo</button>
            <?php endif; ?>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <input type="submit" class="btn btn-primary" value="Registrar" type="button">
      <input type="reset" class="btn btn-warning" value="Limpiar" type="button">
      <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
   </div>
</form>