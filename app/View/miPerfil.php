<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    require_once 'app/Model/UsuarioModel.php';
    // Código PHP para obtener los datos del usuario
    if (isset($_SESSION['codigoUsuario'])) {
      $user_id = $_SESSION['codigoUsuario'];
      $usuario = new UsuarioModel();
      $perfil = $usuario->setearDatosUsuario($user_id);
    } else {
      $perfil = null; // O maneja el caso en que el usuario no está logueado
    }
    ?>
    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Editar perfil</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-user"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Configuraci&oacute;n</a></li>
              <li class="breadcrumb-item"><a href="">Mi Perfil</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Contenedor principal para el formulario y la tabla -->
    <form id="formPerfil" action="mi-perfil.php?action=editar" method="POST" class="card table-card bg-white shadow-md p-6 text-xs flex flex-col mb-2">
      <input type="hidden" id="form-action" name="action" value="editar">
      <div class="card-body">

        <div class="row">
          <div class="col-md-6">
            <h5 class="mb-1 text-lg text-bold">Datos Personales</h5>
            <hr class="mb-2">
            <!-- Inicio de formulario de Datos personales -->
            <!-- Dni del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">DNI</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm border border-gray-200 bg-gray-100 rounded-md p-2 text-md" id="dni" name="dni" maxlength="8" pattern="\d{1,8}" inputmode="numeric" placeholder="DNI" value="<?php echo htmlspecialchars($perfil['PER_dni']); ?>" disabled>
              </div>
            </div>

            <!-- Nombres del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Nombres</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm border border-gray-200 bg-gray-50 rounded-md p-2 text-md" id="nombres" name="nombres" placeholder="Nombres" value="<?php echo htmlspecialchars($perfil['PER_nombres']); ?>" disabled>
              </div>
            </div>

            <!-- Apellido Paterno del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Apellido Paterno</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm border border-gray-200 bg-gray-50 rounded-md p-2 text-md" id="apellidoPaterno" name="apellidoPaterno" placeholder="Apellido paterno" value="<?php echo htmlspecialchars($perfil['PER_apellidoPaterno']); ?>" disabled>
              </div>
            </div>

            <!-- Apellido Materno del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Apellido Materno</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm border border-gray-200 bg-gray-50 rounded-md p-2 text-md" id="apellidoMaterno" name="apellidoMaterno" placeholder="Apellido materno" value="<?php echo htmlspecialchars($perfil['PER_apellidoMaterno']); ?>" disabled>
              </div>
            </div>

            <!-- Celular del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Celular</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm border border-gray-200 bg-gray-50 rounded-md p-2 text-md" id="celular" name="celular" maxlength="9" pattern="\d{1,9}" inputmode="numeric" placeholder="Celular" value="<?php echo htmlspecialchars($perfil['PER_celular']); ?>" disabled>
              </div>
            </div>

            <!-- Email del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm border border-gray-200 bg-gray-50 rounded-md p-2 text-md" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($perfil['PER_email']); ?>" disabled>
              </div>
            </div>

            <!-- Botones del formulario -->
            <div class="flex justify-center space-x-4 mt-5 mb-5">
              <button type="button" id="habilitar" class="bn btn-warning text-xs text-white font-bold py-2 px-3 rounded-md disabled:bg-gray-300"><i class="feather mr-2 icon-save"></i>Habilitar</button>
              <button type="submit" id="editar-datos" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md disabled:bg-gray-300 disabled:cursor-not-allowed" disabled><i class="feather mr-2 icon-edit"></i>Editar</button>
              <button type="button" id="nuevo-registro" class="bn bg-gray-500 text-xs text-white font-bold py-2 px-3 rounded-md disabled:bg-gray-300 disabled:cursor-not-allowed" disabled><i class="feather mr-2 icon-slash"></i>Deshabilitar</button>
            </div>


            <!-- Fin Formulario Datos personales -->
          </div>

          <div class="col-md-6">
            <h5 class="mb-1 text-lg text-bold">Informaci&oacute;n de Usuario</h5>
            <hr class="mb-2">

            <!-- Codigo de usuario -->
            <div class="form-group row hidden">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Codigo de usuario</label>
              <div class="col-sm-9">
                <input type="text" id="codigoUsuario" name="codigoUsuario" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs" value="<?php echo $_SESSION['codigoUsuario']; ?>" readonly>
              </div>
            </div>

            <!-- Nombre de usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Usuario</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm border border-gray-200 bg-gray-100 rounded-md p-2 text-md" id="username" name="username" placeholder="Nombre de usuario" value="<?php echo htmlspecialchars($perfil['USU_nombre']); ?>" disabled>
              </div>
            </div>

            <!-- Rol del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Rol asignado</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm border border-gray-200 bg-gray-100 rounded-md p-2 text-md" id="rol" name="rol" placeholder="Rol" value="<?php echo htmlspecialchars($perfil['ROL_nombre']); ?>" disabled readonly>
              </div>
            </div>

            <!-- Area del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">&Aacute;rea asignada</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm border border-gray-200 bg-gray-100 rounded-md p-2 text-md" id="area" name="area" placeholder="&Aacute;rea" value="<?php echo htmlspecialchars($perfil['ARE_nombre']); ?>" disabled readonly>
              </div>
            </div>


            <!-- Boton cambiar contraseña -->
            <div class="flex justify-center space-x-4 mt-10">
              <button type="button" data-toggle="modal" data-target="#modalCambiarPassword" id="cambiarPasswordModal" class="bn bg-orange-500 hover:bg-orange-600 text-xs text-white font-bold py-2 px-3 rounded-md">
                <i class="feather mr-2 icon-lock"></i>Cambiar contrase&ntilde;a</button>
            </div>
            <!-- Fin Formulario datos de Usuario -->

            <!-- TODO:Modal Cambio de contraseña -->
            <div class="modal fade" id="modalCambiarPassword" tabindex="-1" role="dialog" aria-labelledby="modalBuscarIncidenciaLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title text-xl font-bold" id="modalBuscarIncidenciaLabel">Cambio de clave de usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form id="formCambiarPassword" action="mi-perfil.php?action=cambiar" method="POST" class="card table-card bg-white shadow-md p-6 text-xs flex flex-col mb-2">
                      <input type="hidden" id="form-action-cambiar" name="action" value="cambiar">
                      <div>
                        <p class="mb-0 text-gray-800 text-lg">Datos del usuario</p>
                        <hr class="border-t-2 border-gray-100 mb-2"> <!-- Línea separadora -->

                        <!-- Codigo de usuario -->
                        <div class="form-group row hidden">
                          <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-xs">Codigo de usuario</label>
                          <div class="col-sm-9">
                            <input type="text" id="codigoUsuarioModal" name="codigoUsuarioModal" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoUsuario']; ?>" readonly>
                          </div>
                        </div>

                        <!-- Nombre de usuario -->
                        <div class="form-group row">
                          <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Usuario</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm border border-gray-200 bg-gray-100 rounded-md p-2 text-md" id="usernameModal" name="usernameModal" placeholder="Nombre de usuario" value="<?php echo htmlspecialchars($perfil['USU_nombre']); ?>" disabled readonly>
                          </div>
                        </div>

                        <!-- Nombre de usuario -->
                        <div class="form-group row">
                          <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Nombre</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm border border-green-500 bg-gray-100 rounded-md p-2 text-md" id="usernameModel" name="usernameModel" placeholder="Nombre de usuario" value="<?php echo htmlspecialchars($perfil['Persona']); ?>" disabled readonly>
                          </div>
                        </div>
                      </div>

                      <div>
                        <p class="mb-0 text-gray-800 text-lg">Datos de acceso</p>
                        <hr class="border-t-2 border-gray-100 mb-3"> <!-- Línea separadora -->

                        <!-- Contraseña actual -->
                        <div class="form-group row">
                          <label for="colFormLabelSm" class="col-sm-5 col-form-label col-form-label-sm">Contrase&ntilde;a actual</label>
                          <div class="col-sm-7 position-relative">
                            <input type="password" class="form-control form-control-sm border border-green-500 bg-white rounded-md p-2 text-md pr-10" id="passwordActual" name="passwordActual" placeholder="Ingrese contrase&ntilde;a actual">
                            <!-- Icono para mostrar/ocultar contraseña -->
                            <span id="togglePasswordActual" class="position-absolute cursor-pointer" style="right: 25px; top: 50%; transform: translateY(-50%);">
                              <i class="feather icon-eye text-gray-400"></i>
                            </span>
                          </div>
                        </div>

                        <!-- Contraseña nueva -->
                        <div class="form-group row">
                          <label for="colFormLabelSm" class="col-sm-5 col-form-label col-form-label-sm">Nueva contrase&ntilde;a</label>
                          <div class="col-sm-7 position-relative">
                            <input type="password" class="form-control form-control-sm border border-green-500 bg-white rounded-md p-2 text-md pr-10" id="passwordNuevo" name="passwordNuevo" placeholder="Ingrese nueva contrase&ntilde;a">
                            <!-- Icono para mostrar/ocultar contraseña -->
                            <span id="togglePasswordNuevo" class="position-absolute cursor-pointer" style="right: 25px; top: 50%; transform: translateY(-50%);">
                              <i class="feather icon-eye text-gray-400"></i>
                            </span>
                          </div>
                        </div>

                        <!-- Confirmar contraseña -->
                        <div class="form-group row">
                          <label for="colFormLabelSm" class="col-sm-5 col-form-label col-form-label-sm">Confirmar contrase&ntilde;a</label>

                          <div class="col-sm-7 position-relative">
                            <input type="password" class="form-control form-control-sm border border-green-500 bg-white rounded-md p-2 text-md pr-10" id="passwordConfirm" name="passwordConfirm" placeholder="Confirmar contrase&ntilde;a">

                            <!-- Icono para mostrar/ocultar contraseña -->
                            <span id="togglePasswordConfirm" class="position-absolute cursor-pointer" style="right: 25px; top: 50%; transform: translateY(-50%);">
                              <i class="feather icon-eye text-gray-400"></i>
                            </span>

                            <!-- Mensaje de confirmación debajo del input -->
                            <div id="mensajeConfirmacion" class="position-absolute" style="display: none; color: green; font-size: 14px; top: 100%; left: 15px; transform: translateY(10px);">
                            </div>
                          </div>
                        </div>

                      </div>
                    </form>
                  </div>
                  <div class="modal-footer justify-center">
                    <button type="submit" id="cambiarPasswordPerfil" class="bn bg-gray-500 text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-lock"></i>Cambiar contrase&ntilde;a</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Fin Modal Cambio de contraseña -->
          </div>
        </div>
    </form>
  </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>