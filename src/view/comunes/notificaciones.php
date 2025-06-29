<div class="dropdown-menu dropdown-menu-end pe-0 py-0" data-bs-popper="static" id="menuNotificaciones" aria-labelledby="verNotificacionesRecientes">
   <ul class="list-group">
      <li class="list-group-item">
         <div class="rounded-0 py-1 d-flex justify-content-between align-items-center">
            <div class="mb-0 h6 text-nowrap"><i id="websocketNotificaciones" class="fa-solid fa-circle fa-beat-fade fa-2xs d-inline-block"></i> Notificaciones</div>
            <button class="btn btn-outline-light" aria-label="Marcar todas las notificaciones como leídas"
               title="Marcar todo como leído" id="marcar-todo-leido" data-tooltip="tooltip" data-bs-placement="left">
               <i class="fa-solid fa-envelope-open"></i>
            </button>
         </div>
      </li>
      <div>
         <ul class="list-group rounded-0" id="contenedor-notificaciones">
            <li class="list-group-item list-group-item-secondary">
               <div class="text-nowrap p-2">
                  <div class="w-100 text-center">
                     <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Cargando notificaciones...</span>
                     </div>
                  </div>
               </div>
            </li>
         </ul>
      </div>
      <li class="list-group-item list-group-item-primary list-group-item-action" role="button"
         id="ver-todas-notificaciones" data-bs-toggle="modal" data-bs-target="#modalVerNotificaciones">
         <div class="text-nowrap text-center">
            <span class="mb-1">Ver todas</span>
         </div>
      </li>
   </ul>
</div>