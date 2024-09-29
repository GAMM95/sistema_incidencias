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
                <input type="text" class="form-control form-control-sm" id="dni" name="dni" maxlength="8" pattern="\d{1,8}" inputmode="numeric" placeholder="DNI" value="<?php echo htmlspecialchars($perfil['PER_dni']); ?>" disabled>
              </div>
            </div>

            <!-- Nombres del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Nombres</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="nombres" name="nombres" placeholder="Nombres" value="<?php echo htmlspecialchars($perfil['PER_nombres']); ?>" disabled>
              </div>
            </div>

            <!-- Apellido Paterno del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Apellido Paterno</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="apellidoPaterno" name="apellidoPaterno" placeholder="Apellido paterno" value="<?php echo htmlspecialchars($perfil['PER_apellidoPaterno']); ?>" disabled>
              </div>
            </div>

            <!-- Apellido Materno del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Apellido Materno</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="apellidoMaterno" name="apellidoMaterno" placeholder="Apellido materno" value="<?php echo htmlspecialchars($perfil['PER_apellidoMaterno']); ?>" disabled>
              </div>
            </div>

            <!-- Celular del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Celular</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="celular" name="celular" maxlength="9" pattern="\d{1,9}" inputmode="numeric"  placeholder="Celular" value="<?php echo htmlspecialchars($perfil['PER_celular']); ?>" disabled>
              </div>
            </div>

            <!-- Email del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($perfil['PER_email']); ?>" disabled>
              </div>
            </div>
            <!-- Fin Formulario Datos personales -->
          </div>

          <div class="col-md-6">
            <h5 class="mb-1 text-lg text-bold">Informaci&oacute;n de Usuario</h5>
            <hr class="mb-2">

            <!-- Inicio de formulario de datos de Usuario -->
            <!-- Codigo de usuario -->
            <div class="form-group row hidden">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Codigo de usuario</label>
              <div class="col-sm-9">
                <input type="text" id="codigoUsuario" name="codigoUsuario" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoUsuario']; ?>" readonly>
              </div>
            </div>

            <!-- Nombre de usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Usuario</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="username" name="username" placeholder="Nombre de usuario" value="<?php echo htmlspecialchars($perfil['USU_nombre']); ?>" disabled>
              </div>
            </div>

            <!-- Contraseña del usuario -->
            <div class="form-group row">
              <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Contrase&ntilde;a</label>
              <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="password" name="password" placeholder="Contrase&ntilde;a" value="<?php echo htmlspecialchars($perfil['USU_password']); ?>" disabled>
              </div>
            </div>

            <!-- BOTONES DEL FORMULARIO -->
            <div class="flex justify-center space-x-4 mt-10">
              <button type="button" id="habilitar" class="bn btn-warning text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-circle"></i>Habilitar</button>
              <button type="submit" id="editar-datos" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md" disabled><i class="feather mr-2 icon-edit"></i>Editar</button>
              <button type="button" id="nuevo-registro" class="bn btn-danger text-xs text-white font-bold py-2 px-3 rounded-md" disabled> <i class="feather mr-2 icon-slash"></i>Deshabilitar</button>
            </div>
            <!-- Fin Formulario datos de Usuario -->
          </div>
        </div>

        <!-- Recopilacion de valores de cada input -->
        <script>
          document.getElementById('dni').value = '<?php echo htmlspecialchars($perfil['PER_dni']); ?>';
          document.getElementById('nombres').value = '<?php echo htmlspecialchars($perfil['PER_nombres']); ?>';
          document.getElementById('apellidoPaterno').value = '<?php echo htmlspecialchars($perfil['PER_apellidoPaterno']); ?>';
          document.getElementById('apellidoMaterno').value = '<?php echo htmlspecialchars($perfil['PER_apellidoMaterno']); ?>';
          document.getElementById('celular').value = '<?php echo htmlspecialchars($perfil['PER_celular']); ?>';
          document.getElementById('email').value = '<?php echo htmlspecialchars($perfil['PER_email']); ?>';
          document.getElementById('username').value = '<?php echo htmlspecialchars($perfil['USU_nombre']); ?>';
          document.getElementById('password').value = '<?php echo htmlspecialchars($perfil['USU_password']); ?>';
        </script>
      </div>
    </form>
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>