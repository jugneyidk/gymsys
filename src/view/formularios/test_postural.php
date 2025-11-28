<form id="formTestPostural">
   <!-- Campos ocultos -->
   <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars($_SESSION['_csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
   <input type="hidden" name="id_atleta" id="tp_id_atleta">
   <input type="hidden" name="id_test_postural" id="tp_id_test_postural">

   <div class="row">
      <!-- Columna Izquierda: Formulario -->
      <div class="col-lg-7">
         <!-- Fecha de evaluación -->
         <div class="mb-3">
            <label for="tp_fecha_evaluacion" class="form-label fw-bold"><i class="bi bi-calendar-event me-2"></i>Fecha de Evaluación <span class="text-danger">*</span></label>
            <input type="date" name="fecha_evaluacion" id="tp_fecha_evaluacion" class="form-control" required>
         </div>

         <!-- Sección: Columna Vertebral -->
         <div class="evaluacion-section">
            <h6 class="section-title"><i class="bi bi-arrow-down-up me-2"></i>Columna Vertebral</h6>
            <div class="row g-3">

               <div class="col-md-6">
                  <label for="tp_cifosis_dorsal" class="form-label">Cifosis Dorsal</label>
                  <select name="cifosis_dorsal" id="tp_cifosis_dorsal" class="form-select">
                     <option value="ninguna">Ninguna</option>
                     <option value="leve">Leve</option>
                     <option value="moderada">Moderada</option>
                     <option value="severa">Severa</option>
                  </select>
               </div>
               <div class="col-md-6">
                  <label for="tp_lordosis_lumbar" class="form-label">Lordosis Lumbar</label>
                  <select name="lordosis_lumbar" id="tp_lordosis_lumbar" class="form-select">
                     <option value="ninguna">Ninguna</option>
                     <option value="leve">Leve</option>
                     <option value="moderada">Moderada</option>
                     <option value="severa">Severa</option>
                  </select>
               </div>
               <div class="col-md-6">
                  <label for="tp_escoliosis" class="form-label">Escoliosis</label>
                  <select name="escoliosis" id="tp_escoliosis" class="form-select">
                     <option value="ninguna">Ninguna</option>
                     <option value="leve">Leve</option>
                     <option value="moderada">Moderada</option>
                     <option value="severa">Severa</option>
                  </select>
               </div>
            </div>
         </div>


         <!-- Sección: Pelvis -->
         <div class="evaluacion-section">
            <h6 class="section-title"><i class="bi bi-symmetry-vertical me-2"></i>Pelvis</h6>
            <div class="row g-3">
               <div class="col-12">
                  <label for="tp_inclinacion_pelvis" class="form-label">Inclinación de Pelvis</label>
                  <select name="inclinacion_pelvis" id="tp_inclinacion_pelvis" class="form-select">
                     <option value="ninguna">Ninguna</option>
                     <option value="leve">Leve</option>
                     <option value="moderada">Moderada</option>
                     <option value="severa">Severa</option>
                  </select>
               </div>
            </div>
         </div>


         <!-- Sección: Rodillas -->
         <div class="evaluacion-section">
            <h6 class="section-title"><i class="bi bi-circle me-2"></i>Rodillas</h6>
            <div class="row g-3">
               <div class="col-md-6">
                  <label for="tp_valgo_rodilla" class="form-label">Valgo de Rodilla</label>
                  <select name="valgo_rodilla" id="tp_valgo_rodilla" class="form-select">
                     <option value="ninguna">Ninguna</option>
                     <option value="leve">Leve</option>
                     <option value="moderada">Moderada</option>
                     <option value="severa">Severa</option>
                  </select>
               </div>
               <div class="col-md-6">
                  <label for="tp_varo_rodilla" class="form-label">Varo de Rodilla</label>
                  <select name="varo_rodilla" id="tp_varo_rodilla" class="form-select">
                     <option value="ninguna">Ninguna</option>
                     <option value="leve">Leve</option>
                     <option value="moderada">Moderada</option>
                     <option value="severa">Severa</option>
                  </select>
               </div>
            </div>
         </div>


         <!-- Sección: Hombros y Escápulas -->
         <div class="evaluacion-section">
            <h6 class="section-title"><i class="bi bi-arrow-left-right me-2"></i>Hombros y Escápulas</h6>
            <div class="row g-3">
               <div class="col-md-6">
                  <label for="tp_rotacion_hombros" class="form-label">Rotación de Hombros</label>
                  <select name="rotacion_hombros" id="tp_rotacion_hombros" class="form-select">
                     <option value="ninguna">Ninguna</option>
                     <option value="leve">Leve</option>
                     <option value="moderada">Moderada</option>
                     <option value="severa">Severa</option>
                  </select>
               </div>
               <div class="col-md-6">
                  <label for="tp_desnivel_escapulas" class="form-label">Desnivel de Escápulas</label>
                  <select name="desnivel_escapulas" id="tp_desnivel_escapulas" class="form-select">
                     <option value="ninguna">Ninguna</option>
                     <option value="leve">Leve</option>
                     <option value="moderada">Moderada</option>
                     <option value="severa">Severa</option>
                  </select>
               </div>
            </div>
         </div>


         <!-- Observaciones -->
         <div class="mb-3">
            <label for="tp_observaciones" class="form-label fw-bold"><i class="bi bi-chat-left-text me-2"></i>Observaciones</label>
            <textarea name="observaciones" id="tp_observaciones" class="form-control" rows="3" placeholder="Notas adicionales sobre la evaluación postural..."></textarea>
         </div>
      </div>

      <!-- Columna Derecha: Referencia Anatómica -->
      <div class="col-lg-5">
         <div class="anatomia-container">
            <h6 class="anatomia-title"><i class="bi bi-person-standing me-2"></i>Referencia Anatómica</h6>
            <div class="anatomia-views">
               <!-- Vista Frontal -->
               <div class="anatomia-view">
                  <div class="anatomia-view-label">Vista Frontal</div>
                  <div class="anatomia-placeholder">
                     <i class="bi bi-person-standing anatomia-icon-overlay"></i>
                  </div>
               </div>
               <!-- Vista Lateral -->
               <div class="anatomia-view">
                  <div class="anatomia-view-label">Vista Lateral</div>
                  <div class="anatomia-placeholder">
                     <i class="bi bi-person anatomia-icon-overlay"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Botones -->
   <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      <button type="submit" class="btn btn-primary" id="btnGuardarTestPostural">Guardar</button>
   </div>
</form>
