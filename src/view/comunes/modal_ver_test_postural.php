<!-- Modal: Ver Test Postural (Solo lectura) -->
<div class="modal fade" id="modalVerTestPostural" tabindex="-1" aria-labelledby="modalVerTestPosturalLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalVerTestPosturalLabel">
               <i class="bi bi-person-standing me-2"></i>Visualizar Test Postural
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
         </div>
         <div class="modal-body">
            <!-- Fecha de evaluación -->
            <div class="row mb-4">
               <div class="col-md-6">
                  <div class="card border-0 bg-light">
                     <div class="card-body">
                        <h6 class="text-secondary mb-2"><i class="bi bi-calendar3 me-2"></i>Fecha de Evaluación</h6>
                        <p class="h5 mb-0" id="ver_post_fecha_evaluacion"></p>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="card border-0 bg-light">
                     <div class="card-body">
                        <h6 class="text-secondary mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Estado General</h6>
                        <p class="h5 mb-0" id="ver_post_estado_general"></p>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Columna Vertebral -->
            <div class="evaluacion-section mb-4">
               <h6 class="text-secondary mb-3"><i class="bi bi-activity me-2"></i>Columna Vertebral</h6>
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label class="form-label fw-bold">Cifosis Dorsal</label>
                        <p id="ver_post_cifosis" class="badge-resultado"></p>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label class="form-label fw-bold">Lordosis Lumbar</label>
                        <p id="ver_post_lordosis" class="badge-resultado"></p>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label class="form-label fw-bold">Escoliosis</label>
                        <p id="ver_post_escoliosis" class="badge-resultado"></p>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Pelvis -->
            <div class="evaluacion-section mb-4">
               <h6 class="text-secondary mb-3"><i class="bi bi-circle me-2"></i>Pelvis</h6>
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label class="form-label fw-bold">Inclinación de Pelvis</label>
                        <p id="ver_post_pelvis" class="badge-resultado"></p>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Rodillas -->
            <div class="evaluacion-section mb-4">
               <h6 class="text-secondary mb-3"><i class="bi bi-circle-half me-2"></i>Rodillas</h6>
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label class="form-label fw-bold">Valgo de Rodilla</label>
                        <p id="ver_post_valgo" class="badge-resultado"></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label class="form-label fw-bold">Varo de Rodilla</label>
                        <p id="ver_post_varo" class="badge-resultado"></p>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Hombros y Escápulas -->
            <div class="evaluacion-section mb-4">
               <h6 class="text-secondary mb-3"><i class="bi bi-person-arms-up me-2"></i>Hombros y Escápulas</h6>
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label class="form-label fw-bold">Rotación de Hombros</label>
                        <p id="ver_post_hombros" class="badge-resultado"></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label class="form-label fw-bold">Desnivel de Escápulas</label>
                        <p id="ver_post_escapulas" class="badge-resultado"></p>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Observaciones -->
            <div class="evaluacion-section">
               <h6 class="text-secondary mb-3"><i class="bi bi-chat-left-text me-2"></i>Observaciones</h6>
               <div class="alert alert-info mb-0" id="ver_post_observaciones_container" style="display: none;">
                  <p id="ver_post_observaciones" class="mb-0"></p>
               </div>
               <p id="ver_post_sin_observaciones" class="text-muted fst-italic">Sin observaciones registradas</p>
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
