<?php
// Se incluyen los modelos necesarios para la funcionalidad del script
require_once 'app/Model/UsuarioModel.php';  // Modelo de Usuario para gestionar los datos de usuario
require_once './app/Model/MantenimientoModel.php';  // Modelo de Mantenimiento para las funciones relacionadas con el mantenimiento
require_once './app/Model/IncidenciaModel.php';  // Modelo de Incidencia para gestionar las incidencias

// Verificación de si existe una sesión activa del usuario
if (isset($_SESSION['codigoUsuario'])) {
  // Si la sesión existe, se asigna el código de usuario a la variable $user_id
  $user_id = $_SESSION['codigoUsuario'];

  $usuario = new UsuarioModel();  // Se crea una instancia del modelo Usuario para obtener o manipular datos del usuario
} else {
  $perfil = null;
}
// Se crea una instancia del modelo 
$mantenimientoModel = new MantenimientoModel();
$incidenciasModel = new IncidenciaModel();

$incidenciasMantenimiento = $mantenimientoModel->notificarIncidenciasMantenimiento($user_id);
$incidenciasRecepcion = $incidenciasModel->contarIncidenciasAdministrador();
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
                    <span class="absolute inset-0 w-3 h-3 bg-lime-200 rounded-full"></span>
                  </span>
                <?php endif; ?>
                Recepci&oacute;n
              </a>
            </li>
            <li class=" transition-transform duration-300 hover:translate-x-1">
              <a href="registro-mantenimiento.php" class="flex items-center">
                <?php if ($incidenciasMantenimiento > 0): ?>
                  <span class="absolute right-[50px] top-[40%] transform -translate-y-1/2 w-3 h-3 bg-green-200 rounded-full animate-ping">
                    <span class="absolute inset-0 w-3 h-3 bg-green-500 rounded-full"></span>
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
        <li class="nav-item">
          <a href="reportes.php" class="nav-link ">
            <span class="pcoded-micon"> <i class="feather icon-file"></i> </span>
            <span class="pcoded-mtext">Reportes de incidencias</span>
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