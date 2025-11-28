<!-- Modal: Tarjeta del Atleta -->
<div class="modal fade" id="modalTarjetaAtleta" tabindex="-1" aria-labelledby="modalTarjetaAtletaLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header bg-dark text-white py-3">
            <h5 class="modal-title" id="modalTarjetaAtletaLabel"><i class="bi bi-person-badge me-2"></i>Perfil del Atleta</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
         </div>
         <div class="modal-body p-4 bg-light">
            <!-- Datos básicos del atleta (Compactos, siempre visibles) -->
            <div class="card border-0 mb-3 shadow-sm">
               <div class="card-body p-3" id="tarjeta-datos-basicos">
                  <!-- Contenido dinámico desde JS -->
               </div>
            </div>

            <!-- Acordeón para evaluaciones y lesiones -->
            <div class="accordion accordion-flush" id="accordionTarjetaAtleta">
               
               <!-- Resumen de Riesgo (Siempre visible primero) -->
               <div class="card border-0 mb-3 shadow-sm bg-white">
                  <div class="card-body p-3" id="tarjeta-resumen-riesgo">
                     <!-- Contenido dinámico desde JS -->
                  </div>
               </div>

               <!-- Última Evaluación Postural (Colapsable) -->
               <div class="accordion-item mb-3 border-0 shadow-sm">
                  <h2 class="accordion-header" id="headingPostural">
                     <button class="accordion-button collapsed py-3 bg-white text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePostural" aria-expanded="false" aria-controls="collapsePostural">
                        <i class="bi bi-person-standing me-2 text-secondary"></i>Última Evaluación Postural
                     </button>
                  </h2>
                  <div id="collapsePostural" class="accordion-collapse collapse" aria-labelledby="headingPostural" data-bs-parent="#accordionTarjetaAtleta">
                     <div class="accordion-body p-3 bg-white" id="tarjeta-test-postural">
                        <!-- Contenido dinámico desde JS -->
                     </div>
                  </div>
               </div>

               <!-- Último Test FMS (Colapsable) -->
               <div class="accordion-item mb-3 border-0 shadow-sm">
                  <h2 class="accordion-header" id="headingFMS">
                     <button class="accordion-button collapsed py-3 bg-white text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFMS" aria-expanded="false" aria-controls="collapseFMS">
                        <i class="bi bi-activity me-2 text-secondary"></i>Último Test FMS
                     </button>
                  </h2>
                  <div id="collapseFMS" class="accordion-collapse collapse" aria-labelledby="headingFMS" data-bs-parent="#accordionTarjetaAtleta">
                     <div class="accordion-body p-3 bg-white" id="tarjeta-test-fms">
                        <!-- Contenido dinámico desde JS -->
                     </div>
                  </div>
               </div>

               <!-- Lesiones Recientes (Colapsable) -->
               <div class="accordion-item mb-3 border-0 shadow-sm">
                  <h2 class="accordion-header" id="headingLesiones">
                     <button class="accordion-button collapsed py-3 bg-white text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLesiones" aria-expanded="false" aria-controls="collapseLesiones">
                        <i class="bi bi-bandaid me-2 text-secondary"></i>Lesiones Recientes
                     </button>
                  </h2>
                  <div id="collapseLesiones" class="accordion-collapse collapse" aria-labelledby="headingLesiones" data-bs-parent="#accordionTarjetaAtleta">
                     <div class="accordion-body p-3 bg-white" id="tarjeta-lesiones-recientes">
                        <!-- Contenido dinámico desde JS -->
                     </div>
                  </div>
               </div>

               <!-- Análisis de Riesgo IA (Colapsable) -->
               <div class="accordion-item mb-3 border-0 shadow-sm">
                  <h2 class="accordion-header" id="headingIA">
                     <button class="accordion-button collapsed py-2" type="button"
                             data-bs-toggle="collapse" data-bs-target="#collapseIA"
                             aria-expanded="false" aria-controls="collapseIA">
                        <i class="bi bi-robot me-2"></i>
                        Análisis de Riesgo (IA)
                     </button>
                  </h2>
                  <div id="collapseIA" class="accordion-collapse collapse"
                       aria-labelledby="headingIA" data-bs-parent="#accordionTarjetaAtleta">
                     <div class="accordion-body py-3">

                        <!-- Score principal -->
                        <div class="text-center mb-3">
                           <div class="display-5 fw-bold mb-1" id="riesgo-score">--</div>
                           <span class="badge rounded-pill px-3 py-2" id="badge-riesgo">
                              Sin análisis disponible
                           </span>
                        </div>

                        <!-- Desglose compacto -->
                        <div class="row text-center mb-3 small">
                           <div class="col-3">
                              <small class="text-muted d-block">FMS</small>
                              <strong id="desglose-fms">--</strong>
                           </div>
                           <div class="col-3">
                              <small class="text-muted d-block">Postural</small>
                              <strong id="desglose-postural">--</strong>
                           </div>
                           <div class="col-3">
                              <small class="text-muted d-block">Lesiones</small>
                              <strong id="desglose-lesiones">--</strong>
                           </div>
                           <div class="col-3">
                              <small class="text-muted d-block">Asistencia</small>
                              <strong id="desglose-asistencia">--</strong>
                           </div>
                        </div>

                        <!-- Factores de riesgo -->
                        <h6 class="mb-1">
                           <i class="bi bi-exclamation-circle text-warning me-1"></i>
                           Factores de riesgo detectados
                        </h6>
                        <ul class="list-group list-group-flush mb-3 small" id="lista-factores">
                           <li class="list-group-item text-muted fst-italic">
                              No hay factores de riesgo disponibles.
                           </li>
                        </ul>

                        <!-- Recomendaciones -->
                        <h6 class="mb-1">
                           <i class="bi bi-lightbulb text-primary me-1"></i>
                           Recomendaciones de la IA
                        </h6>
                        <ul class="list-group list-group-flush small" id="lista-recomendaciones">
                           <li class="list-group-item text-muted fst-italic">
                              No hay recomendaciones disponibles.
                           </li>
                        </ul>

                     </div>
                  </div>
               </div>

            </div>
         </div>
         <div class="modal-footer bg-white border-top py-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-2"></i>Cerrar</button>
         </div>
      </div>
   </div>
</div>
