<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    global $usuarioRegistrado;
    ?>

    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Registro de usuarios</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-server"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Mantenedor</a></li>
              <li class="breadcrumb-item"><a href="">Usuarios</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Inicio de Formulario -->
    <form id="formUsuario" action="modulo-usuario.php?action=registrar" method="POST" class="card table-card  bg-white shadow-md p-6  text-xs  flex flex-col mb-2">
      <input type="hidden" id="form-action" name="action" value="registrar">
      <!-- Subtitulo
      <h3 class="text-2xl font-plain mb-4 text-xs text-gray-400">Informaci&oacute;n del usuario</h3> -->

      <!-- Inicio de Card de formulario -->
      <div class="card-body">
        <!-- CAMPO ESCONDIDO -->
        <div class="flex justify-center -mx-2 ">
          <!-- CODIGO DE USUARIO -->
          <div class="w-full sm:w-1/4 px-2 mb-2 hidden">
            <div class="flex items-center">
              <label for="CodUsuario" class="block font-bold mb-1 mr-3 text-lime-500">C&oacute;digo de Usuario:</label>
              <input type="text" id="CodUsuario" name="CodUsuario" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center">
            </div>
          </div>
        </div>

        <!-- Primera fila -->
        <div class="mb-4 flex items-center gap-4">
          <!-- Selecciona de persona -->
          <div class="flex-grow w-1/4">
            <label for="persona" class="block text-gray-700 font-bold mb-2">Trabajador:</label>
            <select id="persona" name="persona" class="border p-2 w-full text-xs cursor-pointer rounded-md"></select>
            <input type="hidden" id="codigoPersona" name="codigoPersona">
          </div>

          <!-- Boton agregar persona -->
          <a href="modulo-persona.php" class="bn btn-warning text-xs text-white font-bold py-2 px-3 mt-4 rounded-md" disabled> <i class="feather icon-user-plus"></i></a>

          <!-- Seleccion de area -->
          <div class="flex-grow w-1/2">
            <label for="area" class="block text-gray-700 font-bold mb-2">&Aacute;rea:</label>
            <select id="area" name="area" class="border p-2 w-full text-xs cursor-pointer rounded-md"></select>
          </div>

          <!-- Seleccion de rol -->
          <div class="flex-grow w-1/4">
            <label for="rol" class="block text-gray-700 font-bold mb-2">Rol:</label>
            <select id="rol" name="rol" class="border p-2 w-full text-xs cursor-pointer rounded-md"></select>
          </div>
        </div>

        <!-- Input Usuario y Contraseña -->
        <div class="flex flex-wrap -mx-2">
          <!-- NOMBRE DE USUARIO -->
          <div class="w-full sm:w-1/5 px-2 mb-2">
            <label for="username" class="block mb-1 font-bold text-xs">Usuario:</label>
            <input type="text" id="username" name="username" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese username" oninput="uppercaseInput(this)">

            <script>
              function uppercaseInput(element) {
                element.value = element.value.toUpperCase();
              }
            </script>
          </div>

          <!-- CONTRASEÑA -->
          <div class="w-full sm:w-1/5 px-2 mb-2">
            <label for="password" class="block mb-1 font-bold text-xs">Contrase&ntilde;a:</label>
            <input type="password" id="password" name="password" class="border p-2 w-full text-xs rounded-md" placeholder="Ingrese contraseña">
          </div>

          <!-- Botones del formulario -->
          <div class="flex justify-center items-center space-x-4 mt-3 ml-20">
            <button type="submit" id="guardar-usuario" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md disabled:cursor-not-allowed"><i class="feather mr-2 icon-save"></i>Registrar</button>
            <button type="button" id="editar-usuario" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md disabled:cursor-not-allowed" disabled><i class="feather mr-2 icon-edit"></i>Actualizar</button>
            <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-plus-square"></i>Limpiar</button>
          </div>
          <!-- Fin botones -->
        </div>

        <!-- Funciones -->
        <script>
          document.getElementById('CodUsuario').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['USU_codigo'] : ''; ?>';
          document.getElementById('persona').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['PER_codigo'] : ''; ?>';
          document.getElementById('area').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['ARE_codigo'] : ''; ?>';
          document.getElementById('rol').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['ROL_codigo'] : ''; ?>';
          document.getElementById('username').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['USU_nombre'] : ''; ?>';
          document.getElementById('password').value = '<?php echo $usuarioRegistrado ? $usuarioRegistrado['USU_password'] : ''; ?>';
        </script>
      </div>
      <!-- Fin de Card de formulario -->
    </form>
    <!-- Fin de formulario -->

    <!-- Tabla de usuarios -->
    <div class="w-full">
      <!-- Busqueda de termino -->
      <div class="flex justify-between items-center mt-3">
        <input type="text" id="termino" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-lime-300 text-xs" placeholder="Buscar usuario..." oninput="filtrarTablaUsuario()" />
      </div>
      <!-- Fin de busqueda -->

      <!-- TABLA DE USUARIOS -->
      <div class="relative max-h-[800px] mt-2 overflow-x-hidden shadow-md sm:rounded-lg bg-white">
        <table id="tablaListarUsuarios" class="w-full text-xs text-left rtl:text-right text-gray-500">
          <!-- Encabezado -->
          <thead class="sticky top-0 text-xs text-gray-70 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-6 py-2 text-center hidden">N&deg;</th>
              <th scope="col" class="px-6 py-2 text-center">DNI</th>
              <th scope="col" class="px-6 py-2 text-center">Trabajador</th>
              <th scope="col" class="px-6 py-2 text-center">&Aacute;rea</th>
              <th scope="col" class="px-6 py-2 text-center">Usuario</th>
              <th scope="col" class="px-6 py-2 text-center hidden">Contrase&ntilde;a</th>
              <th scope="col" class="px-6 py-2 text-center">Rol</th>
              <th scope="col" class="px-6 py-2 text-center">Estado</th>
            </tr>
          </thead>
          <!-- Fin de encabezado -->

          <!-- Inicio de cuerpo de tabla -->
          <tbody>
            <?php if (!empty($resultado)) : ?>
              <?php foreach ($resultado as $usuario) : ?>
                <?php
                $estado = htmlspecialchars($usuario['EST_descripcion']);
                $isActive = ($estado === 'ACTIVO');
                $areaEstado = htmlspecialchars($usuario['EST_codigo']);
                $codigoUsuario = htmlspecialchars($usuario['USU_codigo']);
                // Aplicar clase de texto rojo si el ARE_estado es 2
                $areaInactiva = ($areaEstado == 2) ? 'text-red-600' : 'text-gray-900';
                ?>
                <tr class="hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b" data-id="<?= $usuario['USU_codigo']; ?>">
                  <th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap hidden"><?= htmlspecialchars($usuario['USU_codigo']); ?></th>
                  <td class="px-6 py-2 text-center"><?= htmlspecialchars($usuario['PER_dni']); ?></td>
                  <td class="px-6 py-2 text-center"><?= htmlspecialchars($usuario['persona']); ?></td>
                  <td class="px-6 py-2 text-center <?= $areaInactiva; ?>"><?= htmlspecialchars($usuario['ARE_nombre']); ?></td>
                  <td class="px-6 py-2 text-center"><?= htmlspecialchars($usuario['USU_nombre']); ?></td>
                  <td class="px-6 py-2 text-center hidden"><?= htmlspecialchars($usuario['USU_password']); ?></td>
                  <td class="px-6 py-2 text-center"><?= htmlspecialchars($usuario['ROL_nombre']); ?></td>
                  <td class="px-6 py-2 text-center">
                    <div class="custom-control custom-switch cursor-pointer">
                      <input type="checkbox" class="custom-control-input switch-usuario" id="customswitch<?= $codigoUsuario; ?>" data-id="<?= $codigoUsuario; ?>" <?= $isActive ? 'checked' : ''; ?>>
                      <label class="custom-control-label" for="customswitch<?= $codigoUsuario; ?>"><?= $isActive ? 'Activo' : 'Inactivo'; ?></label>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="8" class="text-center py-3">No se han registrado usuarios</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <!-- Fin de cuerpo de tabla -->
        </table>
      </div>
    </div>
    <!-- Fin de la Tabla de usuarios -->
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>