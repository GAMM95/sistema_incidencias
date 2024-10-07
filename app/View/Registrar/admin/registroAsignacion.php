<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    global $asignacionRegistrada;
    ?>

    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Asignaci&oacute;n de personal</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-edit"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Registros</a></li>
              <li class="breadcrumb-item"><a href="">Recepci&oacute;n</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Titulo y paginacion de tabla de recepciones -->
    <div class="flex justify-between items-center mb-2">
      <h1 class="text-xl text-gray-400">Incidencias recepcionadas</h1>
      <div id="paginadorRecepciones" class="flex justify-end items-center mt-1">
        <?php if ($totalPagesRecepciones > 1) : ?>
          <?php if ($pageRecepciones > 1) : ?>
            <a href="#" class="px-2 py-1 bg-gray-400 text-gray-200 hover:bg-gray-600 rounded-l-md" onclick="changePageTablaRecepciones(<?php echo $pageRecepciones - 1; ?>)"><i class="feather mr-2 icon-chevrons-left"></i> Anterior</a>
          <?php endif; ?>
          <span class="px-2 py-1 bg-gray-400 text-gray-200"><?php echo $pageRecepciones; ?> de <?php echo $totalPagesRecepciones; ?></span>
          <?php if ($pageRecepciones < $totalPagesRecepciones) : ?>
            <a href="#" class="px-2 py-1 bg-gray-400 text-gray-200 hover:bg-gray-600 rounded-r-md" onclick="changePageTablaRecepciones(<?php echo $pageRecepciones + 1; ?>)"> Siguiente <i class="feather ml-2 icon-chevrons-right"></i></a>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
    <!-- Fin de titulo y paginacion -->

    <!-- Segundo apartado -->
    <div class="w-full mb-3">
      <!-- Tabla de incidencias recepcionadas -->
      <div class="relative max-h-[500px] overflow-x-hidden shadow-md sm:rounded-lg">
        <table id="tablaIncidenciasRecepcionadas" class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
          <!-- Encabezado de la tabla -->
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-orange-300">
            <tr>
              <th scope="col" class="px-6 py-2 hidden">Recepci&oacute;n</th>
              <th scope="col" class="px-6 py-2 text-center">Incidencia</th>
              <th scope="col" class="px-6 py-2 text-center">Fecha recepci&oacute;n</th>
              <th scope="col" class="px-6 py-2 text-center">&Aacute;rea</th>
              <th scope="col" class="px-6 py-2 text-center">C&oacute;d. Patrimonial</th>
              <th scope="col" class="px-6 py-2 text-center">Categor&iacute;a</th>
              <th scope="col" class="px-6 py-2 text-center">Prioridad</th>
              <th scope="col" class="px-6 py-2 text-center">Impacto</th>
              <th scope="col" class="px-6 py-2 text-center">Usuario receptor</th>
              <th scope="col" class="px-6 py-2 text-center">Acci&oacute;n</th>
            </tr>
          </thead>
          <!-- Fin de encabezado -->

          <!-- Cuerpo de la tabla -->
          <tbody>
            <?php if (!empty($resultadoRecepciones)) : ?>
              <?php foreach ($resultadoRecepciones as $recepcion) : ?>
                <tr class='second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b' data-id="<?= $recepcion['REC_numero']; ?>">
                  <th scope='row' class='px-6 py-3 font-medium text-gray-900 whitespace-nowrap hidden'><?= $recepcion['REC_numero']; ?></th>
                  <td class='px-6 py-2 text-center'><?= $recepcion['INC_numero_formato']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $recepcion['fechaRecepcionFormateada']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $recepcion['ARE_nombre']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $recepcion['INC_codigoPatrimonial']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $recepcion['CAT_nombre']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $recepcion['PRI_nombre']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $recepcion['IMP_descripcion']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $recepcion['UsuarioRecepcion']; ?></td>
                  <td class="px-6 py-2 text-center flex space-x-2">
                    <button type="button" class="eliminar-recepcion bn btn-danger text-xs text-white font-bold py-2 px-3 rounded-md flex items-center justify-center">
                      <i class="feather icon-trash-2"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="10" class="text-center py-3">No se han recepcionado incidencias.</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <!-- Fin del cuerpo de la tabla -->
        </table>
      </div>
    </div>
    <!-- Fin de la tabla -->

    <!-- Formulario de registro de asignacion de incidencias -->
    <form id="formAsignacion" action="registro-asignacion.php?action=registrar" method="POST" class="card table-card bg-white shadow-md p-4 w-full text-xs mb-3">
      <input type="hidden" id="form-action" name="action" value="registrar">

      <div class="flex flex-wrap -mx-2 justify-center">
        <!-- Numero de recepcion -->
        <div class="flex justify-center items-center mr-5 ml-5">
          <div class="text-center">
            <label for="num_recepcion" class="block font-bold mb-1 mr-3 text-[#32cfad]">C&oacute;digo de recepci&oacute;n:</label>
            <input type="text" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" id="num_recepcion" name="num_recepcion" readonly required>
          </div>
        </div>

        <!-- incidencia seleccionada -->
        <div class="flex justify-center items-center mr-5 ml-5">
          <div class="text-center">
            <label for="incidenciaSeleccionada" class="block font-bold mb-1 text-[#32cfad]">Incidencia seleccionada:</label>
            <input type="text" class="border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center w-full" id="incidenciaSeleccionada" name="incidenciaSeleccionada" readonly required>
          </div>
        </div>

        <!-- Numero de asignacion -->
        <div class="flex justify-center items-center">
          <div class="text-center">
            <label for="num_asignacion" class="block font-bold mb-1 mr-3 text-lime-500">Número de Asignacion:</label>
            <input type="text" id="num_asignacion" name="num_asignacion" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
          </div>
        </div>

        <!-- FECHA DE ASIGNACION -->
        <div class="w-full md:w-1/5 px-2 mb-2">
          <label for="fecha" class="block font-bold mb-1">Fecha de Asignaci&oacute;n:</label>
          <input type="date" id="fecha" name="fecha" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>

        <!-- HORA DE ASIGNACION -->
        <div class="w-full md:w-1/5 px-2 mb-2">
          <label for="hora" class="block font-bold mb-1">Hora Asignaci&oacute;n:</label>
          <?php
          // Establecer la zona horaria deseada
          date_default_timezone_set('America/Lima');
          $fecha_actual = date('Y-m-d');
          // Obtener la hora actual en formato de 24 horas (HH:MM)
          $hora_actual = date('H:i:s');
          ?>
          <input type="text" id="hora" name="hora" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $hora_actual; ?>" readonly>
        </div>

        <!-- USUARIO QUE REGISTRA LA ASIGNACION -->
        <div class="w-full md:w-1/5 px-2 mb-2">
          <label for="usuarioDisplay" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="usuarioDisplay" name="usuarioDisplay" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['usuario']; ?>" readonly>
        </div>
        <div class="w-full md:w-1/5 px-2 mb-2 hidden">
          <label for="usuario" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="usuario" name="usuario" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoUsuario']; ?>">
        </div>

        <!-- SELECT USUARIO -->
        <div class="flex flex-wrap -mx-2 mr-4">
          <div class="w-full px-2">
            <label for="usuarioAsignado" class="block font-bold mb-1">Usuario asignado:</label>
            <select id="usuarioAsignado" name="usuarioAsignado" class="border p-2 w-full text-xs cursor-pointer rounded-md">
            </select>
            <input type="hidden" id="codigoUsuario" name="codigoUsuario">
          </div>
        </div>

        <!-- RECOPILACION DE VALORES DE CADA INPUT Y COMBOBOX -->
        <script>
          // document.getElementById('incidencia').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['INC_numero'] : ''; ?>';
          // document.getElementById('num_recepcion').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['REC_numero'] : ''; ?>';
          // document.getElementById('hora').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['REC_hora'] : $hora_actual; ?>';
          // document.getElementById('fecha').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['REC_fecha'] : $fecha_actual; ?>';
          // document.getElementById('prioridad').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['PRI_codigo'] : ''; ?>';
          // document.getElementById('impacto').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['IMP_codigo'] : ''; ?>';
        </script>

        <!-- Botones de formulario -->
        <div class="flex justify-center items-center space-x-4 mt-3 ml-5">
          <button type="submit" id="guardar-asignacion" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md disabled:cursor-not-allowed"><i class="feather mr-2 icon-save"></i>Asignar</button>
          <button type="button" id="editar-asignacion" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md disabled:cursor-not-allowed" disabled><i class="feather mr-2 icon-edit"></i>Actualizar</button>
          <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-plus-square"></i>Limpiar</button>
        </div>
        <!-- Fin de botones -->
      </div>
    </form>
    <!-- Fin de formulario -->

    <!-- Segundo apartado -->
    <div class="w-full">
      <!-- Titulo y paginacion de tabla de asignaciones -->
      <div class="flex justify-between items-center mb-2">
        <h1 class="text-xl text-gray-400">Incidencias asignadas</h1>
        <div id="paginadorRecepciones" class="flex justify-end items-center mt-1">
          <?php if ($totalPagesAsignaciones > 1) : ?>
            <?php if ($pageAsignaciones > 1) : ?>
              <a href="#" class="px-2 py-1 bg-gray-400 text-gray-200 hover:bg-gray-600 rounded-l-md" onclick="changePageTablaRecepciones(<?php echo $pageAsignaciones - 1; ?>)"><i class="feather mr-2 icon-chevrons-left"></i> Anterior</a>
            <?php endif; ?>
            <span class="px-2 py-1 bg-gray-400 text-gray-200"><?php echo $pageAsignaciones; ?> de <?php echo $totalPagesAsignaciones; ?></span>
            <?php if ($pageAsignaciones < $totalPagesAsignaciones) : ?>
              <a href="#" class="px-2 py-1 bg-gray-400 text-gray-200 hover:bg-gray-600 rounded-r-md" onclick="changePageTablaRecepciones(<?php echo $pageAsignaciones + 1; ?>)"> Siguiente <i class="feather ml-2 icon-chevrons-right"></i></a>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
      <!-- Fin de titulo y paginacion -->

      <!-- Tabla de incidencias recepcionadas -->
      <div class="relative max-h-[500px] overflow-x-hidden shadow-md sm:rounded-lg">
        <table id="tablaIncidenciasRecepcionadas" class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
          <!-- Encabezado de la tabla -->
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-blue-300">
            <tr>
              <th scope="col" class="px-6 py-2 ">Asignaci&oacute;n</th>
              <th scope="col" class="px-6 py-2 ">Recepci&oacute;n</th>
              <th scope="col" class="px-6 py-2 text-center">Incidencia</th>
              <th scope="col" class="px-6 py-2 text-center">Fecha asignaci&oacute;n</th>
              <th scope="col" class="px-6 py-2 text-center">&Aacute;rea solicitante</th>
              <th scope="col" class="px-6 py-2 text-center">Asunto</th>
              <th scope="col" class="px-6 py-2 text-center">Equipo</th>

              <th scope="col" class="px-6 py-2 text-center">Usuario Asignado</th>
              <th scope="col" class="px-6 py-2 text-center">Estado</th>
            </tr>
          </thead>
          <!-- Fin de encabezado -->

          <!-- Cuerpo de la tabla -->
          <tbody>
            <?php if (!empty($resultadoAsignaciones)) : ?>
              <?php foreach ($resultadoAsignaciones as $asignaciones) : ?>
                <?php
                $estado = htmlspecialchars($asignaciones['EST_descripcion']);
                $isFinalizado = ($estado === 'FINALIZADO');
                ?>
                <tr class='second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b' data-id="<?= $asignaciones['ASI_codigo']; ?>">
                  <th scope='row' class='px-6 py-3 font-medium text-gray-900 whitespace-nowrap'><?= $asignaciones['ASI_codigo']; ?></th>
                  <th scope='row' class='px-6 py-3 font-medium text-gray-900 whitespace-nowrap'><?= $asignaciones['REC_numero']; ?></th>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['INC_numero_formato']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['fechaAsignacionFormateada']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['ARE_nombre']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['INC_asunto']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['INC_codigoPatrimonial']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['usuarioAsignado']; ?></td>

                  <td class="px-6 py-2 text-center">
                    <div class="custom-control custom-switch cursor-pointer">
                      <!-- Activamos el switch si el estado es 'FINALIZADO' -->
                      <input type="checkbox" class="custom-control-input switch-asignacion" id="customswitch<?= $asignaciones['ASI_codigo']; ?>" data-id="<?= $asignaciones['ASI_codigo']; ?>" <?= $isFinalizado ? 'checked' : ''; ?>>
                      <!-- Mostramos el estado correspondiente -->
                      <label class="custom-control-label" for="customswitch<?= $asignaciones['ASI_codigo']; ?>"><?= $isFinalizado ? 'FINALIZADO' : 'EN ESPERA'; ?></label>
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