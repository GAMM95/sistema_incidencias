<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <?php
    global $recepcionRegistrada;
    ?>

    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Recepci&oacute;n de incidencias</h1>
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

    <!-- Titulo de incidencias y paginador -->
    <div id="noIncidencias" class="flex justify-between items-center mb-2">
      <h1 class="text-xl text-gray-400">Nuevas incidencias</h1>
      <div id="paginadorNuevasIncidencias" class="flex justify-end items-center mt-1">
        <?php if ($totalPages > 1) : // Mostrar el contenedor solo si hay más de una página
        ?>
          <div class="flex justify-end items-center mt-1">
            <?php if ($page > 1) : ?>
              <a href="#" class="px-2 py-1 bg-gray-400 text-gray-200 hover:bg-gray-600 rounded-l-md" onclick="changePageTablaSinRecepcionar(<?php echo $page - 1; ?>)"><i class="feather mr-2 icon-chevrons-left"></i> Anterior</a>
            <?php endif; ?>
            <span class="px-2 py-1 bg-gray-400 text-gray-200"><?php echo $page; ?> de <?php echo $totalPages; ?></span>
            <?php if ($page < $totalPages) : ?>
              <a href="#" class="px-2 py-1 bg-gray-400 text-gray-200 hover:bg-gray-600 rounded-r-md" onclick="changePageTablaSinRecepcionar(<?php echo $page + 1; ?>)"> Siguiente <i class="feather ml-2 icon-chevrons-right"></i></a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <!-- Fin de titulo y paginador -->

    <!-- Tabla de incidencias sin recepcionar -->
    <input type="hidden" id="incidenciaCount" value="<?php echo count($resultadoIncidencias); ?>">
    <div class="mb-4">
      <div id="tablaContainer" class="relative max-h-[300px] overflow-x-hidden shadow-md sm:rounded-lg">
        <table id="tablaIncidenciasSinRecepcionar" class="w-full text-xs text-left rtl:text-right text-gray-500 bg-white">
          <!-- Encabezado de la tabla -->
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-6 py-2 hidden">N&deg;</th>
              <th scope="col" class="px-6 py-2 text-center">Incidencia</th>
              <th scope="col" class="px-6 py-2 text-center">Fecha incidencia</th>
              <th scope="col" class="px-6 py-2 text-center">&Aacute;rea</th>
              <th scope="col" class="px-6 py-2 text-center">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-6 py-2 text-center">Nombre del bien</th>
              <th scope="col" class="px-6 py-2 text-center">Asunto</th>
              <th scope="col" class="px-6 py-2 text-center">Documento</th>
              <th scope="col" class="px-6 py-2 text-center">Usuario</th>
            </tr>
          </thead>
          <!-- Fin de encabezado -->

          <!-- Cuerpo de la tabla -->
          <tbody>
            <?php foreach ($resultadoIncidencias as $incidencia) : ?>
              <tr class=' hover:bg-green-100 hover:scale-[101%] transition-all hover:cursor-pointer border-b'>
                <th scope='row' class='hidden px-6 py-3 font-medium text-gray-900 whitespace-nowrap'><?= $incidencia['INC_numero']; ?></th>
                <td class='px-6 py-2 text-center'><?= $incidencia['INC_numero_formato']; ?></td>
                <td class='px-6 py-2 text-center'><?= $incidencia['fechaIncidenciaFormateada']; ?></td>
                <td class='px-6 py-2 text-center'><?= $incidencia['ARE_nombre']; ?></td>
                <td class='px-6 py-2 text-center'><?= $incidencia['INC_codigoPatrimonial']; ?></td>
                <td class='px-6 py-2 text-center'><?= $incidencia['BIE_nombre']; ?></td>
                <td class='px-6 py-2 text-center'><?= $incidencia['INC_asunto']; ?></td>
                <td class='px-6 py-2 text-center'><?= $incidencia['INC_documento']; ?></td>
                <td class='px-6 py-2 text-center'><?= $incidencia['Usuario']; ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($resultadoIncidencias)) : ?>
              <tr>
                <td colspan="8" class="text-center py-4">No se han registrado nuevas incidencias.</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <!-- Fin de cuerpo de la tabla -->
        </table>
      </div>
    </div>
    <!-- Fin de tabla de incidencias sin recepcionar -->

    <!-- Formulario de registro de recepción de incidencias -->
    <form id="formRecepcion" action="registro-recepcion.php?action=registrar" method="POST" class="card table-card bg-white shadow-md p-4 w-full text-xs mb-3">
      <input type="hidden" id="form-action" name="action" value="registrar">

      <div class="flex flex-wrap -mx-2 justify-center">
        <!-- NUMERO DE INCIDENCIA -->
        <div class="flex justify-center mx-2 mb-2 ">
          <div class="flex-1 max-w-[500px] px-2 mb-2 flex items-center hidden">
            <label for="incidencia" class="block font-bold mb-1 mr-3 text-[#32cfad]">C&oacute;digo de incidencia:</label>
            <input type="text" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" id="incidencia" name="incidencia" readonly required>
          </div>
        </div>

        <!-- INCIDENCIA SELECCIONADA -->
        <div class="flex justify-center items-center mr-5 ml-5">
          <div class="text-center">
            <label for="incidenciaSeleccionada" class="block font-bold mb-1 text-[#32cfad]">Incidencia seleccionada:</label>
            <input type="text" class="border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center w-full" id="incidenciaSeleccionada" name="incidenciaSeleccionada" readonly required>
          </div>
        </div>

        <!-- Numero de recepcion -->
        <div class="flex justify-center mx-2 mb-2 hidden">
          <div class="flex-1 max-w-[500px] px-2 mb-2 flex items-center">
            <label for="num_recepcion" class="block font-bold mb-1 mr-3 text-lime-500">Número de Recepción:</label>
            <input type="text" id="num_recepcion" name="num_recepcion" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
          </div>
        </div>

        <!-- FECHA DE RECEPCION -->
        <div class="w-full md:w-1/5 px-2 mb-2 hidden">
          <label for="fecha_recepcion" class="block font-bold mb-1">Fecha de Recepci&oacute;n:</label>
          <input type="date" id="fecha_recepcion" name="fecha_recepcion" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo date('Y-m-d'); ?>" readonly>
        </div>

        <!-- HORA DE RECEPCION -->
        <div class="w-full md:w-1/5 px-2 mb-2 hidden">
          <label for="hora" class="block font-bold mb-1">Hora:</label>
          <?php
          // Establecer la zona horaria deseada
          date_default_timezone_set('America/Lima');
          $fecha_actual = date('Y-m-d');
          // Obtener la hora actual en formato de 24 horas (HH:MM)
          $hora_actual = date('H:i:s');
          ?>
          <input type="text" id="hora" name="hora" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $hora_actual; ?>" readonly>
        </div>

        <!-- USUARIO QUE REGISTRA LA RECEPCION -->
        <div class="w-full md:w-1/5 px-2 mb-2 hidden">
          <label for="usuarioDisplay" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="usuarioDisplay" name="usuarioDisplay" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['usuario']; ?>" readonly>
        </div>
        <div class="w-full md:w-1/5 px-2 mb-2 hidden">
          <label for="usuario" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="usuario" name="usuario" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoUsuario']; ?>">
        </div>

        <!-- SELECT PRIORIDAD -->
        <div class="flex flex-wrap -mx-2 ml-5 mr-4">
          <div class="w-full px-2">
            <label for="prioridad" class="block font-bold mb-1">Prioridad:</label>
            <select id="prioridad" name="prioridad" class="border p-2 w-full text-xs cursor-pointer rounded-md">
            </select>
          </div>
        </div>

        <!-- SELECT IMPACTO -->
        <div class="flex flex-wrap -mx-2 mr-4">
          <div class="w-full px-2">
            <label for="impacto" class="block font-bold mb-1">Impacto:</label>
            <select id="impacto" name="impacto" class="border p-2 w-full text-xs cursor-pointer rounded-md">
            </select>
          </div>
        </div>

        <!-- RECOPILACION DE VALORES DE CADA INPUT Y COMBOBOX -->
        <!-- <script>
          document.getElementById('incidencia').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['INC_numero'] : ''; ?>';
          document.getElementById('num_recepcion').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['REC_numero'] : ''; ?>';
          document.getElementById('hora').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['REC_hora'] : $hora_actual; ?>';
          document.getElementById('fecha').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['REC_fecha'] : $fecha_actual; ?>';
          document.getElementById('prioridad').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['PRI_codigo'] : ''; ?>';
          document.getElementById('impacto').value = '<?php echo $recepcionRegistrada ? $recepcionRegistrada['IMP_codigo'] : ''; ?>';
        </script> -->

        <!-- Botones de formulario -->
        <div class="flex justify-center items-center space-x-4 mt-3 ml-5">
          <button type="submit" id="guardar-recepcion" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md disabled:bg-gray-300 disabled:cursor-not-allowed"><i class="feather mr-2 icon-save"></i>Recepcionar</button>
          <button type="button" id="editar-recepcion" class="bn btn-info text-xs text-white font-bold py-2 px-3 rounded-md disabled:bg-gray-300 disabled:cursor-not-allowed" disabled><i class="feather mr-2 icon-edit"></i>Actualizar</button>
          <button type="button" id="nuevo-registro" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-plus-square"></i>Limpiar</button>
        </div>
        <!-- Fin de botones -->
      </div>
    </form>
    <!-- Fin de formulario -->

    <!-- Segundo apartado -->
    <div class="w-full">
      <!-- Titulo y paginacion de tabla de recepciones -->
      <div class="flex justify-between items-center mb-2">
        <h1 class="text-xl text-gray-400">Lista de incidencias recepcionadas</h1>
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

      <!-- Tabla de incidencias recepcionadas -->
      <div class="relative max-h-[500px] overflow-x-hidden shadow-md sm:rounded-lg">
        <table id="tablaIncidenciasRecepcionadas" class="w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer bg-white">
          <!-- Encabezado de la tabla -->
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-blue-300">
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
                  <td class="px-6 py-2 justify-center text-center flex space-x-2">
                    <button type="button" class="eliminar-recepcion bn btn-danger text-xs text-white font-bold py-2 px-3 rounded-md flex items-center justify-center" title="Eliminar recepci&oacute;n">
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
  </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>