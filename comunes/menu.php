<?php
require_once("modelo/permisos.php");
if (isset($_SESSION['rol'])) {
  $permisos_navbar = $permisos_o->permisos_nav();
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
        if (isset($_SESSION["id_usuario"])):
          ?>
          <li class="nav-item d-none d-lg-block">
            <a class="nav-link<?php echo $p == in_array($p, ["", "dashboard"]) ? " active" : "" ?>" href="?p=dashboard"
              <?php echo in_array($p, ["", "dashboard"]) ? "aria-current='page'" : "" ?>><i class="fas fa-house"
                aria-label="Página de inicio"></i></a>
          </li>
          <?php
          if (($permisos_navbar[0]["leer"] ?? false) || ($permisos_navbar[1]["leer"] ?? false) || ($permisos_navbar[2]["leer"] ?? false)):
            ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle ps-3 ps-lg-2<?php echo in_array($p, ["atletas", "entrenadores", "rolespermisos"]) ? " active" : "" ?>"
                data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">Gestionar
                Usuarios</a>
              <div class="dropdown-menu" data-bs-popper="static">
                <?php
                if (($permisos_navbar[0]["leer"] ?? false)):
                  ?>
                  <a class="dropdown-item<?php echo $p == "entrenadores" ? " active" : "" ?>" href="?p=entrenadores" <?php echo $p == "entrenadores" ? "aria-current='page'" : "" ?>>Entrenadores</a>
                  <?php
                endif;
                ?>
                <?php
                if (($permisos_navbar[1]["leer"] ?? false)):
                  ?>
                  <a class="dropdown-item<?php echo $p == "atletas" ? " active" : "" ?>" href="?p=atletas" <?php echo $p == "atletas" ? "aria-current='page'" : "" ?>>Atletas</a>
                  <?php
                endif;
                ?>
                <?php
                if (($permisos_navbar[2]["leer"] ?? false)):
                  ?>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item<?php echo $p == "rolespermisos" ? " active" : "" ?>" href="?p=rolespermisos" <?php echo $p == "rolespermisos" ? "aria-current='page'" : "" ?>>Roles y permisos</a>
                  <?php
                endif;
                ?>
              </div>
            </li>
            <?php
          endif;
          ?>
          <?php
          if (($permisos_navbar[3]["leer"] ?? false)):
            ?>
            <li class="nav-item">
              <a class="nav-link ps-3 ps-lg-2<?php echo $p == "asistencias" ? " active" : "" ?>" href="?p=asistencias" <?php echo $p == "asistencias" ? "aria-current='page'" : "" ?>>Asistencias</a>
            </li>

            <?php
          endif;
          ?>
          <?php
          if (($permisos_navbar[4]["leer"] ?? false)):
            ?>
            <li class="nav-item">
              <a class="nav-link ps-3 ps-lg-2<?php echo $p == "eventos" ? " active" : "" ?>" href="?p=eventos" <?php echo $p == "eventos" ? "aria-current='page'" : "" ?>>Eventos</a>
            </li>
            <?php
          endif;
          ?>
          <?php
          if (($permisos_navbar[5]["leer"] ?? false)):
            ?>
            <li class="nav-item">
              <a class="nav-link ps-3 ps-lg-2<?php echo $p == "mensualidad" ? " active" : "" ?>" href="?p=mensualidad" <?php echo $p == "mensualidad" ? "aria-current='page'" : "" ?>>Mensualidad</a>
            </li>
            <?php
          endif;
          ?>
          <?php
          if (($permisos_navbar[6]["leer"] ?? false)):
            ?>
            <li class="nav-item">
              <a class="nav-link ps-3 ps-lg-2<?php echo $p == "wada" ? " active" : "" ?>" href="?p=wada" <?php echo $p == "wada" ? "aria-current='page'" : "" ?>>WADA</a>
            </li>
            <?php
          endif;
          ?>
          <?php
          if (($permisos_navbar[7]["leer"] ?? false)):
            ?>
            <li class="nav-item">
              <a class="nav-link ps-3 ps-lg-2<?php echo $p == "reportes" ? " active" : "" ?>" href="?p=reportes" <?php echo $p == "reportes" ? "aria-current='page'" : "" ?>>Reportes</a>
            </li>
            <?php
          endif;
          ?>
          <?php
          if (($permisos_navbar[8]["leer"] ?? false)):
            ?>
            <li class="nav-item">
              <a class="nav-link ps-3 ps-lg-2<?php echo $p == "bitacora" ? " active" : "" ?>" href="?p=bitacora" <?php echo $p == "bitacora" ? "aria-current='page'" : "" ?>>Bitacora</a>
            </li>
            <?php
          endif;
          ?>
          <li class="nav-item dropdown d-none d-lg-block">
            <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
              aria-expanded="false" aria-label="Notificaciones"><i class="fa-solid fa-bell"></i></a>
            <div class="dropdown-menu dropdown-menu-end" data-bs-popper="static">
              <div class="ms-2">Notificaciones</div>
              <div class="dropdown-divider"></div>
            </div>
            </a>
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
          <li class="nav-item d-lg-none">
            <a class="nav-link ps-3" href="?p=cerrarsesion">Cerrar sesión</a>
          </li>
          <li class="nav-item d-none d-lg-block">
            <a class="nav-link" href="?p=cerrarsesion" aria-label="Cerrar sesión"><i
                class="fa-solid fa-right-from-bracket"></i></a>
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