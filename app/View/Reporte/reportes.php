<div class="pcoded-main-container h-screen flex flex-col mt-5">
  <div class="pcoded-content flex flex-col grow">
    <!-- Miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Generaci&oacute;n de reportes</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i class="feather icon-file"></i></a></li>
              <li class="breadcrumb-item"><a href="reportes.php">Reportes</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Inicio del tab pane -->
    <div class="h-full flex flex-col grow mb-0">
      <div class="card grow">
        <div class="card-body flex flex-col grow pt-2">
          <!-- Inicio de titulos de las pestañas -->
          <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active text-uppercase" id="generales-tab" data-toggle="tab" href="#generales" role="tab" aria-controls="generales" aria-selected="true">Reportes generales</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="detalle-tab" data-toggle="tab" href="#detalle" role="tab" aria-controls="detalle" aria-selected="false">Detalle de reporte</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="registros-tab" data-toggle="tab" href="#registros" role="tab" aria-controls="registros" aria-selected="false">Reportes por ...</a>
            </li>

          </ul>
          <!-- Fin de los titulos de las pestañas -->

          <!-- Contenido de las pestañas -->
          <div class="tab-content grow" id="myTabContent">

            <!-- Contenido de la primera pestaña -->
            <div class="tab-pane fade show active" id="generales" role="tabpanel" aria-labelledby="generales-tab">
              <!-- Pestañas del primer tab -->
              <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="pills-incidenciasTotales-tab" data-toggle="pill" href="#pills-incidenciasTotales" role="tab" aria-controls="pills-incidenciasTotales" aria-selected="true">Incidencias totales</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="pills-pendientesCierre-tab" data-toggle="pill" href="#pills-pendientesCierre" role="tab" aria-controls="pills-pendientesCierre" aria-selected="false">Pendientes de cierre</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="pills-incidenciasCerradas-tab" data-toggle="pill" href="#pills-incidenciasCerradas" role="tab" aria-controls="pills-incidenciasCerradas" aria-selected="false">Incidencias cerradas</a>
                </li>
              </ul>

              <!-- Fin de pestañas del primer tab -->
              <div class="tab-content" id="pills-tabContent">
                <!-- Tab incidencias totales -->
                <div class="tab-pane fade show active" id="pills-incidenciasTotales" role="tabpanel" aria-labelledby="pills-incidenciasTotales-tab">

                  <!-- Inicio de formulario de consulta de incidencias totales -->
                  <form id="formConsultarIncidenciasTotales" action="reportes.php?action=consultarTotales" method="GET" class="bg-white w-full text-xs ">
                    <!-- Inputs y botones para filtrar incidencias -->
                    <div class="flex justify-center items-center mt-2">
                      <!-- Fecha de inicio -->
                      <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                        <label for="fechaInicioIncidenciasTotales" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                        <input type="date" id="fechaInicioIncidenciasTotales" name="fechaInicioIncidenciasTotales" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                      </div>

                      <!-- Fecha de fin -->
                      <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                        <label for="fechaFinIncidenciasTotales" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                        <input type="date" id="fechaFinIncidenciasTotales" name="fechaFinIncidenciasTotales" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                      </div>

                      <!-- Botones alineados horizontalmente -->
                      <div class="ml-5 flex space-x-2">
                        <!-- Botón de buscar -->
                        <button type="submit" id="filtrarIncidenciasTotales" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-primary rounded-md flex justify-center items-center">
                          <i class="feather icon-filter"></i>
                        </button>

                        <!-- Botón de nueva consulta -->
                        <button type="button" id="limpiarCampos_incidencias_totales" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-secondary rounded-md flex justify-center items-center">
                          <i class="feather icon-refresh-cw"></i>
                        </button>

                        <!-- Boton generar reporte -->
                        <div class="btn-group mr-2">
                          <div class="flex justify-center space-x-2">
                            <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="feather mr-2 icon-file"></i>Reporte
                            </button>
                            <div class="dropdown-menu">
                              <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasTotales">Todos las incidencias</div>
                              <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasTotalesFecha">Incidencias por fechas</div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Fin de botones alineados horizontalmente -->
                    </div>
                    <!-- Fin de inputs y botones para filtrar incidencias -->

                    <!-- Tabla de resultados de incidencias totales -->
                    <div class="relative sm:rounded-lg mt-2">
                      <div class="max-w-full overflow-hidden sm:rounded-lg">
                        <table id="tablaIncidenciasTotales" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                          <!-- Encabezado de la tabla -->
                          <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                            <tr>
                              <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                              <th scope="col" class="px-3 py-2 text-center">Incidencia</th>
                              <th scope="col" class="px-3 py-2 text-center">Fecha Inc.</th>
                              <th scope="col" class="px-3 py-2 text-center">Asunto</th>
                              <th scope="col" class="px-3 py-2 text-center">Documento</th>
                              <th scope="col" class="px-3 py-2 text-center">C&oacute;d. Patrimonial</th>
                              <th scope="col" class="px-3 py-2 text-center">Nombre del Bien</th>
                              <th scope="col" class="px-3 py-2 text-center">&Aacute;rea solicitante</th>
                              <th scope="col" class="px-3 py-2 text-center">Prioridad</th>
                              <th scope="col" class="px-3 py-2 text-center">Condici&oacute;n</th>
                              <th scope="col" class="px-3 py-2 text-center">Estado</th>
                            </tr>
                          </thead>
                          <!-- Fin de encabezado de la tabla -->

                          <!-- Cuerpo de la tabla -->
                          <tbody>
                            <?php if (!empty($resultadoIncidenciasTotales)): ?>
                              <?php $item = 1; // Iniciar contador para el ítem 
                              ?>
                              <?php foreach ($resultadoIncidenciasTotales as $totales): ?>
                                <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                  <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_numero_formato']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['fechaIncidenciaFormateada']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_asunto']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_documento']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_codigoPatrimonial']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['BIE_nombre']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['ARE_nombre']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['PRI_nombre']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['CON_descripcion']) ?></td>
                                  <td class="px-3 py-2 text-center text-xs align-middle">
                                    <?php
                                    $estadoDescripcion = htmlspecialchars($totales['Estado']);
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
                                <td colspan="11" class="text-center py-3">No se encontraron registros de incidencias.</td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                          <!-- Fin de cuerpo de tabla -->
                        </table>
                      </div>
                    </div>
                    <!-- Fin de tabla de resultados de incidencias totales -->
                  </form>
                  <!-- Fin de formulario de consulta de incidencias totales -->
                </div>
                <!-- Fin de primer tab -->

                <!-- Tab Incidencias Pendientes de Cierre -->
                <div class="tab-pane fade" id="pills-pendientesCierre" role="tabpanel" aria-labelledby="pills-pendientesCierre-tab">
                  <!-- Inicio formulario de consulta de incidencias pendientes de cierre -->
                  <form id="formConsultarPendientesCierre" action="reportes.php?action=consultarPendientesCierre" method="GET" class="bg-white w-full text-xs ">
                    <!-- Boton para generar reporte -->
                    <div class="flex justify-center space-x-2">
                      <button type="button" id="reporte-pendientes-cierre" class="bn bg-red-400 text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-file"></i>Generar reporte</button>
                    </div>

                    <!-- Tabla de resultados de incidencias pendientes de cierre -->
                    <div class="relative sm:rounded-lg mt-2">
                      <div class="max-w-full overflow-hidden sm:rounded-lg">
                        <table id="tablaPendientesCierre" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                          <!-- Encabezado de la tabla -->
                          <thead class="text-xs text-gray-700 uppercase bg-red-300">
                            <tr>
                              <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                              <th scope="col" class="px-3 py-2 text-center">Incidencia</th>
                              <th scope="col" class="px-3 py-2 text-center">Fecha Inc.</th>
                              <th scope="col" class="px-3 py-2 text-center">Asunto</th>
                              <th scope="col" class="px-3 py-2 text-center">Documento</th>
                              <th scope="col" class="px-3 py-2 text-center">C&oacute;d. Patrimonial</th>
                              <th scope="col" class="px-3 py-2 text-center">Nombre del Bien</th>
                              <th scope="col" class="px-3 py-2 text-center">&Aacute;rea solicitante</th>
                              <th scope="col" class="px-3 py-2 text-center">Prioridad</th>
                              <th scope="col" class="px-3 py-2 text-center">Estado</th>
                            </tr>
                          </thead>
                          <!-- Fin de encabezado de la tabla -->

                          <!-- Cuerpo de la tabla -->
                          <tbody>
                            <?php if (!empty($resultadoPendientesCierre)): ?>
                              <?php $item = 1; // Iniciar contador para el ítem 
                              ?>
                              <?php foreach ($resultadoPendientesCierre as $pendientes): ?>
                                <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                  <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($pendientes['INC_numero_formato']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($pendientes['fechaIncidenciaFormateada']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($pendientes['INC_asunto']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($pendientes['INC_documento']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($pendientes['INC_codigoPatrimonial']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($pendientes['BIE_nombre']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($pendientes['ARE_nombre']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($pendientes['PRI_nombre']) ?></td>
                                  <td class="px-3 py-2 text-center text-xs align-middle">
                                    <?php
                                    $estadoDescripcion = htmlspecialchars($pendientes['ESTADO']);
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
                                <td colspan="10" class="text-center py-3">No se encontraron registros de incidencias pendientes de cierre.</td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                          <!-- Fin de cuerpo de tabla -->
                        </table>
                      </div>
                    </div>
                    <!-- Fin de tabla de resultados de incidencias pendientes de cierre -->
                  </form>
                </div>
                <!--Fin de segundo tab -->

                <!-- TODO: Tab Incidencias Cerradas -->
                <div class="tab-pane fade" id="pills-incidenciasCerradas" role="tabpanel" aria-labelledby="pills-incidenciasCerradas-tab">
                  <!-- Inicio formulario de consulta de incidencias cerradas -->
                  <form id="formConsultarIncidenciasCerradas" action="reportes.php?action=consultarCerradas" method="GET" class="bg-white w-full text-xs ">
                    <!-- Inputs y botones para filtrar incidencias -->
                    <div class="flex justify-center items-center mt-2">
                      <!-- Fecha de inicio -->
                      <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                        <label for="fechaInicio" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                        <input type="date" id="fechaInicio_incidencias_cerradas" name="fechaInicio" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                      </div>
                      <!-- Fin de fecha de inicio -->

                      <!-- Fecha de fin -->
                      <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                        <label for="fechaFin" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                        <input type="date" id="fechaFin_incidencias_cerradas" name="fechaFin" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                      </div>
                      <!-- Fin de fecha de fin -->

                      <!-- Botones alineados horizontalmente -->
                      <div class="ml-5 flex space-x-2">
                        <!-- Botón de buscar -->
                        <button type="button" id="filtrarIncidenciasCerradasFecha" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 btn-primary rounded-md flex justify-center items-center">
                          <i class="feather icon-filter"></i>
                        </button>
                        <!-- Botón de nueva consulta -->
                        <button type="button" id="limpiarCampos_incidencias_cerradas" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-500 rounded-md flex justify-center items-center">
                          <i class="feather icon-refresh-cw"></i>
                        </button>
                        <!-- Boton generar reporte filtrado-->
                        <button type="button" id="reportes-cierres-fechas" class="bn text-xs font-bold py-2 px-3 rounded-md text-white bg-gray-300 cursor-not-allowed" disabled>
                          <i class="feather mr-2 icon-file"></i>Reporte filtrado
                        </button>
                        <!-- Boton generar reporte -->
                        <div class="flex justify-center space-x-2">
                          <button type="button" id="reporte-incidencias-totales" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-file"></i>Reporte totales</button>
                        </div>
                      </div>
                      <!-- Fin de botones alineados horizontalmente -->
                    </div>
                    <!-- Fin de inputs y botones para filtrar incidencias -->

                    <!-- Tabla de resultados de incidencias cerradas -->
                    <div class="relative sm:rounded-lg mt-2">
                      <div class="max-w-full overflow-hidden sm:rounded-lg">
                        <table id="tablaIncidenciasCerradas" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                          <!-- Encabezado de la tabla -->
                          <thead class="text-xs text-gray-700 uppercase bg-green-300">
                            <tr>
                              <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                              <th scope="col" class="px-3 py-2 text-center">Incidencia</th>
                              <th scope="col" class="px-3 py-2 text-center">Fecha de cierre</th>
                              <th scope="col" class="px-3 py-2 text-center">Asunto</th>
                              <th scope="col" class="px-3 py-2 text-center">Documento</th>
                              <th scope="col" class="px-3 py-2 text-center">C&oacute;d. Patrimonial</th>
                              <th scope="col" class="px-3 py-2 text-center">Nombre del Bien</th>
                              <th scope="col" class="px-3 py-2 text-center">&Aacute;rea solicitante</th>
                              <th scope="col" class="px-3 py-2 text-center">Prioridad</th>
                              <th scope="col" class="px-3 py-2 text-center">Condici&oacute;n</th>
                            </tr>
                          </thead>
                          <!-- Fin de encabezado de la tabla -->

                          <!-- Cuerpo de la tabla -->
                          <tbody>
                            <?php if (!empty($resultadoIncidenciasCerradas)): ?>
                              <?php $item = 1; // Iniciar contador para el ítem 
                              ?>
                              <?php foreach ($resultadoIncidenciasCerradas as $cerradas): ?>
                                <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                  <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($cerradas['INC_numero_formato']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($cerradas['fechaCierreFormateada']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($cerradas['INC_asunto']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($cerradas['INC_documento']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($cerradas['INC_codigoPatrimonial']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($cerradas['BIE_nombre']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($cerradas['ARE_nombre']) ?></td>
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($cerradas['PRI_nombre']) ?></td>
                                  <td class="px-3 py-2 text-center text-xs align-middle">
                                    <?php
                                    $condicionDescripcion = htmlspecialchars($cerradas['CON_descripcion']);
                                    $badgeClass = '';
                                    switch ($condicionDescripcion) {
                                      case 'OPERATIVO':
                                        $badgeClass = 'badge-light-info';
                                        break;
                                      case 'INOPERATIVO':
                                        $badgeClass = 'badge-light-danger';
                                        break;
                                      case 'SOLUCIONADO':
                                        $badgeClass = 'badge-light-info';
                                        break;
                                      case 'NO SOLUCIONADO':
                                        $badgeClass = 'badge-light-danger';
                                        break;
                                      default:
                                        $badgeClass = 'badge-light-secondary';
                                        break;
                                    }
                                    ?>
                                    <label class="badge <?= $badgeClass ?>"><?= $condicionDescripcion ?></label>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <tr>
                                <td colspan="10" class="text-center py-3">No se encontraron registros de incidencias cerradas.</td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                          <!-- Fin de cuerpo de tabla -->
                        </table>
                      </div>
                    </div>
                    <!-- Fin de tabla de resultados de incidencias totales -->
                  </form>
                </div>
                <!-- Fin de tercer tab -->
              </div>
            </div>
            <!-- Fin de contenido de la primera pestaña -->

            <!-- Contenido de la segunda pestaña  - DETALLE DE REPORTE -->
            <div class="tab-pane fade" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
              <div class="flex items-center mb-4 hidden">
                <!-- Número de incidencia -->
                <label for="num_incidencia" class="block mb-1 mr-1">N&deg; Incidencia:</label>
                <input type="text" id="num_incidencia" name="num_incidencia" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
                <!-- Fin de número de incidencia -->
                <!-- Numero de cierre -->
                <label for="num_cierre" class="block mb-1 mr-1">N&deg; Cierre:</label>
                <input type="text" id="num_cierre" name="num_cierre" class="w-20 border border-gray-200 bg-gray-100 rounded-md p-2 text-xs text-center" readonly>
                <!-- Fin de numero de cierre -->
              </div>

              <!-- Buscador de termino -->
              <div class="flex justify-between items-center">
                <input type="text" id="termino" class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 text-xs" placeholder="Buscar incidencia..." oninput="filtrarTablaIncidenciasDetalle()" />
              </div>
              <!-- Fin de buscador de termino -->

              <!-- Tabla de incidencias -->
              <div class="relative sm:rounded-lg mt-2">
                <div class="max-w-full overflow-hidden sm:rounded-lg">
                  <table id="tablaIncidenciasDetalle" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500 cursor-pointer">
                    <!-- Encabezado de la tabla -->
                    <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-blue-300">
                      <tr>
                        <th scope="col" class="px-3 py-2 text-center hidden">Num_incidencia</th>
                        <th scope="col" class="px-3 py-2 text-center hidden">Num_cierre</th>
                        <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                        <th scope="col" class="px-3 py-2 text-center">Incidencia</th>
                        <th scope="col" class="px-3 py-2 text-center">Fecha Inc.</th>
                        <th scope="col" class="px-3 py-2 text-center">Asunto</th>
                        <th scope="col" class="px-3 py-2 text-center">Documento</th>
                        <th scope="col" class="px-3 py-2 text-center">C&oacute;d. Patrimonial</th>
                        <th scope="col" class="px-3 py-2 text-center">Nombre del Bien</th>
                        <th scope="col" class="px-3 py-2 text-center">&Aacute;rea solicitante</th>
                        <th scope="col" class="px-3 py-2 text-center">Prioridad</th>
                        <th scope="col" class="px-3 py-2 text-center">Estado</th>
                        <th scope="col" class="px-3 py-2 text-center">Reporte</th>
                      </tr>
                    </thead>
                    <!-- Fin de encabezado de la tabla -->

                    <!-- Cuerpo de la tabla -->
                    <tbody>
                      <?php $item = 1; // Iniciar contador para el ítem 
                      ?>
                      <?php foreach ($resultadoIncidenciasTotales as $totales): ?>
                        <tr class="second-table hover:bg-green-100 hover:scale-[101%] transition-all border-b" data-id="<?= $totales['INC_numero']; ?>">
                          <th scope="row" class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap hidden"><?= $totales['INC_numero']; ?></th>
                          <td class="px-3 py-2 text-center hidden"><?= htmlspecialchars($totales['INC_numero']) ?></td>
                          <td class="px-3 py-2 text-center hidden"><?= htmlspecialchars($totales['CIE_numero']) ?></td>
                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->

                          <td class="px-3 py-2 text-center"><?= $totales['INC_numero_formato']; ?></td>
                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['fechaIncidenciaFormateada']); ?></td>
                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_asunto']); ?></td>
                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_documento']); ?></td>
                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_codigoPatrimonial']); ?></td>
                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['BIE_nombre']); ?></td>
                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['ARE_nombre']); ?></td>
                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['PRI_nombre']); ?></td>
                          <td class="px-3 py-2 text-center text-xs align-middle">
                            <?php
                            $estadoDescripcion = htmlspecialchars($totales['Estado']);
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
                          <td class="px-6 py-2 text-center align-middle flex space-x-2"> <!-- Columna de Acción con botones -->
                            <!-- Botón de Imprimir detalle de incidencia -->
                            <button type="button" id="imprimir-incidencia" class="bn btn-warning text-xs text-white font-bold py-2 px-3 rounded-md flex items-center justify-center" title="Detalle de incidencia">
                              <i class="feather icon-file"></i>
                            </button>
                            <!-- Botón de imprimir detalle de cierre -->
                            <button type="button" id="imprimir-cierre" class="bn bg-blue-400 text-xs text-white font-bold py-2 px-3 rounded-md flex items-center justify-center disabled:bg-gray-300 disabled:cursor-not-allowed " title="Detalle de cierre"
                              <?= $estadoDescripcion !== 'CERRADO' ? 'disabled' : '' ?>>
                              <i class="feather icon-file"></i>
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                      <?php if (empty($resultadoIncidenciasTotales)): ?>
                        <tr>
                          <td colspan="10" class="text-center py-3">No se encontraron registros de incidencias.</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                    <!-- Fin de cuerpo de tabla -->
                  </table>
                </div>
              </div>
              <!-- Fin de tabla de incidencias -->

            </div>
            <!-- Fin de contenido de la segunda pestaña -->

            <!-- Contenido de la tercera pestaña -->
            <div class="tab-pane fade" id="registros" role="tabpanel" aria-labelledby="registros-tab">
              <div class="col-sm-20">
                <!-- <div class="card"> -->
                <div class="card-body">
                  <div class="row">
                    <!-- Pestañas verticales -->
                    <div class="col-md-2 col-sm-10">
                      <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <li><a class="nav-link text-left active" id="v-pills-area-tab" data-toggle="pill" href="#v-pills-area" role="tab" aria-controls="v-pills-area" aria-selected="false">&Aacute;rea</a></li>
                        <li><a class="nav-link text-left" id="v-pills-codPatrimonial-tab" data-toggle="pill" href="#v-pills-codPatrimonial" role="tab" aria-controls="v-pills-codPatrimonial" aria-selected="false">C&oacute;d. Patrimonial</a></li>
                      </ul>
                    </div>
                    <!-- Fin de pestañas verticales -->

                    <!-- Contenido de las pestañas -->
                    <div class="col-md-10 col-sm-18">
                      <div class="tab-content" id="v-pills-tabContent">

                        <!-- Contenido de la primera pestaña -->
                        <div class="tab-pane fade show active" id="v-pills-area" role="tabpanel" aria-labelledby="v-pills-area-tab">
                          <div class="flex justify-center space-x-4">
                            <!-- Buscar por área -->
                            <div class="text-center w-full md:w-3/4">
                              <select id="area" name="area" class="border p-2 w-full text-xs cursor-pointer">
                              </select>
                              <input type="" id="codigoArea" name="codigoArea" readonly>
                              <input type="" id="nombreArea" name="nombreArea" readonly>
                            </div>

                            <!-- Botones -->
                            <div class="text-center w-full md:w-1/4">
                              <button type="button" id="filtrar-areas" class="bn btn-primary text-xs text-white font-bold p-2 rounded-md">
                                <i class="feather mr-2 icon-filter"></i> </button>
                              <button type="button" id="reportes-areas" class="bn btn-primary text-xs text-white font-bold p-2 rounded-md">
                                <i class="feather mr-2 icon-printer"></i>Generar reporte
                              </button>
                            </div>
                          </div>
                          <!-- Tabla de resultados de incidencias totales -->
                          <div class="relative sm:rounded-lg mt-2">
                            <div class="max-w-full overflow-hidden sm:rounded-lg">
                              <table id="tablaReporteAreas" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                <!-- Encabezado de la tabla -->
                                <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                                  <tr>
                                    <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                                    <th scope="col" class="px-3 py-2 text-center">Incidencia</th>
                                    <th scope="col" class="px-3 py-2 text-center">Fecha Inc.</th>
                                    <th scope="col" class="px-3 py-2 text-center">Asunto</th>
                                    <th scope="col" class="px-3 py-2 text-center">Documento</th>
                                    <th scope="col" class="px-3 py-2 text-center">C&oacute;d. Patrimonial</th>
                                    <th scope="col" class="px-3 py-2 text-center">Nombre del Bien</th>
                                    <th scope="col" class="px-3 py-2 text-center">Prioridad</th>
                                    <th scope="col" class="px-3 py-2 text-center">Condici&oacute;n</th>
                                    <th scope="col" class="px-3 py-2 text-center">Estado</th>
                                  </tr>
                                </thead>
                                <!-- Fin de encabezado de la tabla -->
                                <tbody>
                                  <!-- Las filas se agregarán aquí dinámicamente -->
                                </tbody>
                              </table>

                            </div>
                          </div>
                          <!-- Fin de tabla de resultados de incidencias totales -->










                        </div>
                        <!-- Fin de contenido de la primera pestaña -->

                        <!-- Contenido de la segunda pestaña -->
                        <div class="tab-pane fade" id="v-pills-codPatrimonial" role="tabpanel" aria-labelledby="v-pills-codPatrimonial-tab">
                          <div class="flex justify-center space-x-4">
                            <!-- input código patrimonial -->
                            <div class="text-center w-full md:w-1/4">
                              <input type="text" id="codigoPatrimonial" name="codigoPatrimonial" class="border p-2 w-full text-xs text-center rounded-md" maxlength="12" pattern="\d{1,12}" inputmode="numeric" title="Ingrese solo dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Ingrese c&oacute;digo patrimonial">
                            </div>
                            <!-- Fin de input de código patrimonial -->

                            <!-- input tipo de bien (más largo) -->
                            <div class="text-center w-full md:w-3/4">
                              <input type="text" id="tipoBien" name="tipoBien" class="border p-2 w-full text-xs text-center rounded-md" disabled readonly>
                            </div>
                            <!-- Fin de input tipo de bien -->
                          </div>
                          <!-- Botones -->
                          <div class="flex justify-center space-x-2 mt-2">
                            <button type="button" id="reportes-codigoPatrimonial" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-printer"></i>Generar reporte</button>
                          </div>
                        </div>
                        <!-- Fin de contenido de la segunda pestaña -->
                      </div>
                    </div>
                    <!-- Fin de contenido de las pestañas -->

                  </div>
                </div>
                <!-- </div> -->
              </div>

            </div>
            <!-- Fin del contenido de la tercera pestaña -->

          </div>
        </div>
      </div>
    </div>
    <!-- Final del tab pane -->
  </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const codigoArea = $('#area').val();
    // Cuando se selecciona un área


    // Función para cargar los datos de la tabla por área seleccionada
    function cargarTablaPorArea(codigoArea) {
      console.log(`Cargando datos para el área: ${codigoArea}`); // Mensaje en consola para depurar
      $.ajax({
        url: 'ajax/getReportePorArea.php', // Cambia esta ruta según el archivo que maneja la consulta
        method: 'GET',
        data: {
          area: codigoArea
        },
        dataType: 'json',
        success: function(response) {
          console.log('Datos obtenidos:', response); // Mostrar los datos en consola
          if (response && response.length > 0) {
            // Limpiar la tabla antes de agregar nuevos datos
            $('#tablaReporteAreas tbody').empty();

            // Recorrer los resultados y agregar las filas a la tabla
            response.forEach(function(incidencia) {
              $('#tablaReporteAreas tbody').append(`
                  <tr>
                    <td class="px-3 py-2 text-center">${incidencia.INC_numero_formato}</td>
                    <td class="px-3 py-2 text-center">${incidencia.INC_asunto}</td>
                    <td class="px-3 py-2 text-center">${incidencia.fechaIncidenciaFormateada}</td>
                    <td class="px-3 py-2 text-center">${incidencia.INC_asunto}</td>
                    <td class="px-3 py-2 text-center">${incidencia.INC_documento}</td>
                    <td class="px-3 py-2 text-center">${incidencia.INC_codigoPatrimonial}</td>
                    <td class="px-3 py-2 text-center">${incidencia.BIE_nombre}</td>
                    <td class="px-3 py-2 text-center">${incidencia.PRI_nombre}</td>
                    <td class="px-3 py-2 text-center">${incidencia.CON_descripcion}</td>
                    <td class="px-3 py-2 text-center">${incidencia.ESTADO}</td>
                  </tr>
                `);
            });
          } else {
            // Si no hay resultados, mostrar un mensaje
            $('#tablaReporteAreas tbody').html('<tr><td colspan="10" class="text-center p-2">No se encontraron resultados.</td></tr>');
            console.log('No se encontraron datos para el área seleccionada.');
          }
        },
        error: function(xhr, status, error) {
          console.error("Error en la solicitud AJAX: " + error); // Mostrar error en consola
        }
      });
    }
  });
</script>