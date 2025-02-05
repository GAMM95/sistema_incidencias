<!doctype html>
<html lang="es">
<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once './app/Model/IncidenciaModel.php';
require_once './app/Model/RecepcionModel.php';
require_once './app/Model/AsignacionModel.php';
require_once './app/Model/MantenimientoModel.php';

$incidenciasModel = new IncidenciaModel();
$recepcionModel = new RecepcionModel();
$asignacionModel = new AsignacionModel();
$mantenimientoModel = new MantenimientoModel();

$incidenciasRecepcion = $incidenciasModel->contarIncidenciasAdministrador();
$incidenciasAsignacion = $recepcionModel->contarRecepciones();
$incidenciasEnEspera = $asignacionModel->contarRecepcionesEnEsperaUltimoMesAdministrador();
$incidenciasCerrar = $mantenimientoModel->contarIncidenciasFinalizadas();
?>
<!-- [ navigation menu ] start -->
<nav class="pcoded-navbar fixed top-0 left-0 right-0 z-50">
  <div class="navbar-wrapper">
    <div class="navbar-content scroll-div">
      <div class="">
        <div class="main-menu-header">
          <img class="img-radius" src="public/assets/logo.ico">
          <div class="user-details">
            <!-- En caso de que el usuario no esté logueado, aparecerá el nombre de usuario -->
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

      <ul class="nav pcoded-inner-navbar">
        <li class="nav-item pcoded-menu-caption">
        </li>
        <!-- Navegacion -->
        <li class="nav-item">
          <a href="inicio.php" class="nav-link ">
            <span class="pcoded-micon"> <i class="feather icon-home"></i></span>
            <span class="pcoded-mtext">Inicio </span>
          </a>
        </li>

        <li class="nav-item pcoded-menu-caption">
          <label>Registros</label>
        </li>
        <!-- Registros -->
        <li class="nav-item pcoded-hasmenu">
          <a href="#!" class="nav-link">
            <span class="pcoded-micon">
              <i class="feather icon-edit"></i>
            </span>
            <span class="pcoded-mtext">Registrar</span>
          </a>
          <ul class="pcoded-submenu space-y-2 mt-2">
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="registro-incidencia.php">Incidencia</a>
            </li>
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="registro-recepcion.php" class="flex items-center">
                <?php if ($incidenciasRecepcion > 0): ?>
                  <span class="absolute right-[50px] top-[40%] transform -translate-y-1/2 w-3 h-3 bg-lime-200 rounded-full animate-ping">
                    <span class="absolute inset-0 w-3 h-3 bg-lime-500 rounded-full"></span>
                  </span>
                <?php endif; ?>
                Recepci&oacute;n
              </a>
            </li>
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="registro-asignacion.php" class="flex items-center">
                <?php if ($incidenciasAsignacion > 0): ?>
                  <span class="absolute right-[50px] top-[40%] transform -translate-y-1/2 w-3 h-3 bg-green-200 rounded-full animate-ping">
                    <span class="absolute inset-0 w-3 h-3 bg-green-500 rounded-full"></span>
                  </span>
                <?php endif; ?>
                Asignaci&oacute;n
              </a>
            </li>
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="registro-mantenimiento.php" class="flex items-center">
                <?php if ($incidenciasEnEspera > 0): ?>
                  <span class="absolute right-[50px] top-[40%] transform -translate-y-1/2 w-3 h-3 bg-green-200 rounded-full animate-ping">
                    <span class="absolute inset-0 w-3 h-3 bg-darkgreen-700 rounded-full"></span>
                  </span>
                <?php endif; ?>
                Mantenimiento
              </a>
            </li>
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="registro-cierre.php" class="flex items-center">
                <?php if ($incidenciasCerrar > 0): ?>
                  <span class="absolute right-[50px] top-[40%] transform -translate-y-1/2 w-3 h-3 bg-blue-200 rounded-full animate-ping">
                    <span class="absolute inset-0 w-3 h-3 bg-blue-500 rounded-full"></span>
                  </span>
                <?php endif; ?>
                Cierre
              </a>
            </li>
          </ul>
        </li>

        <!-- Consultas -->
        <li class="nav-item pcoded-menu-caption">
          <label>Consultas</label>
        </li>
        <li class="nav-item pcoded-hasmenu">
          <a href="#!" class="nav-link ">
            <span class="pcoded-micon">
              <i class="feather icon-clipboard"></i>
            </span>
            <span class="pcoded-mtext">Consultar</span>
          </a>
          <ul class="pcoded-submenu">
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="consultar-totales.php">Incidencias totales</a>
            </li>
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="consultar-pendientes.php">Incidencias pendientes</a>
            </li>
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="consultar-cierres.php">Incidencias cerradas</a>
            </li>
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="consultar-asignaciones.php">Incidencias asignadas</a>
            </li>
          </ul>
        </li>
        <!-- Fin de opcion de consultas -->

        <!-- Reportes -->
        <li class="nav-item pcoded-menu-caption">
          <label>Reportes</label>
        </li>
        <!-- Reportes de incidencias -->
        <li class="nav-item">
          <a href="reportes.php" class="nav-link ">
            <span class="pcoded-micon"> <i class="feather icon-file"></i> </span>
            <span class="pcoded-mtext">Reportes de incidencias</span>
          </a>
        </li>
        <!-- Reportes de auditoria -->
        <li class="nav-item">
          <a href="auditoria.php" class="nav-link ">
            <span class="pcoded-micon"> <i class="feather icon-list"></i> </span>
            <span class="pcoded-mtext">Reportes de auditor&iacute;a</span>
          </a>
        </li>
        <!-- fin de opcion Reportes -->

        <!-- Mantenedor -->
        <li class="nav-item pcoded-menu-caption">
          <label>Mantenedores</label>
        </li>
        <li class="nav-item pcoded-hasmenu">
          <a href="#!" class="nav-link ">
            <span class="pcoded-micon">
              <i class="feather icon-server"></i>
            </span>
            <span class="pcoded-mtext">Mantenedor</span>
          </a>
          <ul class="pcoded-submenu">
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="modulo-usuario.php">Usuarios</a>
            </li>
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="modulo-persona.php">Personas</a>
            </li>
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="modulo-area.php">&Aacute;reas</a>
            </li>
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="modulo-bien.php">Bienes</a>
            </li>
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="modulo-categoria.php">Categor&iacute;as</a>
            </li>
            <li class="transition-transform duration-300 hover:translate-x-1">
              <a href="modulo-solucion.php">Soluciones</a>
            </li>
          </ul>
        </li>
        <!-- Fin de opcion mantenedor -->
      </ul>
    </div>
  </div>
</nav>
<!-- [ navigation menu ] end -->