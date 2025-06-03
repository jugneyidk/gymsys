<div class="dropdown-menu dropdown-menu-end pe-0 py-0" id="menuUsuario" aria-labelledby="usuarioMenu">
   <ul class="list-group">
      <li class="list-group-item">
         <div class="rounded-0 py-1 d-flex justify-content-between align-items-center">
            <div class="mb-0 h6 text-nowrap">Menú de usuario</div>
         </div>
      </li>
      <li class="list-group-item justify-content-between">
         <div class="rounded-0 py-1 fw-bold text-center text-nowrap">
            <?= htmlspecialchars($permisosNav['usuario']['nombre']) ?>
         </div>
         <div class="rounded-0 py-1 text-center text-nowrap">
            <small><?= htmlspecialchars($permisosNav['usuario']['rol']) ?></small>
         </div>
      </li>
      <?php
      if (($permisosNav[7]["leer"] ?? false)):
      ?>
         <li class="list-group-item list-group-item-action justify-content-between">
            <a class="mb-0 text-decoration-none text-white" role="button" href="?p=reportes">
               <div class="rounded-0 py-1 d-flex justify-content-between align-items-center">
                  Reportes <i class="fa-solid fa-chart-simple" title="Reportes"></i>
               </div>
            </a>
         </li>
      <?php
      endif;
      ?>
      <?php
      if (($permisosNav[8]["leer"] ?? false)):
      ?>
         <li class="list-group-item list-group-item-action justify-content-between">
            <a class="mb-0 text-decoration-none text-white" role="button" href="?p=bitacora">
               <div class="rounded-0 py-1 d-flex justify-content-between align-items-center">
                  Bitácora <i class="fa-solid fa-book" title="Bitácora"></i>
               </div>
            </a>
         </li>
      <?php
      endif;
      ?>
      <li class="list-group-item list-group-item-action justify-content-between">
         <a class="mb-0 text-decoration-none text-white" role="button" href="#" id="botonTema">
            <div class="rounded-0 py-1 d-flex justify-content-between align-items-center">
               Tema <i class="fa-solid fa-moon" title="Cambiar a modo claro"></i>
            </div>
         </a>
      </li>
      <li class="list-group-item list-group-item-action justify-content-between">
         <a class="mb-0 text-decoration-none text-white" role="button" href="?p=logout&accion=logout" id="botonLogout">
            <div class="rounded-0 py-1 d-flex justify-content-between align-items-center">
               Cerrar sesión <i class="fa-solid fa-right-from-bracket" title="Cerrar sesión"></i>
            </div>
         </a>
      </li>
   </ul>
</div>
<script type="module">
   import {
      handleTheme
   } from './assets/js/comunes.js';
   const themeButton = document.getElementById('botonTema');
   if (themeButton) {
      themeButton.addEventListener('click', handleTheme);
   }
   const logoutButton = document.getElementById('botonLogout');
   if (logoutButton) {
      logoutButton.addEventListener('click', () => localStorage.clear());
   }
</script>