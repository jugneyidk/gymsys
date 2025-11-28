<form id="formTestFms">
   <!-- Campos ocultos -->
   <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars($_SESSION['_csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
   <input type="hidden" name="id_atleta" id="fms_id_atleta">
   <input type="hidden" name="id_test_fms" id="fms_id_test_fms">

   <!-- Fecha de evaluación -->
   <div class="row mb-3">
      <div class="col-md-6">
         <label for="fms_fecha_evaluacion" class="form-label">Fecha de Evaluación <span class="text-danger">*</span></label>
         <input type="date" name="fecha_evaluacion" id="fms_fecha_evaluacion" class="form-control" required>
      </div>
   </div>

   <!-- Pruebas FMS con selección visual en 2 columnas -->
   <div class="row">
      <div class="col-md-6">
         <div class="fms-test-item evaluacion-section">
            <label class="form-label fw-bold">
               <span><i class="bi bi-person-arms-up me-2"></i>Sentadilla Profunda</span>
               <i class="bi bi-info-circle text-primary" style="font-size: 1.4rem; margin-left: 0.75rem; cursor: help;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="<img src='assets/img/fms/sentadilla.jpg' style='width:250px' alt='Sentadilla Profunda'>"></i>
            </label>
      <input type="hidden" name="sentadilla_profunda" id="fms_sentadilla_profunda" value="" required>
      <div class="fms-score-buttons" data-target="fms_sentadilla_profunda">
         <button type="button" class="fms-score-btn" data-score="0">
            <span class="score-number">0</span>
            <small>Dolor</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="1">
            <span class="score-number">1</span>
            <small>No puede</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="2">
            <span class="score-number">2</span>
            <small>Compensación</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="3">
            <span class="score-number">3</span>
            <small>Correcto</small>
         </button>
         </div>
         </div>
      </div>
      
      <div class="col-md-6">
         <div class="fms-test-item evaluacion-section">
            <label class="form-label fw-bold">
               <span><i class="bi bi-shuffle me-2"></i>Paso de Valla</span>
               <i class="bi bi-info-circle text-primary" style="font-size: 1.4rem; margin-left: 0.75rem; cursor: help;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="<img src='assets/img/fms/paso_valla.jpg' style='width:250px' alt='Paso de Valla'>"></i>
            </label>
      <input type="hidden" name="paso_valla" id="fms_paso_valla" value="" required>
      <div class="fms-score-buttons" data-target="fms_paso_valla">
         <button type="button" class="fms-score-btn" data-score="0">
            <span class="score-number">0</span>
            <small>Dolor</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="1">
            <span class="score-number">1</span>
            <small>No puede</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="2">
            <span class="score-number">2</span>
            <small>Compensación</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="3">
            <span class="score-number">3</span>
            <small>Correcto</small>
         </button>
         </div>
         </div>
      </div>
      
      <div class="col-md-6">
         <div class="fms-test-item evaluacion-section">
            <label class="form-label fw-bold">
               <span><i class="bi bi-arrow-right me-2"></i>Estocada en Línea</span>
               <i class="bi bi-info-circle text-primary" style="font-size: 1.4rem; margin-left: 0.75rem; cursor: help;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="<img src='assets/img/fms/estocada.jpg' style='width:250px' alt='Estocada en Línea'>"></i>
            </label>
      <input type="hidden" name="estocada_en_linea" id="fms_estocada_en_linea" value="" required>
      <div class="fms-score-buttons" data-target="fms_estocada_en_linea">
         <button type="button" class="fms-score-btn" data-score="0">
            <span class="score-number">0</span>
            <small>Dolor</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="1">
            <span class="score-number">1</span>
            <small>No puede</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="2">
            <span class="score-number">2</span>
            <small>Compensación</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="3">
            <span class="score-number">3</span>
            <small>Correcto</small>
         </button>
         </div>
         </div>
      </div>
      
      <div class="col-md-6">
         <div class="fms-test-item evaluacion-section">
            <label class="form-label fw-bold">
               <span><i class="bi bi-person-raised-hand me-2"></i>Movilidad de Hombro</span>
               <i class="bi bi-info-circle text-primary" style="font-size: 1.4rem; margin-left: 0.75rem; cursor: help;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="<img src='assets/img/fms/movilidad_hombro.jpg' style='width:250px' alt='Movilidad de Hombro'>"></i>
            </label>
      <input type="hidden" name="movilidad_hombro" id="fms_movilidad_hombro" value="" required>
      <div class="fms-score-buttons" data-target="fms_movilidad_hombro">
         <button type="button" class="fms-score-btn" data-score="0">
            <span class="score-number">0</span>
            <small>Dolor</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="1">
            <span class="score-number">1</span>
            <small>No puede</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="2">
            <span class="score-number">2</span>
            <small>Compensación</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="3">
            <span class="score-number">3</span>
            <small>Correcto</small>
         </button>
         </div>
         </div>
      </div>
      
      <div class="col-md-6">
         <div class="fms-test-item evaluacion-section">
            <label class="form-label fw-bold">
               <span><i class="bi bi-arrow-up me-2"></i>Elevación de Pierna Recta</span>
               <i class="bi bi-info-circle text-primary" style="font-size: 1.4rem; margin-left: 0.75rem; cursor: help;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="<img src='assets/img/fms/elevacion_pierna.jpg' style='width:250px' alt='Elevación de Pierna Recta'>"></i>
            </label>
      <input type="hidden" name="elevacion_pierna_recta" id="fms_elevacion_pierna_recta" value="" required>
      <div class="fms-score-buttons" data-target="fms_elevacion_pierna_recta">
         <button type="button" class="fms-score-btn" data-score="0">
            <span class="score-number">0</span>
            <small>Dolor</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="1">
            <span class="score-number">1</span>
            <small>No puede</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="2">
            <span class="score-number">2</span>
            <small>Compensación</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="3">
            <span class="score-number">3</span>
            <small>Correcto</small>
         </button>
         </div>
         </div>
      </div>
      
      <div class="col-md-6">
         <div class="fms-test-item evaluacion-section">
            <label class="form-label fw-bold">
               <span><i class="bi bi-back me-2"></i>Estabilidad de Tronco</span>
               <i class="bi bi-info-circle text-primary" style="font-size: 1.4rem; margin-left: 0.75rem; cursor: help;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="<img src='assets/img/fms/estabilidad_tronco.jpg' style='width:250px' alt='Estabilidad de Tronco'>"></i>
            </label>
      <input type="hidden" name="estabilidad_tronco" id="fms_estabilidad_tronco" value="" required>
      <div class="fms-score-buttons" data-target="fms_estabilidad_tronco">
         <button type="button" class="fms-score-btn" data-score="0">
            <span class="score-number">0</span>
            <small>Dolor</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="1">
            <span class="score-number">1</span>
            <small>No puede</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="2">
            <span class="score-number">2</span>
            <small>Compensación</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="3">
            <span class="score-number">3</span>
            <small>Correcto</small>
         </button>
         </div>
         </div>
      </div>
      
      <div class="col-md-6">
         <div class="fms-test-item evaluacion-section">
            <label class="form-label fw-bold">
               <span><i class="bi bi-arrow-repeat me-2"></i>Estabilidad Rotacional</span>
               <i class="bi bi-info-circle text-primary" style="font-size: 1.4rem; margin-left: 0.75rem; cursor: help;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="<img src='assets/img/fms/estabilidad_rotacional.jpg' style='width:250px' alt='Estabilidad Rotacional'>"></i>
            </label>
      <input type="hidden" name="estabilidad_rotacional" id="fms_estabilidad_rotacional" value="" required>
      <div class="fms-score-buttons" data-target="fms_estabilidad_rotacional">
         <button type="button" class="fms-score-btn" data-score="0">
            <span class="score-number">0</span>
            <small>Dolor</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="1">
            <span class="score-number">1</span>
            <small>No puede</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="2">
            <span class="score-number">2</span>
            <small>Compensación</small>
         </button>
         <button type="button" class="fms-score-btn" data-score="3">
            <span class="score-number">3</span>
            <small>Correcto</small>
         </button>
         </div>
         </div>
      </div>
   </div>

   <!-- Puntuación Total -->
   <div class="fms-score-total">
      <div class="score-total-display">
         <div class="score-total-label">Puntuación Total</div>
         <div class="score-total-value" id="fms_puntuacion_total_display">0</div>
         <div class="score-total-max">/ 21</div>
      </div>
      <input type="hidden" name="puntuacion_total" id="fms_puntuacion_total" value="0" readonly>
   </div>

   <!-- Observaciones -->
   <div class="row mb-3">
      <div class="col-12">
         <label for="fms_observaciones" class="form-label">Observaciones</label>
         <textarea name="observaciones" id="fms_observaciones" class="form-control" rows="3"></textarea>
      </div>
   </div>

   <!-- Botones -->
   <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      <button type="submit" class="btn btn-success" id="btnGuardarTestFms">Guardar</button>
   </div>
</form>
