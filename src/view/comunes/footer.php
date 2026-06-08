<footer class="site-footer mt-auto">
   <div class="container">
      <div class="row py-5">
         <div class="col-12 col-md-4 mb-4 mb-md-0">
            <h5 class="footer-title">Contacto</h5>
            <ul class="footer-nav">
               <li>
                  <a href="mailto:jugneycontacto@gmail.com" class="footer-link">
                     <i class="fas fa-envelope"></i>
                     <span>correogimnasio@gmail.com</span>
                  </a>
               </li>
               <li>
                  <a href="tel:+58 1234586" class="footer-link">
                     <i class="fas fa-phone"></i>
                     <span>+58 1234586</span>
                  </a>
               </li>
            </ul>
         </div>
         <div class="col-12 col-md-4 mb-4 mb-md-0">
            <h5 class="footer-title">Ayuda</h5>
            <ul class="footer-nav">
               <li>
                  <a href="#" class="footer-link">
                     <i class="fas fa-question-circle"></i>
                     <span>Preguntas Frecuentes</span>
                  </a>
               </li>
               <li>
                  <a href="./?p=manual" class="footer-link">
                     <i class="fas fa-life-ring"></i>
                     <span>Manual de ayuda</span>
                  </a>
               </li>
            </ul>
         </div>
         <div class="col-12 col-md-4">
            <h5 class="footer-title">Más</h5>
            <ul class="footer-nav">
               <li>
                  <a href="#" class="footer-link">
                     <i class="fas fa-file-alt"></i>
                     <span>Términos de Uso</span>
                  </a>
               </li>
               <li>
                  <a href="#" class="footer-link">
                     <i class="fas fa-shield-alt"></i>
                     <span>Privacidad</span>
                  </a>
               </li>
            </ul>
         </div>
      </div>
      <div class="footer-bottom">
         <p class="mb-0">&copy; <?= date('Y') ?> Gimnasio Eddie Suárez UPTAEB. Todos los derechos reservados.</p>
      </div>
   </div>
</footer>
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
