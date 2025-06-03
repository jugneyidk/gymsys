<div class="container-fluid mt-auto bg-dark">
   <div class="container">
      <footer class="row row-cols-3 py-5">
         <div class="col-12 col-md mb-3 mb-md-0">
            <span class="text-white h5 mb-2 d-inline-block">Contacto</span>
            <ul class="nav flex-column">
               <li class="nav-item mb-2">
                  <a href="mailto:jugneycontacto@gmail.com" class="nav-link p-0 text-white">
                     <i class="fas fa-envelope"></i> correogimnasio@gmail.com
                  </a>
               </li>
               <li class="nav-item mb-2">
                  <a href="tel:+58 1234586" class="nav-link p-0 text-white">
                     <i class="fas fa-phone"></i> +58 1234586
                  </a>
               </li>
            </ul>
         </div>
         <div class="col-12 col-md mb-3 mb-md-0">
            <span class="text-white h5 mb-2 d-inline-block">Ayuda</span>
            <ul class="nav flex-column">
               <li class="nav-item mb-2">
                  <a href="#" class="nav-link p-0 text-white">
                     <i class="fas fa-question-circle"></i> Preguntas Frecuentes
                  </a>
               </li>
               <li class="nav-item mb-2">
                  <a href="./?p=manual" class="nav-link p-0 text-white">
                     <i class="fas fa-life-ring"></i> Manual de ayuda
                  </a>
               </li>
            </ul>
         </div>
         <div class="col-12 col-md mb-3 mb-md-0">
            <span class="text-white h5 mb-2 d-inline-block">Más</span>
            <ul class="nav flex-column">
               <li class="nav-item mb-2">
                  <a href="#" class="nav-link p-0 text-white">
                     <i class="fas fa-file-alt"></i> Términos de Uso
                  </a>
               </li>
               <li class="nav-item mb-2">
                  <a href="#" class="nav-link p-0 text-white">
                     <i class="fas fa-shield-alt"></i> Privacidad
                  </a>
               </li>
            </ul>
         </div>
      </footer>
   </div>
</div>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/sweetalert.js"></script>
<script>
   $(function() {
      $('[data-tooltip="tooltip"]').each(function() {
         bootstrap.Tooltip.getOrCreateInstance(this, {
            trigger: 'manual',
            placement: $(this).data('bs-placement') || 'top'
         });
      });
      $(document)
         .on('mouseenter', '[data-tooltip="tooltip"]', function() {
            bootstrap.Tooltip.getOrCreateInstance(this).show();
         })
         .on('mouseleave', '[data-tooltip="tooltip"]', function() {
            bootstrap.Tooltip.getOrCreateInstance(this).hide();
         });
   });
</script>