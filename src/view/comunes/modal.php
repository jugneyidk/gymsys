<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="modalTitulo"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar Modal"></button>
         </div>
         <div class="modalBody" id="modalBody">
            <div class="container-lg">
               <?php isset($formulario) ? require_once __DIR__ . "/../formularios/{$formulario}.php" : null; ?>
            </div>
         </div>
      </div>
   </div>
</div>