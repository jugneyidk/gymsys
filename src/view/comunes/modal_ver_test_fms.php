<!-- Modal: Ver Test FMS (Solo lectura) -->
<div class="modal fade" id="modalVerTestFms" tabindex="-1" aria-labelledby="modalVerTestFmsLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalVerTestFmsLabel">
               <i class="bi bi-activity me-2"></i>Visualizar Test FMS
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
         </div>
         <div class="modal-body">
            <!-- Fecha y Puntuación Total -->
            <div class="row mb-4">
               <div class="col-md-6">
                  <div class="card border-0 bg-light">
                     <div class="card-body">
                        <h6 class="text-secondary mb-2"><i class="bi bi-calendar3 me-2"></i>Fecha de Evaluación</h6>
                        <p class="h5 mb-0" id="ver_fms_fecha_evaluacion"></p>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="card border-0" id="ver_fms_card_total">
                     <div class="card-body text-center">
                        <h6 class="text-secondary mb-2"><i class="bi bi-trophy me-2"></i>Puntuación Total</h6>
                        <h2 class="mb-0"><span id="ver_fms_puntuacion_total"></span> <small class="text-muted">/ 21</small></h2>
                        <p class="mb-0 mt-2" id="ver_fms_nivel_riesgo"></p>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Pruebas FMS -->
            <div class="row">
               <div class="col-md-6">
                  <div class="evaluacion-section mb-3">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold mb-0"><i class="bi bi-person-arms-up me-2"></i>Sentadilla Profunda</label>
                        <span class="badge-fms-score" id="ver_fms_sentadilla"></span>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="evaluacion-section mb-3">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold mb-0"><i class="bi bi-shuffle me-2"></i>Paso de Valla</label>
                        <span class="badge-fms-score" id="ver_fms_paso_valla"></span>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="evaluacion-section mb-3">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold mb-0"><i class="bi bi-arrow-right me-2"></i>Estocada en Línea</label>
                        <span class="badge-fms-score" id="ver_fms_estocada"></span>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="evaluacion-section mb-3">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold mb-0"><i class="bi bi-person-raised-hand me-2"></i>Movilidad de Hombro</label>
                        <span class="badge-fms-score" id="ver_fms_movilidad_hombro"></span>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="evaluacion-section mb-3">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold mb-0"><i class="bi bi-arrow-up me-2"></i>Elevación de Pierna Recta</label>
                        <span class="badge-fms-score" id="ver_fms_elevacion_pierna"></span>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="evaluacion-section mb-3">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold mb-0"><i class="bi bi-back me-2"></i>Estabilidad de Tronco</label>
                        <span class="badge-fms-score" id="ver_fms_estabilidad_tronco"></span>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="evaluacion-section mb-3">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold mb-0"><i class="bi bi-arrow-repeat me-2"></i>Estabilidad Rotacional</label>
                        <span class="badge-fms-score" id="ver_fms_estabilidad_rotacional"></span>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Observaciones -->
            <div class="evaluacion-section mt-4">
               <h6 class="text-secondary mb-3"><i class="bi bi-chat-left-text me-2"></i>Observaciones</h6>
               <div class="alert alert-info mb-0" id="ver_fms_observaciones_container" style="display: none;">
                  <p id="ver_fms_observaciones" class="mb-0"></p>
               </div>
               <p id="ver_fms_sin_observaciones" class="text-muted fst-italic">Sin observaciones registradas</p>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
               <i class="bi bi-x-circle me-2"></i>Cerrar
            </button>
         </div>
      </div>
   </div>
</div>
