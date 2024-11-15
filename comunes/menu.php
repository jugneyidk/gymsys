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
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <?php
        if (isset($_SESSION["id_usuario"])):
          ?>
          <li class="nav-item d-none d-lg-block">
            <a class="nav-link" href="?p=dashboard"><i class="fas fa-house" aria-label="Página de inicio"></i></a>
          </li>
          <?php
          if (($permisos_navbar[0]["leer"] ?? false) || ($permisos_navbar[1]["leer"] ?? false) || ($permisos_navbar[2]["leer"] ?? false)):
            ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                aria-expanded="false">Gestionar Usuarios</a>
              <div class="dropdown-menu" data-bs-popper="static">
                <?php
                if (($permisos_navbar[0]["leer"] ?? false)):
                  ?>
                  <a class="dropdown-item" href="?p=entrenadores">Entrenadores</a>
                  <?php
                endif;
                ?>
                <?php
                if (($permisos_navbar[1]["leer"] ?? false)):
                  ?>
                  <a class="dropdown-item" href="?p=atletas">Atletas</a>
                  <?php
                endif;
                ?>
                <?php
                if (($permisos_navbar[2]["leer"] ?? false)):
                  ?>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="?p=rolespermisos">Roles y permisos</a>
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
              <a class="nav-link" href="?p=asistencias">Asistencias</a>
            </li>

            <?php
          endif;
          ?>
          <?php
          if (($permisos_navbar[4]["leer"] ?? false)):
            ?>
            <li class="nav-item">
              <a class="nav-link" href="?p=eventos">Eventos</a>
            </li>
            <?php
          endif;
          ?>
          <?php
          if (($permisos_navbar[5]["leer"] ?? false)):
            ?>
            <li class="nav-item">
              <a class="nav-link" href="?p=mensualidad">Mensualidad</a>
            </li>
            <?php
          endif;
          ?>
          <?php
          if (($permisos_navbar[6]["leer"] ?? false)):
            ?>
            <li class="nav-item">
              <a class="nav-link" href="?p=wada">WADA</a>
            </li>
            <?php
          endif;
          ?>
          <?php
          if (($permisos_navbar[7]["leer"] ?? false)):
            ?>
            <li class="nav-item">
              <a class="nav-link" href="?p=reportes">Reportes</a>
            </li>
            <?php
          endif;
          ?>
          <?php
          if (($permisos_navbar[8]["leer"] ?? false)):
            ?>
            <li class="nav-item">
              <a class="nav-link" href="?p=bitacora">Bitacora</a>
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
            <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
              aria-expanded="false">Notificaciones</a>
            <div class="dropdown-menu dropdown-menu-end" data-bs-popper="static">
              <div class="ms-2">Notificaciones</div>
              <div class="dropdown-divider"></div>
            </div>
            </a>
          </li>
          <li class="nav-item d-lg-none">
            <a class="nav-link" href="?p=cerrarsesion">Cerrar sesión</a>
          </li>
          <li class="nav-item d-none d-lg-block">
            <a class="nav-link" href="?p=cerrarsesion" aria-label="Cerrar sesión"><i class="fa-solid fa-right-from-bracket"></i></a>
          </li>
          <?php
        else:
          ?>
          <li class="nav-item">
            <a class="nav-link" href="?p=login">Iniciar sesión</a>
          </li>
          <?php
        endif;
        ?>
      </ul>
    </div>
  </div>
</nav>