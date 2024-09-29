<!doctype html>
<html lang="es">
<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>
<!-- [ navigation menu ] start -->
<nav class="pcoded-navbar fixed top-0 left-0 right-0 z-50">
  <div class="navbar-wrapper  ">
    <div class="navbar-content scroll-div ">

      <div class="">
        <div class="main-menu-header">
          <!-- <img class="img-radius" src="dist/assets/images/user/avatar.png" alt="User-Profile-Image"> -->
          <img class="img-radius" src="public/assets/logo.ico">
          <div class="user-details">
            <?php
            if (isset($_SESSION['nombreDePersona'])) {
              echo htmlspecialchars($_SESSION['area'], ENT_QUOTES, 'UTF-8');
            } else {
              echo "Usuario no logueado";
            }
            ?>
          </div>
        </div>
        <div class="collapse" id="nav-user-link">
          <ul class="list-unstyled">
            <li class="list-group-item"><a href="user-profile.html"><i class="feather icon-user m-r-5"></i>View Profile</a></li>
            <li class="list-group-item"><a href="#!"><i class="feather icon-settings m-r-5"></i>Settings</a></li>
            <li class="list-group-item"><a href="logout.php"><i class="feather icon-log-out m-r-5"></i>Cerrar Sesi&oacute;n</a></li>
          </ul>
        </div>
      </div>

      <ul class="nav pcoded-inner-navbar ">
        <li class="nav-item pcoded-menu-caption">
          <!-- <label>Navegaci&oacute;n</label> -->
        </li>
        <!-- Navegacion -->
        <li class="nav-item">
          <a href="inicio.php" class="nav-link ">
            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
            <span class="pcoded-mtext">Inicio </span>
          </a>
        </li>

        <li class="nav-item pcoded-menu-caption">
          <label>Registros</label>
        </li>

        <!-- Registros -->
        <li class="nav-item transition-transform duration-300 hover:translate-x-1">
          <a href="registro-incidencia-user.php" class="nav-link ">
            <span class="pcoded-micon"> <i class="feather icon-edit"></i> </span>
            <span class="pcoded-mtext">Registrar incidencia</span>
          </a>
        </li>

        <!-- Consultas -->
        <li class="nav-item pcoded-menu-caption">
          <label>Consultas</label>
        </li>
        <li class="nav-item transition-transform duration-300 hover:translate-x-1">
          <a href="consultar-incidencia-user.php" class="nav-link ">
            <span class="pcoded-micon"> <i class="feather icon-clipboard"></i> </span>
            <span class="pcoded-mtext">Consultar incidencias</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- [ navigation menu ] end -->