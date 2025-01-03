<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Consulta de incidencias asignadas</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-clipboard"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Consultas</a></li>
              <li class="breadcrumb-item"><a href="">Asignaciones personales</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Formulario de consulta de asignacion de incidencias -->
    <form id="formConsultarAsignacionesSoporte" action="consultar-asignaciones.php?action=consultar" method="GET" class="card table-card  bg-white shadow-md p-6 w-full text-xs mb-2">
      <div class="flex flex-wrap -mx-2 justify-center">
        <!-- Usuario de inicio de sesion -->
        <div class="w-full md:w-1/6 px-2 mb-2 hidden">
          <label for="codigoUsuario" class="block font-bold mb-1">Usuario:</label>
          <input type="text" id="codigoUsuario" name="codigoUsuario" class="border border-gray-200 bg-gray-100 p-2 w-full text-xs" value="<?php echo $_SESSION['codigoUsuario']; ?>" readonly>
        </div>
        <!-- Fin de usuario de inicio de sesion -->

        <!-- BUSCAR POR CODIGO PATRIMONIAL -->
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="codigoPatrimonial" class="block mb-1 font-bold text-xs">C&oacute;digo Patrimonial:</label>
          <input type="text" id="codigoPatrimonial" name="codigoPatrimonial" class="border p-2 w-full text-xs rounded-md" maxlength="12" pattern="\d{1,12}" inputmode="numeric" title="Ingrese solo dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Ingrese c&oacute;digo patrimonial">
        </div>

        <!-- BUSCAR POR FECHA DE INICIO-->
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="fechaInicio" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
          <input type="date" id="fechaInicio" name="fechaInicio" class="w-full border p-2 text-xs cursor-pointer text-center rounded-md" max="<?php echo date('Y-m-d'); ?>">
        </div>

        <!-- Buscar por fecha fin -->
        <div class="w-full sm:w-1/3 md:w-1/5 px-2 mb-2">
          <label for="fechaFin" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
          <input type="date" id="fechaFin" name="fechaFin" class="w-full border p-2 text-xs cursor-pointer text-center rounded-md" max="<?php echo date('Y-m-d'); ?>">
        </div>
      </div>

      <!-- TIPO DE BIEN -->
      <div class="flex justify-center items-center text-center">
        <input type="text" id="tipoBien" name="tipoBien" class="border p-2 w-1/2 text-xs text-center rounded-md" disabled readonly placeholder="Nombre del bien">
      </div>

      <!-- BOTONES DEL FORMULARIO -->
      <div class="flex justify-center space-x-2 mt-2">
        <button type="submit" id="buscar-asignaciones" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-search"></i>Buscar</button>
        <button type="button" id="limpiarCamposSoporte" class="bn btn-secondary text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-refresh-cw"></i>Nueva consulta</button>
      </div>
      <!-- Fin de Botones -->
    </form>
    <!-- Fin de formulario de consultas -->

    <!-- Tabla de resultados para asignaciones -->
    <div class="relative shadow-md sm:rounded-lg">
      <div class="relative overflow-x-hidden shadow-md sm:rounded-lg">
        <table id="tablaIncidenciasMantenimientoSoporte" class="w-full text-xs text-left rtl:text-right text-gray-500 bg-white">
          <!-- Encabezado de la tabla -->
          <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-lime-300">
            <tr>
              <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
              <th scope="col" class="px-6 py-2 hidden">Asignaci&oacute;n</th>
              <th scope="col" class="px-6 py-2 hidden">Recepci&oacute;n</th>
              <th scope="col" class="px-6 py-2 text-center">Incidencia</th>
              <th scope="col" class="px-6 py-2 text-center">&Aacute;rea solicitante</th>
              <th scope="col" class="px-6 py-2 text-center">Asunto</th>
              <th scope="col" class="px-6 py-2 text-center">Equipo</th>
              <th scope="col" class="px-6 py-2 text-center">Nombre del bien</th>
              <th scope="col" class="px-6 py-2 text-center">Fecha de asignaci&oacute;n</th>
              <th scope="col" class="px-6 py-2 text-center">Fecha de finalizaci&oacute;n</th>
              <th scope="col" class="px-6 py-2 text-center">Tiempo de mantenimiento</th>
              <th scope="col" class="px-6 py-2 text-center">Estado</th>
            </tr>
          </thead>
          <!-- Fin de encabezado -->

          <!-- Cuerpo de la tabla -->
          <tbody>
            <?php if (!empty($resultadoBusqueda)) : ?>
              <?php $item = 1; // Iniciar contador para el ítem
              foreach ($resultadoBusqueda as $asignaciones) : ?>
                <?php
                $numeroAsignacion = htmlspecialchars($asignaciones['ASI_codigo']);
                ?>
                <tr class='second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b' data-id="<?= $numeroAsignacion; ?>">
                  <th scope='row' class='px-6 py-3 font-medium text-gray-900 whitespace-nowrap hidden'><?= $numeroAsignacion; ?></th>
                  <th scope='row' class='px-6 py-3 font-medium text-gray-900 whitespace-nowrap hidden'><?= $asignaciones['REC_numero']; ?></th>
                  <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                  <td class='px-6 py-2 text-center'><?= $asignaciones['INC_numero_formato']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['ARE_nombre']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['INC_asunto']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['INC_codigoPatrimonial']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['BIE_nombre']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['fechaAsignacionFormateada']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['fechaMantenimientoFormateada']; ?></td>
                  <td class='px-6 py-2 text-center'><?= $asignaciones['tiempoMantenimientoFormateado']; ?></td>
                  <td class="px-3 py-2 text-center text-xs align-middle">
                    <?php
                    $estadoDescripcion = htmlspecialchars($asignaciones['Estado']);
                    $badgeClass = '';
                    switch ($estadoDescripcion) {
                      case 'EN ESPERA':
                        $badgeClass = 'badge-light-danger';
                        break;
                      case 'RESUELTO':
                        $badgeClass = 'badge-light-primary';
                        break;
                      case 'CERRADO':
                        $badgeClass = 'badge-light-secondary';
                        break;
                      default:
                        $badgeClass = 'badge-light-info';
                        break;
                    }
                    ?>
                    <label class="badge <?= $badgeClass ?>"><?= $estadoDescripcion ?></label>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="10" class="text-center py-3">A&uacute;n no se ha realizado mantenimiento a las incidencias asignadas.</td>
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