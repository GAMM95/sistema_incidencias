<?php
require_once 'app/Model/UsuarioModel.php';
// Código PHP para obtener los datos del usuario
if (isset($_SESSION['codigoUsuario'])) {
  $user_id = $_SESSION['codigoUsuario'];
  $area = $_SESSION['codigoArea'];
  $usuario = new UsuarioModel();
  $perfil = $usuario->setearDatosUsuario($user_id);
} else {
  $perfil = null;
}
?>
<header class="navbar pcoded-header navbar-expand-lg navbar-light header-dark fixed top-0 left-0 right-0 z-50">
  <div class="m-header">
    <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
    <a href="#!" class="b-brand">
      <!-- ========   change your logo hear   ============ -->
      <img src="dist/assets/images/logo.png" alt="" class="logo">
    </a>
  </div>
  <div class="navbar-collapse">
    <ul class="navbar-nav mr-auto">
      <!-- <li class="nav-item">
        <a href="#!" class="pop-search"><i class="feather icon-search"></i></a>
        <div class="search-bar">
          <input type="text" class="form-control border-0 shadow-none" placeholder="Buscar">
          <button type="button" class="close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </li> -->
      <li class="nav-item ixed flex items-center">
        <div class="dropdown">
          <span class="dropdown-toggle h-drop" href="#" data-toggle="dropdown">
            Sistema de Gesti&oacute;n de Incidencias
          </span>
        </div>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <?php
      require_once './app/Model/IncidenciaModel.php';
      $incidenciaModel = new IncidenciaModel();
      $incidencias = $incidenciaModel->notificacionesUser($area);
      ?>
      <li>
        <div class="dropdown">
          <a class="dropdown-toggle" href="#" data-toggle="dropdown">
            <i class="icon feather icon-bell mr-2"></i>
            <?php if (count($incidencias) > 0) : ?>
              <span class="badge badge-pill badge-danger"><?= count($incidencias); ?></span>
            <?php endif; ?>
          </a>
          <div class="dropdown-menu dropdown-menu-right notification">
            <div class="noti-head">
              <h6 class="d-inline-block m-b-0">Incidencias cerradas del d&iacute;a</h6>
            </div>
            <ul class="noti-body" style="max-height: 250px; overflow-y: auto;">
              <?php if (empty($incidencias)) : ?>
                <li class="notification">
                  <div class="media">
                    <div class="media-body">
                      <p class="text-center">No hay nuevas notificaciones.</p>
                    </div>
                  </div>
                </li>
              <?php else : ?>
                <?php foreach (array_slice($incidencias, 0, 5) as $incidencia) : ?>
                  <li class="notification">
                    <div class="media">
                      <img class="img-radius" src="dist/assets/images/user/avatar.png" alt="User-Profile-Image">
                      <div class="media-body">
                        <p>
                          <strong><?= 'Incidencia: ' . htmlspecialchars($incidencia['INC_numero_formato']); ?></strong>
                          <span class="n-time text-muted">
                            <i class="icon feather icon-clock m-r-9"></i>
                            <?= htmlspecialchars($incidencia['tiempoDesdeCierre']); ?>
                          </span>
                        </p>
                        <p><?= 'Condici&oacute;n: ' . htmlspecialchars($incidencia['CON_descripcion']); ?></p>
                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </li>

      <!-- Perfil de usuario -->
      <li>
        <div class="dropdown drp-user">
          <a href="#" class="dropdown-toggle " data-toggle="dropdown">
            <i class="feather icon-user mr-2 "></i>
            <span>
              <?php
              if (isset($_SESSION['usuario'])) {
                echo '<span class="text-base">' . htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8') . '</span>';
              } else {
                echo "Usuario no logueado";
              }
              ?>
            </span>
            <i class="feather icon-more-vertical ml-4"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right profile-notification">
            <div class="pro-head fixed flex items-center">
              <img class="img-radius" src="dist/assets/images/user/avatar.png" alt="User-Profile-Image">
              <span>
                <?php
                if (isset($_SESSION['nombreDePersona'])) {
                  echo '<span class="text-base">' . htmlspecialchars($_SESSION['nombreDePersona'], ENT_QUOTES, 'UTF-8') . '</span>';
                  echo '<br>';
                  echo '<span class="text-xs text-gray-100">' . htmlspecialchars($_SESSION['rol'], ENT_QUOTES, 'UTF-8') . '</span>';
                } else {
                  echo "Usuario no logueado";
                }
                ?>
              </span>
            </div>
            <ul class="pro-body">
              <!-- <li><a href="perfil-admin.php" class="dropdown-item"><i class="feather icon-user"></i> Perfil</a></li> -->
              <li><a href="#" class="dropdown-item" data-toggle="modal" data-target="#exampleModal"><i class="feather icon-user"></i>Mi Perfil</a></li>
              <li><a href="#" class="dropdown-item" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="feather icon-log-out"></i> Cerrar sesi&oacute;n</a></li>
            </ul>
          </div>
        </div>

        <!-- Modal Perfil start -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-xl text-bold" id="exampleModalLabel">Mi Perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form>
                  <div class="row">
                    <div class="col-md-6">
                      <label for="usuNombre" class="block font-bold text-xs text-gray-800 py-0 mb-0">Usuario</label>
                      <input type="text" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs text-gray-800 mb-3" id="usuNombre" value="<?php echo htmlspecialchars($perfil['USU_nombre']); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                      <label for="rolNombre" class="block font-bold text-xs text-gray-800 mb-0">Rol</label>
                      <input type="text" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs text-gray-800 mb-3" id="rolNombre" value="<?php echo htmlspecialchars($perfil['ROL_nombre']); ?>" readonly>
                    </div>
                  </div>
                  <div class="mb-0">
                    <label for="areNombre" class="block font-bold text-xs text-gray-800 mb-0">&Aacute;rea</label>
                    <input type="text" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs text-gray-800 mb-3" id="areNombre" value="<?php echo htmlspecialchars($perfil['ARE_nombre']); ?>" readonly>
                  </div>
                  <div class="mb-1">
                    <label for="perNombres" class="block font-bold text-xs text-gray-800 mb-0">Nombres y apellidos</label>
                    <input type="text" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs text-gray-800 mb-3" id="perNombres" value="<?php echo htmlspecialchars($perfil['Persona']); ?>" readonly>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label for="perApellidoPaterno" class="block font-bold text-xs text-gray-800 mb-0">Celular</label>
                      <input type="text" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs text-gray-800 mb-3" id="perApellidoPaterno" value="<?php echo htmlspecialchars($perfil['PER_celular']); ?>" readonly>
                    </div>
                    <div class="col-md-9">
                      <label for="perApellidoMaterno" class="block font-bold text-xs text-gray-800 mb-0">Email</label>
                      <input type="text" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs text-gray-800 mb-3" id="perApellidoMaterno" value="<?php echo htmlspecialchars($perfil['PER_email']); ?>" readonly>
                    </div>
                  </div>
                </form>


              </div>
              <div class="modal-footer">
                <a href="mi-perfil.php" class="btn text-xs btn-info py-2 px-3 rounded-md text-white">Editar perfil</a>
                <button type="button" class="btn  text-xs btn-secondary py-2 px-3 rounded-md" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal perfil end -->

        <!-- Modal Cerrar sesion start -->
        <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-xl text-bold" id="mySmallModalLabel">Mensaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <p class="mb-0 text-gray-800 text-center text-md">¿Desea cerrar sesi&oacute;n?</p>
              </div>
              <div class="modal-footer">
                <a href="logout.php" class="btn btn-primary rounded-md text-white">Cerrar sesi&oacute;n</a>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal Cerrar sesion end -->
      </li>

    </ul>
  </div>
</header>