<form id="formLesion">
   <!-- Campos ocultos -->
   <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars($_SESSION['_csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
   <input type="hidden" name="id_atleta" id="les_id_atleta">
   <input type="hidden" name="id_lesion" id="les_id_lesion">

   <!-- Información básica de la lesión -->
   <div class="evaluacion-section mb-3">
      <h6 class="text-secondary mb-3"><i class="bi bi-clipboard-pulse me-2"></i>Información de la Lesión</h6>
   <div class="row mb-3">
      <div class="col-md-6">
         <label for="les_fecha_lesion" class="form-label">Fecha de la Lesión <span class="text-danger">*</span></label>
         <input type="date" name="fecha_lesion" id="les_fecha_lesion" class="form-control" required>
      </div>
      <div class="col-md-6">
         <label for="les_tipo_lesion" class="form-label">Tipo de Lesión <span class="text-danger">*</span></label>
         <select name="tipo_lesion" id="les_tipo_lesion" class="form-select" required>
            <option value="">Seleccione...</option>
            <option value="muscular">Muscular</option>
            <option value="articular">Articular</option>
            <option value="osea">Ósea</option>
            <option value="ligamentosa">Ligamentosa</option>
            <option value="tendinosa">Tendinosa</option>
            <option value="otra">Otra</option>
         </select>
      </div>
   </div>

   <div class="row mb-3">
      <div class="col-md-6">
         <label for="les_zona_afectada" class="form-label">Zona Afectada <span class="text-danger">*</span></label>
         <select name="zona_afectada" id="les_zona_afectada" class="form-select" required>
            <option value="">Seleccione una zona...</option>
            <optgroup label="Cabeza y Cuello">
               <option value="cabeza">Cabeza</option>
               <option value="cuello">Cuello</option>
               <option value="mandibula">Mandíbula</option>
            </optgroup>
            <optgroup label="Tronco">
               <option value="cervical">Cervical</option>
               <option value="dorsal">Dorsal</option>
               <option value="lumbar">Lumbar</option>
               <option value="sacro">Sacro</option>
               <option value="coxis">Cóccix</option>
               <option value="pecho">Pecho</option>
               <option value="abdomen">Abdomen</option>
            </optgroup>
            <optgroup label="Hombros">
               <option value="hombro_derecho">Hombro Derecho</option>
               <option value="hombro_izquierdo">Hombro Izquierdo</option>
            </optgroup>
            <optgroup label="Brazos">
               <option value="biceps_derecho">Bíceps Derecho</option>
               <option value="biceps_izquierdo">Bíceps Izquierdo</option>
               <option value="triceps_derecho">Tríceps Derecho</option>
               <option value="triceps_izquierdo">Tríceps Izquierdo</option>
               <option value="antebrazo_derecho">Antebrazo Derecho</option>
               <option value="antebrazo_izquierdo">Antebrazo Izquierdo</option>
            </optgroup>
            <optgroup label="Codos">
               <option value="codo_derecho">Codo Derecho</option>
               <option value="codo_izquierdo">Codo Izquierdo</option>
            </optgroup>
            <optgroup label="Muñecas y Manos">
               <option value="muneca_derecha">Muñeca Derecha</option>
               <option value="muneca_izquierda">Muñeca Izquierda</option>
               <option value="mano_derecha">Mano Derecha</option>
               <option value="mano_izquierda">Mano Izquierda</option>
               <option value="dedos_mano_derecha">Dedos Mano Derecha</option>
               <option value="dedos_mano_izquierda">Dedos Mano Izquierda</option>
            </optgroup>
            <optgroup label="Caderas">
               <option value="cadera_derecha">Cadera Derecha</option>
               <option value="cadera_izquierda">Cadera Izquierda</option>
               <option value="ingle_derecha">Ingle Derecha</option>
               <option value="ingle_izquierda">Ingle Izquierda</option>
            </optgroup>
            <optgroup label="Muslos">
               <option value="cuadriceps_derecho">Cuádriceps Derecho</option>
               <option value="cuadriceps_izquierdo">Cuádriceps Izquierdo</option>
               <option value="isquiotibiales_derecho">Isquiotibiales Derecho</option>
               <option value="isquiotibiales_izquierdo">Isquiotibiales Izquierdo</option>
               <option value="aductor_derecho">Aductor Derecho</option>
               <option value="aductor_izquierdo">Aductor Izquierdo</option>
            </optgroup>
            <optgroup label="Rodillas">
               <option value="rodilla_derecha">Rodilla Derecha</option>
               <option value="rodilla_izquierda">Rodilla Izquierda</option>
            </optgroup>
            <optgroup label="Piernas">
               <option value="gemelo_derecho">Gemelo Derecho</option>
               <option value="gemelo_izquierdo">Gemelo Izquierdo</option>
               <option value="tibia_derecha">Tibia Derecha</option>
               <option value="tibia_izquierda">Tibia Izquierda</option>
               <option value="perone_derecho">Peroné Derecho</option>
               <option value="perone_izquierdo">Peroné Izquierdo</option>
            </optgroup>
            <optgroup label="Tobillos y Pies">
               <option value="tobillo_derecho">Tobillo Derecho</option>
               <option value="tobillo_izquierdo">Tobillo Izquierdo</option>
               <option value="pie_derecho">Pie Derecho</option>
               <option value="pie_izquierdo">Pie Izquierdo</option>
               <option value="dedos_pie_derecho">Dedos Pie Derecho</option>
               <option value="dedos_pie_izquierdo">Dedos Pie Izquierdo</option>
               <option value="talon_derecho">Talón Derecho</option>
               <option value="talon_izquierdo">Talón Izquierdo</option>
            </optgroup>
         </select>
      </div>
      <div class="col-md-6">
         <label for="les_gravedad" class="form-label">Gravedad <span class="text-danger">*</span></label>
         <select name="gravedad" id="les_gravedad" class="form-select" required>
            <option value="">Seleccione...</option>
            <option value="leve">Leve</option>
            <option value="moderada">Moderada</option>
            <option value="severa">Severa</option>
         </select>
      </div>
   </div>
   </div>

   <!-- Detalles adicionales -->
   <div class="evaluacion-section mb-3">
      <h6 class="text-secondary mb-3"><i class="bi bi-info-circle me-2"></i>Detalles Adicionales</h6>
   <div class="row mb-3">
      <div class="col-md-6">
         <label for="les_mecanismo_lesion" class="form-label">Mecanismo de Lesión</label>
         <select name="mecanismo_lesion" id="les_mecanismo_lesion" class="form-select">
            <option value="entrenamiento">Entrenamiento</option>
            <option value="competencia">Competencia</option>
            <option value="accidente">Accidente</option>
            <option value="otro">Otro</option>
         </select>
      </div>
      <div class="col-md-6">
         <label for="les_tiempo_estimado_recuperacion" class="form-label">Tiempo Estimado de Recuperación (días)</label>
         <input type="number" name="tiempo_estimado_recuperacion" id="les_tiempo_estimado_recuperacion" class="form-control" placeholder="Ej: 30" min="0">
      </div>
      <div class="col-md-6">
         <label for="les_fecha_recuperacion" class="form-label">Fecha de Recuperación</label>
         <input type="date" name="fecha_recuperacion" id="les_fecha_recuperacion" class="form-control">
         <small class="form-text text-muted"><i class="bi bi-info-circle-fill me-1"></i>Dejar en blanco si la lesión está activa</small>
      </div>
   </div>
   </div>

   <!-- Tratamiento y observaciones -->
   <div class="evaluacion-section mb-3">
      <h6 class="text-secondary mb-3"><i class="bi bi-prescription2 me-2"></i>Tratamiento y Observaciones</h6>
   <div class="row mb-3">
      <div class="col-12">
         <label for="les_tratamiento_realizado" class="form-label">Tratamiento Realizado</label>
         <textarea name="tratamiento_realizado" id="les_tratamiento_realizado" class="form-control" rows="2" placeholder="Describa el tratamiento médico o terapéutico"></textarea>
      </div>
   </div>

   <div class="row mb-3">
      <div class="col-12">
         <label for="les_observaciones" class="form-label">Observaciones Generales</label>
         <textarea name="observaciones" id="les_observaciones" class="form-control" rows="3" placeholder="Notas adicionales sobre la lesión..."></textarea>
      </div>
   </div>
   </div>

   <!-- Botones -->
   <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      <button type="submit" class="btn btn-danger" id="btnGuardarLesion">Guardar</button>
   </div>
</form>
