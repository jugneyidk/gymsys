<!-- Modal: Registrar / Editar Test Postural -->
<div class="modal fade modal-evaluaciones" id="modalTestPostural" tabindex="-1" aria-labelledby="modalTestPosturalLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalTestPosturalLabel"><i class="bi bi-person-standing me-2"></i>Test Postural - Evaluación de Postura</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
         </div>
         <div class="modal-body">
            <?php require_once __DIR__ . "/../formularios/test_postural.php"; ?>
         </div>
      </div>
   </div>
</div>

<!-- Modal: Registrar / Editar Test FMS -->
<div class="modal fade modal-evaluaciones" id="modalTestFms" tabindex="-1" aria-labelledby="modalTestFmsLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalTestFmsLabel"><i class="bi bi-activity me-2"></i>Test FMS (Functional Movement Screen)</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
         </div>
         <div class="modal-body">
            <?php require_once __DIR__ . "/../formularios/test_fms.php"; ?>
         </div>
      </div>
   </div>
</div>

<!-- Modal: Registrar / Editar Lesión -->
<div class="modal fade modal-evaluaciones" id="modalLesion" tabindex="-1" aria-labelledby="modalLesionLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="modalLesionLabel"><i class="bi bi-bandaid me-2"></i>Registro de Lesión</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
         </div>
         <div class="modal-body">
            <?php require_once __DIR__ . "/../formularios/lesion.php"; ?>
         </div>
      </div>
   </div>
</div>
