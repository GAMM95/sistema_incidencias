<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">

    <!-- Inicio de breadcrumb -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Mantenimiento de incidencias</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-edit"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Registros</a></li>
              <li class="breadcrumb-item"><a href="">Mantenimiento</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de breadcrumb -->

    <!-- Formulario de registro de asignacion de incidencias -->
    <form id="formMantenimiento" action="registro-mantenimiento.php?action=habilitar" method="POST" class="card table-card bg-white shadow-md p-4 w-full text-xs mb-3 hidden">
      <input type="hidden" id="form-action" name="action" value="habilitar">

      <div class="flex flex-wrap -mx-2 justify-center">
        <!-- Numero de asignacion -->
        <div class="flex justify-center items-center">
          <div class="text-center">
            <label for="numeroAsignacion" class="block font-bold mb-1 mr-3 text-lime-500">N&uacute;mero de Asignaci&oacute;n:</label>
            <input type="text" id="numeroAsignacion" name="numeroAsignacion" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
          </div>
        </div>
        <!-- fin numero de asignacion -->

        <!-- Fecha de mantenimiento -->
        <div class="w-full md:w-1/5 px-2 mb-2 ">
          <label for="fecha" class="block font-bold mb-1">Fecha de Mantenimiento:</label>
          <input type="date" id="fecha" name="fecha" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>
        <!-- Fin de fecha de mantenimiento -->

        <!-- Hora de mantenimiento -->
        <div class="w-full md:w-1/5 px-2 mb-2">
          <label for="hora" class="block font-bold mb-1">Hora Mantenimiento:</label>
          <?php
          // Establecer la zona horaria deseada
          date_default_timezone_set('America/Lima');
          $fecha_actual = date('Y-m-d');
          // Obtener la hora actual en formato de 24 horas (HH:MM)
          $hora_actual = date('H:i:s');
          ?>
          <input type="text" id="hora" name="hora" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $hora_actual; ?>" readonly>
        </div>
        <!-- Fin de hora de mantenimiento -->

        <!-- Usuario de inicio de sesion -->
        <div class="w-full md:w-1/6 px-2 mb-2 ">
          <label for="usuarioDisplay" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="usuarioDisplay" name="usuarioDisplay" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['usuario']; ?>" readonly>
        </div>
        <div class="w-full md:w-1/6 px-2 mb-2 ">
          <label for="usuario" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="usuario" name="usuario" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoUsuario']; ?>" readonly>
        </div>
        <!-- Fin de usuario de inicio de sesion -->
      </div>
    </form>
    <!-- Fin de formulario -->

    <!-- Segundo apartado -->
    <div class="w-full">
      <!-- Titulo de tabla de asignaciones -->
      <div class="flex justify-between items-center mb-2">
        <h1 class="text-xl text-gray-400">Incidencias asignadas para mantenimiento</h1>
      </div>
      <!-- Fin de titulo -->

      <!-- Tabla de incidencias asignadas -->
      <div class="relative max-h-[500px] overflow-x-hidden shadow-md sm:rounded-lg">
        <table id="tablaIncidenciasMantenimiento" class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
          <!-- Encabezado de la tabla -->
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-red-300">
            <tr>
              <th scope="col" class="px-6 py-2 hidden">Asignaci&oacute;n</th>
              <th scope="col" class="px-6 py-2 hidden">Recepci&oacute;n</th>
              <th scope="col" class="px-6 py-2 text-center">Incidencia</th>
              <th scope="col" class="px-6 py-2 text-center">Fecha asignaci&oacute;n</th>
              <th scope="col" class="px-6 py-2 text-center">&Aacute;rea solicitante</th>
              <th scope="col" class="px-6 py-2 text-center">Asunto</th>
              <th scope="col" class="px-6 py-2 text-center">Equipo</th>
              <th scope="col" class="px-6 py-2 text-center">Nombre del bien</th>
              <th scope="col" class="px-6 py-2 text-center">Estado Actual</th>
            </tr>
          </thead>
          <!-- Fin de encabezado -->

          <!-- Cuerpo de la tabla -->
          <tbody>
            <?php if (!empty($resultadoAsignaciones)) : ?>
              <?php foreach ($resultadoAsignaciones as $asignaciones) : ?>
                <?php
                $numeroAsignacion = htmlspecialchars($asignaciones['ASI_codigo']);
                $estado = htmlspecialchars($asignaciones['EST_descripcion']);
                $Finalizado = ($estado === 'RESUELTO'); // Comparación forzada como entero
                ?>
                <tr class='second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b' data-id="<?= $numeroAsignacion; ?>">
                  <th scope='row' class='px-6 py-3 font-medium text-gray-900 whitespace-nowrap hidden'><?= $numeroAsignacion; ?></th>
                  <th scope='row' class='px-6 py-3 font-medium text-gray-900 whitespace-nowrap hidden'><?= $asignaciones['REC_numero']; ?></th>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['INC_numero_formato']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['fechaAsignacionFormateada']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['ARE_nombre']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['INC_asunto']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['INC_codigoPatrimonial']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['BIE_nombre']; ?></td>
                  <td class="px-6 py-2 text-center">
                    <div class="custom-control custom-switch cursor-pointer">
                      <input type="checkbox" class="custom-control-input switch-mantenimiento" id="customswitch<?= $numeroAsignacion; ?>" data-id="<?= $numeroAsignacion; ?>" <?= $Finalizado ? 'checked' : ''; ?>>
                      <!-- Mostramos el estado exacto desde la base de datos -->
                      <label class="custom-control-label" for="customswitch<?= $numeroAsignacion; ?>"><?= $Finalizado ? 'Finalizado' : 'En proceso'; ?></label>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="10" class="text-center py-3">No se han asignado incidencias.</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <!-- Fin del cuerpo de la tabla -->
        </table>
      </div>
    </div>
    <!-- Fin de la tabla -->
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>
<link href="dist/assets/css/plugins/tailwind.min.css" rel="stylesheet">