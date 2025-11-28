<!-- Modal: Historial de Evaluaciones -->
<div class="modal fade" id="modalHistorialEvaluaciones" tabindex="-1" aria-labelledby="modalHistorialEvaluacionesLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="modalHistorialEvaluacionesLabel"><i class="fas fa-history me-2"></i>Historial de Evaluaciones del Atleta</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
         </div>
         <div class="modal-body">
            <!-- Selector de tipo de historial -->
            <div class="mb-3">
               <label for="hist_tipo" class="form-label"><strong>Seleccione el tipo de historial:</strong></label>
               <select name="hist_tipo" id="hist_tipo" class="form-select">
                  <option value="">-- Seleccione --</option>
                  <option value="postural">Tests Posturales</option>
                  <option value="fms">Tests FMS</option>
                  <option value="lesiones">Lesiones</option>
               </select>
            </div>

            <!-- Tabla de historial -->
            <div class="table-responsive">
               <table class="table table-striped table-hover" id="tablaHistorialEvaluaciones">
                  <thead class="table-dark">
                     <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Detalle</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td colspan="5" class="text-center">Seleccione un tipo de historial</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
         </div>
      </div>
   </div>
</div>
