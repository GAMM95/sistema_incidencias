<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Consulta de incidencias</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-clipboard"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Consultas</a></li>
              <li class="breadcrumb-item"><a href="">Incidencias</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Formulario Consulta de incidencias -->
    <form id="formConsultarIncidenciaUser" action="consultar-incidencia-user.php?action=consultar_usuario" method="GET" class="card table-card bg-white shadow-md p-6 w-full text-xs mb-2">
      <div class="flex flex-wrap items-center -mx-2 justify-center space-x-2">
        <!-- AREA DEL USUARIO -->
        <div class="w-full sm:w-1/6 px-2 mb-2 hidden">
          <label for="codigoArea" class="block mb-1 font-bold text-xs">C&oacute;digo &Aacute;rea:</label>
          <input type="text" id="codigoArea" name="codigoArea" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoArea']; ?>" readonly>
        </div>

        <!-- BUSCAR POR CODIGO PATRIMONIAL -->
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="codigoPatrimonial" class="block mb-1 font-bold text-xs">C&oacute;digo Patrimonial:</label>
          <input type="text" id="codigoPatrimonial" name="codigoPatrimonial" class="border p-2 w-full text-xs rounded-md" maxlength="12" pattern="\d{1,12}" inputmode="numeric" title="Ingrese solo d&iacute;gitos" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Ingrese c&oacute;digo patrimonial">
        </div>

        <!-- BUSCAR POR ESTADO -->
        <div class="w-full md:w-1/6 px-2 mb-2">
          <label for="estado" class="block mb-1 font-bold text-xs">Estado:</label>
          <select id="estado" name="estado" class="border p-2 w-full text-xs cursor-pointer"></select>
        </div>

        <!-- BUSCAR POR FECHA DE INICIO -->
        <div class="w-full md:w-1/6 px-2 mb-2">
          <label for="fechaInicio" class="block mb-1 font-bold text-xs text-center">Fecha Inicio:</label>
          <input type="date" id="fechaInicio" name="fechaInicio" class="w-full text-center border p-2 text-xs cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
        </div>

        <!-- BUSCAR POR FECHA DE FIN -->
        <div class="w-full md:w-1/6 px-2 mb-2">
          <label for="fechaFin" class="block mb-1 font-bold text-xs text-center">Fecha Fin:</label>
          <input type="date" id="fechaFin" name="fechaFin" class="w-full border text-center p-2 text-xs cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
        </div>
      </div>

      <!-- TIPO DE BIEN -->
      <div class="flex justify-center items-center text-center">
        <input type="text" id="tipoBien" name="tipoBien" class="border p-2 w-1/2 text-xs text-center rounded-md" disabled readonly placeholder="Nombre del bien">
      </div>

      <!-- BOTONES DEL FORMULARIO -->
      <div class="flex justify-center space-x-2 mt-2">
        <button type="submit" id="buscar-incidencias" class="bg-blue-500 text-white font-bold py-2 px-3 rounded-md" value="consultar"> <i class="feather mr-2 icon-filter"></i>Filtrar</button>
        <button type="button" id="limpiarCamposUserArea" class="bg-gray-500 text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-refresh-cw"></i>Nueva consulta</button>
      </div>
      <!-- Fin de Botones -->
    </form>
    <!-- Fin de formulario de consultas -->


    <!-- TABLA DE RESULTADOS DE LAS INCIDENCIAS -->
    <div class="relative shadow-md sm:rounded-lg">
      <div class="max-w-full overflow-hidden shadow-md sm:rounded-lg">
        <table id="tablaIncidenciasUser" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
          <!-- Encabezado de la tabla -->
          <thead class="text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
              <th scope="col" class="px-3 py-2 text-center hidden">N&deg;</th>
              <th scope="col" class="px-3 py-2 text-center">N&deg; INCIDENCIA</th>
              <th scope="col" class="px-3 py-2 text-center hidden">&Aacute;rea</th>
              <th scope="col" class="px-3 py-3 text-center">Fecha incidencia</th>
              <th scope="col" class="px-3 py-3 text-center">Asunto</th>
              <th scope="col" class="px-3 py-3 text-center">Documento</th>
              <th scope="col" class="px-3 py-3 text-center">C&oacute;digo Patrimonial</th>
              <th scope="col" class="px-3 py-3 text-center">Nombre del Bien</th>
              <th scope="col" class="px-3 py-3 text-center hidden">Fecha Recepcion</th>
              <th scope="col" class="px-3 py-3 text-center">Prioridad</th>
              <th scope="col" class="px-3 py-3 text-center hidden">Impacto</th>
              <th scope="col" class="px-3 py-3 text-center">Fecha Cierre</th>
              <th scope="col" class="px-3 py-3 text-center">Condici&oacute;n</th>
              <th scope="col" class="px-3 py-3 text-center hidden">Usuario</th>
              <th scope="col" class="px-3 py-3 text-center">Estado</th>
            </tr>
          </thead>
          <!-- Fin de encabezado de tabla -->

          <!-- Cuerpo de tabla -->
          <tbody>
            <?php if (!empty($resultadoBusqueda)): ?>
              <?php $item = 1; // Iniciar contador para el ítem 
              ?>
              <?php foreach ($resultadoBusqueda as $incidencia): ?>
                <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                  <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                  <td class="px-3 py-2 text-center hidden"><?= htmlspecialchars($incidencia['INC_numero']) ?></td>
                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencia['INC_numero_formato']) ?></td>
                  <td class="px-3 py-2 text-center hidden"><?= htmlspecialchars($incidencia['ARE_nombre']) ?></td>
                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencia['fechaIncidenciaFormateada']) ?></td>
                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencia['INC_asunto']) ?></td>
                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencia['INC_documento']) ?></td>
                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencia['INC_codigoPatrimonial']) ?></td>
                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencia['BIE_nombre']) ?></td>
                  <td class="px-3 py-2 text-center hidden"><?= htmlspecialchars($incidencia['fechaRecepcionFormateada']) ?></td>
                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencia['PRI_nombre']) ?></td>
                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencia['fechaCierreFormateada']) ?></td>
                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencia['CON_descripcion']) ?></td>
                  <td class="px-3 py-2 text-center text-center text-xs align-middle">
                    <?php
                    $estadoDescripcion = htmlspecialchars($incidencia['Estado']);
                    $badgeClass = '';
                    switch ($estadoDescripcion) {
                      case 'ABIERTO':
                        $badgeClass = 'badge-light-danger';
                        break;
                      case 'RECEPCIONADO':
                        $badgeClass = 'badge-light-success';
                        break;
                      case 'CERRADO':
                        $badgeClass = 'badge-light-primary';
                        break;
                      default:
                        $badgeClass = 'badge-light-secondary';
                        break;
                    }
                    ?>
                    <label class="badge <?= $badgeClass ?>"><?= $estadoDescripcion ?></label>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="15" class="text-center py-3">No se encontraron incidencias.</td>
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