<nav class="navbar navbar-expand-lg bg-info" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="?p=landing">Gimnasio Eddy Suarez UPTAEB</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <?php
        if (isset($_SESSION["id_usuario"])):
          ?>
          <li class="nav-item">
            <a class="nav-link" href="?p=dashboard"><i class="fas fa-house"></i></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
              aria-expanded="false">Gestionar Usuarios</a>
            <div class="dropdown-menu" data-bs-popper="static">
              <a class="dropdown-item" href="?p=atletas">Atletas</a>
              <a class="dropdown-item" href="?p=entrenadores">Entrenadores</a>
              <a class="dropdown-item" href="?p=rolespermisos">Roles y permisos</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?p=asistencias">Asistencias</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?p=eventos">Eventos/competencia</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?p=mensualidad">Mensualidad</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?p=wada">Status Wada</a>
          </li>          
          <li class="nav-item">
            <a class="nav-link" href="?p=reportes">Reportes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?p=bitacora">Bitacora</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="?p=cerrarsesion">Salir</a>
          </li>
          <?php
        else:
          ?>
          <li class="nav-item">
            <a class="nav-link" href="?p=login">Iniciar sesion</a>
          </li>
          <?php
        endif;
        ?>
      </ul>
    </div>
  </div>
</nav>