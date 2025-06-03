<?php
$p = $_GET["p"];
if (!empty(ID_USUARIO)) {
   echo "<script>
    var idUsuario = " . ID_USUARIO . ";
    var pagina = 1;
    </script>";
}
?>
<nav class="navbar navbar-expand-lg bg-primary sticky-top" data-bs-theme="dark">
   <div class="container-fluid">
      <a class="navbar-brand" href="?p=landing">Gimnasio Eddie Suarez UPTAEB</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
         aria-controls="navbarNav" aria-expanded="false" aria-label="Expandir menú de navegacion">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
         <ul class="navbar-nav">
            <?php
            if (!empty(ID_USUARIO)):
            ?>
               <li class="nav-item d-none d-lg-block">
                  <a class="nav-link<?= $p == in_array($p, ["", "dashboard"]) ? " active" : "" ?>" href="?p=dashboard"
                     <?= in_array($p, ["", "dashboard"]) ? "aria-current='page'" : "" ?>><i class="fas fa-house"
                        aria-label="Página de inicio"></i></a>
               </li>
               <?php
               if (($permisosNav[0]["leer"] ?? false) || ($permisosNav[1]["leer"] ?? false) || ($permisosNav[2]["leer"] ?? false)):
               ?>
                  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle ps-3 ps-lg-2<?= in_array($p, ["atletas", "entrenadores", "rolespermisos"]) ? " active" : "" ?>"
                        data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">Gestionar
                        Usuarios</a>
                     <div class="dropdown-menu" data-bs-popper="static">
                        <?php
                        if (($permisosNav[0]["leer"] ?? false)):
                        ?>
                           <a class="dropdown-item<?= $p == "entrenadores" ? " active" : "" ?>" href="?p=entrenadores"
                              <?= $p == "entrenadores" ? "aria-current='page'" : "" ?>>Entrenadores</a>
                        <?php
                        endif;
                        ?>
                        <?php
                        if (($permisosNav[1]["leer"] ?? false)):
                        ?>
                           <a class="dropdown-item<?= $p == "atletas" ? " active" : "" ?>" href="?p=atletas" <?= $p == "atletas" ? "aria-current='page'" : "" ?>>Atletas</a>
                        <?php
                        endif;
                        if (($permisosNav[0]["leer"] && $permisosNav[2]["leer"]) || ($permisosNav[1]["leer"] && $permisosNav[2]["leer"])) {
                           echo "<div class='dropdown-divider'></div>";
                        }
                        if (($permisosNav[2]["leer"] ?? false)):
                        ?>
                           <a class="dropdown-item<?= $p == "rolespermisos" ? " active" : "" ?>" href="?p=rolespermisos"
                              <?= $p == "rolespermisos" ? "aria-current='page'" : "" ?>>Roles y permisos</a>
                        <?php
                        endif;
                        ?>
                     </div>
                  </li>
               <?php
               endif;
               ?>
               <?php
               if (($permisosNav[3]["leer"] ?? false)):
               ?>
                  <li class="nav-item">
                     <a class="nav-link ps-3 ps-lg-2<?= $p == "asistencias" ? " active" : "" ?>" href="?p=asistencias"
                        <?= $p == "asistencias" ? "aria-current='page'" : "" ?>>Asistencias</a>
                  </li>

               <?php
               endif;
               ?>
               <?php
               if (($permisosNav[4]["leer"] ?? false)):
               ?>
                  <li class="nav-item">
                     <a class="nav-link ps-3 ps-lg-2<?= $p == "eventos" ? " active" : "" ?>" href="?p=eventos" <?= $p == "eventos" ? "aria-current='page'" : "" ?>>Eventos</a>
                  </li>
               <?php
               endif;
               ?>
               <?php
               if (($permisosNav[5]["leer"] ?? false)):
               ?>
                  <li class="nav-item">
                     <a class="nav-link ps-3 ps-lg-2<?= $p == "mensualidad" ? " active" : "" ?>" href="?p=mensualidad"
                        <?= $p == "mensualidad" ? "aria-current='page'" : "" ?>>Mensualidad</a>
                  </li>
               <?php
               endif;
               ?>
               <?php
               if (($permisosNav[6]["leer"] ?? false)):
               ?>
                  <li class="nav-item">
                     <a class="nav-link ps-3 ps-lg-2<?= $p == "wada" ? " active" : "" ?>" href="?p=wada" <?= $p == "wada" ? "aria-current='page'" : "" ?>>WADA</a>
                  </li>
               <?php
               endif;
               ?>
               <li class="nav-item dropdown d-none d-lg-block">
                  <a class="nav-link px-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                     aria-expanded="false" aria-label="Notificaciones" title="Notificaciones"><i class="fa-solid fa-bell" id="icono-notificaciones"></i>
                     <span
                        class="position-absolute top-25 start-75 translate-middle badge rounded-circle p-2 bg-danger border border-light fa-beat-fade d-none"
                        id="contador-notificaciones">
                        <span class="visually-hidden">Notificaciones sin leer</span>
                     </span>
                  </a>
                  <?php require_once __DIR__ . "/notificaciones.php"; ?>
               </li>
               <li class="nav-item dropdown d-lg-none">
                  <a class="nav-link ps-3" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                     aria-expanded="false">Notificaciones</a>
                  <div class="dropdown-menu dropdown-menu-end" data-bs-popper="static">
                     <div class="ms-2">Notificaciones</div>
                     <div class="dropdown-divider"></div>
                  </div>
                  </a>
               </li>
               <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle px-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                     aria-expanded="false" aria-label="Menu de usuario" title="Menu de usuario"><i class="fa-solid fa-circle-user d-none d-lg-inline-block"></i>
                     <span class="d-lg-none">Menú de usuario</span>
                  </a>
                  <?php require_once __DIR__ . "/menu_usuario.php"; ?>
               </li>
            <?php
            else:
            ?>
               <li class="nav-item">
                  <a class="nav-link ps-3" href="?p=login">Iniciar sesión</a>
               </li>
            <?php
            endif;
            ?>
         </ul>
      </div>
   </div>
</nav>
<?php require_once "modal_notificaciones.php" ?>