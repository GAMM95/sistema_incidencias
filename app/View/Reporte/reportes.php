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
              <a class="nav-link text-uppercase" id="detalle-tab" data-toggle="tab" href="#detalle" role="tab" aria-controls="detalle" aria-selected="false">Reportes de detalle</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="equipos-tab" data-toggle="tab" href="#equipos" role="tab" aria-controls="equipos" aria-selected="false">Reportes por equipo</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="areas-tab" data-toggle="tab" href="#areas" role="tab" aria-controls="areas" aria-selected="false">Reportes por &aacute;rea</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="otros-tab" data-toggle="tab" href="#otros" role="tab" aria-controls="otros" aria-selected="false">Otros reportes</a>
            </li>
          </ul>
          <!-- Fin de los titulos de las pestañas -->

          <!-- Contenido de las pestañas -->
          <div class="tab-content grow" id="myTabContent">

            <!-- Contenido de la primera pestaña - REPORTES GENERALES -->
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
                <li class="nav-item">
                  <a class="nav-link" id="pills-incidenciasAsignadas-tab" data-toggle="pill" href="#pills-incidenciasAsignadas" role="tab" aria-controls="pills-incidenciasAsignadas" aria-selected="false">Incidencias asignadas</a>
                </li>
              </ul>

              <!-- Fin de pestañas del primer tab -->
              <div class="tab-content" id="pills-tabContent">
                <!-- Tab incidencias totales -->
                <div class="tab-pane fade show active" id="pills-incidenciasTotales" role="tabpanel" aria-labelledby="pills-incidenciasTotales-tab">

                  <!-- Inicio de formulario de consulta de incidencias totales -->
                  <form id="formIncidenciasTotales" action="reportes.php?action=consultarIncidenciasTotales" method="GET" class="bg-white w-full text-xs ">
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
                        <button type="submit" id="filtrarListaIncidenciasTotales" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-primary rounded-md flex justify-center items-center" title="Previsualizar reporte">
                          <i class="feather icon-filter"></i>
                        </button>

                        <!-- Botón de nueva consulta -->
                        <button type="button" id="limpiarCamposIncidenciasTotales" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-secondary rounded-md flex justify-center items-center" title="Limpiar campos">
                          <i class="feather icon-refresh-cw"></i>
                        </button>

                        <!-- Boton generar reporte -->
                        <div class="btn-group mr-2">
                          <div class="flex justify-center space-x-2">
                            <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="feather mr-2 icon-file"></i>Reporte
                            </button>
                            <div class="dropdown-menu">
                              <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasTotales">Reporte total</div>
                              <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasTotalesFecha">Reporte por fechas</div>
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
                <!-- Fin de incidencias totales -->

                <!-- Tab Incidencias Pendientes de Cierre -->
                <div class="tab-pane fade" id="pills-pendientesCierre" role="tabpanel" aria-labelledby="pills-pendientesCierre-tab">
                  <!-- Inicio formulario de consulta de incidencias pendientes de cierre -->
                  <form id="formPendientesCierre" action="reportes.php?action=consultarPendientesCierre" method="GET" class="bg-white w-full text-xs ">
                    <!-- Boton para generar reporte -->
                    <div class="flex justify-center space-x-2">
                      <button type="button" id="reportePendientesCierre" class="bn  h-10 btn-secondary text-xs text-white font-bold py-2 px-3  rounded-md"> <i class="feather mr-2 icon-file"></i>Generar reporte</button>
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
                                    $estadoDescripcion = htmlspecialchars($pendientes['Estado']);
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
                <!--Fin de incidencias pendientes de cierre -->

                <!-- Tab Incidencias Cerradas -->
                <div class="tab-pane fade" id="pills-incidenciasCerradas" role="tabpanel" aria-labelledby="pills-incidenciasCerradas-tab">
                  <!-- Inicio formulario de consulta de incidencias cerradas -->
                  <form id="formIncidenciasCerradas" action="reportes.php?action=consultarIncidenciasCerradas" method="GET" class="bg-white w-full text-xs ">
                    <!-- Inputs y botones para filtrar incidencias -->
                    <div class="flex justify-center items-center mt-2">
                      <!-- Nombre de persona -->
                      <div class="w-full px-2 mb-2" style="max-width: 250px;">
                        <label for="usuarioIncidenciasCerradas" class="block mb-1 font-bold text-xs">Usuario de cierre:</label>
                        <select id="usuarioIncidenciasCerradas" name="usuarioIncidenciasCerradas" class="border p-2 w-full text-xs cursor-pointer">
                        </select>
                        <input type="hidden" id="codigoUsuarioIncidenciasCerradas" name="codigoUsuarioIncidenciasCerradas" readonly>
                        <input type="hidden" id="nombreUsuarioIncidenciasCerradas" name="nombreUsuarioIncidenciasCerradas" readonly>
                      </div>

                      <!-- Fecha de inicio -->
                      <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                        <label for="fechaInicioIncidenciasCerradas" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                        <input type="date" id="fechaInicioIncidenciasCerradas" name="fechaInicioIncidenciasCerradas" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                      </div>

                      <!-- Fecha de fin -->
                      <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                        <label for="fechaFinIncidenciasCerradas" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                        <input type="date" id="fechaFinIncidenciasCerradas" name="fechaFinIncidenciasCerradas" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                      </div>

                      <!-- Botones alineados horizontalmente -->
                      <div class="ml-5 flex space-x-2">
                        <!-- Botón de buscar -->
                        <button type="submit" id="filtrarListaIncidenciasCerradas" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-primary rounded-md flex justify-center items-center" title="Previsualizar reporte">
                          <i class="feather icon-filter"></i>
                        </button>

                        <!-- Botón de nueva consulta -->
                        <button type="button" id="limpiarCamposIncidenciasCerradas" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-secondary rounded-md flex justify-center items-center" title="Limpiar campos">
                          <i class="feather icon-refresh-cw"></i>
                        </button>

                        <!-- Boton generar reporte -->
                        <div class="btn-group mr-2">
                          <div class="flex justify-center space-x-2">
                            <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="feather mr-2 icon-file"></i>Reporte
                            </button>
                            <div class="dropdown-menu">
                              <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasCerradas">Todos las incidencias cerradas</div>
                              <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasCerradasFecha">Cierres por fechas</div>
                              <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasCerradasUsuario">Cierres por usuario</div>
                              <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasCerradasUsuarioFecha">Cierres por usuario y fechas</div>
                            </div>
                          </div>
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
                              <th scope="col" class="px-3 py-2 text-center">Usuario Cierre</th>
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
                                  <td class="px-3 py-2 text-center"><?= htmlspecialchars($cerradas['Usuario']) ?></td>
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
                                <td colspan="11" class="text-center py-3">No se encontraron registros de incidencias cerradas.</td>
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
                <!-- Fin de incidencias cerradas -->

                <!-- Tab Incidencias Asignadas -->
                <div class="tab-pane fade" id="pills-incidenciasAsignadas" role="tabpanel" aria-labelledby="pills-incidenciasAsignadas-tab">
                  <!-- Inicio formulario de consulta de incidencias asignadas -->
                  <form id="formIncidenciasAsignadas" action="reportes.php?action=consultarIncidenciasAsignadas" method="GET" class="bg-white w-full text-xs ">
                    <!-- Inputs y botones para filtrar incidencias -->
                    <div class="flex justify-center items-center mt-2">
                      <!-- Nombre de usuario asignado -->
                      <div class="w-full px-2 mb-2" style="max-width: 250px;">
                        <label for="usuarioIncidenciasAsignadas" class="block mb-1 font-bold text-xs">Usuario asignado:</label>
                        <select id="usuarioIncidenciasAsignadas" name="usuarioIncidenciasAsignadas" class="border p-2 w-full text-xs cursor-pointer">
                        </select>
                        <input type="hidden" id="codigoUsuarioIncidenciasAsignadas" name="codigoUsuarioIncidenciasAsignadas" readonly>
                        <input type="hidden" id="nombreUsuarioIncidenciasAsignadas" name="nombreUsuarioIncidenciasAsignadas" readonly>
                      </div>
                      <!-- Fecha de inicio -->
                      <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                        <label for="fechaInicioIncidenciasAsignadas" class="block mb-1 font-bold text-center text-xs">Fecha de asignaci&oacute;n de inicio:</label>
                        <input type="date" id="fechaInicioIncidenciasAsignadas" name="fechaInicioIncidenciasAsignadas" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                      </div>

                      <!-- Fecha de fin -->
                      <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                        <label for="fechaFinIncidenciasAsignadas" class="block mb-1 font-bold text-center text-xs">Fecha de asignaci&oacute;n final:</label>
                        <input type="date" id="fechaFinIncidenciasAsignadas" name="fechaFinIncidenciasAsignadas" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                      </div>

                      <!-- Botones alineados horizontalmente -->
                      <div class="ml-5 flex space-x-2">
                        <!-- Botón de buscar -->
                        <button type="submit" id="filtrarListaAsignaciones" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-primary rounded-md flex justify-center items-center" title="Previsualizar reporte">
                          <i class="feather icon-filter"></i> </button>
                        <!-- Botón de nueva consulta -->
                        <button type="button" id="limpiarCamposIncidenciasAsignadas" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-secondary rounded-md flex justify-center items-center" title="Limpiar campos">
                          <i class="feather icon-refresh-cw"></i>
                        </button>

                        <!-- Boton generar reporte -->
                        <div class="btn-group mr-2">
                          <div class="flex justify-center space-x-2">
                            <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="feather mr-2 icon-file"></i>Reporte
                            </button>
                            <div class="dropdown-menu">
                              <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasAsignadas">Todos las incidencias asignadas</div>
                              <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasAsignadasFecha">Asignaciones por fechas</div>
                              <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasAsignadasUsuario">Asignaciones por usuario</div>
                              <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasAsignadasUsuarioFecha">Asignaciones por usuario y fechas</div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Fin de botones alineados horizontalmente -->
                    </div>
                    <div class="relative sm:rounded-lg mt-2">
                      <div class="max-w-full overflow-hidden sm:rounded-lg">
                        <table id="tablaIncidenciasAsignadas" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                          <!-- Encabezado de la tabla -->
                          <thead class="text-xs text-gray-700 uppercase bg-orange-300">
                            <tr>
                              <th scope="col" class="px-3 py-2 text-center">&iacute;tem</th>
                              <th scope="col" class="px-6 py-2 hidden">Asignaci&oacute;n</th>
                              <th scope="col" class="px-6 py-2 hidden">Recepci&oacute;n</th>
                              <th scope="col" class="px-6 py-2 text-center">Incidencia</th>
                              <th scope="col" class="px-6 py-2 text-center">&Aacute;rea solicitante</th>
                              <th scope="col" class="px-6 py-2 text-center">Asunto</th>
                              <th scope="col" class="px-6 py-2 text-center">Equipo</th>
                              <th scope="col" class="px-6 py-2 text-center">Nombre del bien</th>
                              <th scope="col" class="px-6 py-2 text-center">Fecha de asignaci&oacute;n</th>
                              <th scope="col" class="px-6 py-2 text-center">Fecha de finalizaci&oacute;n</th>
                              <th scope="col" class="px-6 py-2 text-center">Usuario asignado</th>
                              <th scope="col" class="px-6 py-2 text-center">Tiempo de mantenimiento</th>
                              <th scope="col" class="px-6 py-2 text-center">Estado</th>
                            </tr>
                          </thead>
                          <!-- Fin de encabezado de la tabla -->

                          <!-- Cuerpo de la tabla -->
                          <tbody>
                            <?php if (!empty($resultadoIncidenciasAsignadas)): ?>
                              <?php $item = 1; // Iniciar contador para el ítem 
                              ?>
                              <?php foreach ($resultadoIncidenciasAsignadas as $asignadas): ?>
                                <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                  <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                  <td class='px-6 py-2 text-center'><?= $asignadas['INC_numero_formato']; ?></td>
                                  <td class='px-6 py-2 text-center'><?= $asignadas['ARE_nombre']; ?></td>
                                  <td class='px-6 py-2 text-center'><?= $asignadas['INC_asunto']; ?></td>
                                  <td class='px-6 py-2 text-center'><?= $asignadas['INC_codigoPatrimonial']; ?></td>
                                  <td class='px-6 py-2 text-center'><?= $asignadas['BIE_nombre']; ?></td>
                                  <td class='px-6 py-2 text-center'><?= $asignadas['fechaAsignacionFormateada']; ?></td>
                                  <td class='px-6 py-2 text-center'><?= $asignadas['fechaMantenimientoFormateada']; ?></td>
                                  <td class='px-6 py-2 text-center'><?= $asignadas['usuarioSoporte']; ?></td>
                                  <td class='px-6 py-2 text-center'><?= $asignadas['tiempoMantenimientoFormateado']; ?></td>
                                  <td class="px-3 py-2 text-center text-xs align-middle">
                                    <?php
                                    $estadoDescripcion = htmlspecialchars($asignadas['Estado']);
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
                                <td colspan="11" class="text-center py-3">No se encontraron registros de incidencias asignadas.</td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                          <!-- Fin de cuerpo de tabla -->
                        </table>
                      </div>
                    </div>
                    <!-- Fin de tabla de resultados de incidencias asignadas -->
                  </form>
                </div>
                <!-- Fin de incidencias asignadas -->
              </div>
            </div>

            <!-- Contenido de la segunda pestaña  - REPORTES DE DETALLE -->
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
                        <th scope="col" class="px-3 py-2 text-center hidden">INC_numero</th>
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

            <!-- Contenido de la cuarta pestaña - REPORTES POR EQUIPO O CODIGO PATRIMONIAL -->
            <div class="tab-pane fade" id="equipos" role="tabpanel" aria-labelledby="equipos-tab">
              <!-- Inicio formulario de consulta de incidencias asignadas -->
              <form id="formIncidenciasEquipos" action="reportes.php?action=consultarIncidenciasEquipos" method="GET" class="bg-white w-full text-xs ">
                <!-- Inputs y botones para filtrar incidencias -->
                <div class="flex justify-center items-center mt-2">
                  <!-- input código patrimonial -->
                  <div class="text-center w-full md:w-1/4 px-2 mb-2">
                    <label for="codigoPatrimonialEquipo" class="block mb-1 font-bold text-xs">C&oacute;digo Patrimonial:</label>
                    <input type="text" id="codigoPatrimonialEquipo" name="codigoPatrimonialEquipo" class="border p-2 w-full text-xs text-center rounded-md" maxlength="12" pattern="\d{1,12}" inputmode="numeric" title="Ingrese solo dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Ingrese c&oacute;digo patrimonial">
                  </div>

                  <!-- Fecha de inicio -->
                  <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                    <label for="fechaInicioIncidenciasEquipo" class="block mb-1 font-bold text-center text-xs">Fecha de inicio:</label>
                    <input type="date" id="fechaInicioIncidenciasEquipo" name="fechaInicioIncidenciasEquipo" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                  </div>

                  <!-- Fecha de fin -->
                  <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                    <label for="fechaFinIncidenciasEquipo" class="block mb-1 font-bold text-center text-xs">Fecha fin:</label>
                    <input type="date" id="fechaFinIncidenciasEquipo" name="fechaFinIncidenciasEquipo" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                  </div>

                  <!-- Botones alineados horizontalmente -->
                  <div class="ml-5 flex space-x-2">
                    <!-- Botón de buscar -->
                    <button type="submit" id="filtrarListaIncidenciasEquipo" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-primary rounded-md flex justify-center items-center" title="Previsualizar reporte">
                      <i class="feather icon-filter"></i> </button>
                    <!-- Botón de nueva consulta -->
                    <button type="button" id="limpiarCamposIncidenciasEquipo" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-secondary rounded-md flex justify-center items-center" title="Limpiar campos">
                      <i class="feather icon-refresh-cw"></i>
                    </button>

                    <!-- Boton generar reporte -->
                    <div class="btn-group mr-2">
                      <div class="flex justify-center space-x-2">
                        <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="feather mr-2 icon-file"></i>Reporte
                        </button>
                        <div class="dropdown-menu">
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteTotalEquipos">Todas las incidencias</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEquiposPorFecha">Reporte por fechas</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEquiposPorCodigoPatrimonial">Reporte por equipo</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEquiposPorCodPatrimonialFecha">Reporte por equipo y fechas</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Fin de botones alineados horizontalmente -->
                </div>
                <!-- input tipo de bien (más largo) -->
                <div class="flex justify-center items-center text-center">
                  <input type="text" id="tipoBienEquipo" name="tipoBienEquipo" class="border p-2 w-1/2 text-xs text-center rounded-md" disabled readonly placeholder="Nombre del bien">
                </div>
                <!-- Fin de input tipo de bien -->

                <div class="relative sm:rounded-lg mt-2">
                  <div class="max-w-full overflow-hidden sm:rounded-lg">
                    <table id="tablaIncidenciasEquipos" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                      <!-- Encabezado de la tabla -->
                      <thead class="text-xs text-gray-700 uppercase bg-sky-300">
                        <tr>
                          <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                          <th scope="col" class="px-3 py-2 text-center">Incidencia</th>
                          <th scope="col" class="px-3 py-2 text-center hidden">Area</th>
                          <th scope="col" class="px-3 py-2 text-center">Fecha Incidencia</th>
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

                      <!-- Cuerpo de la tabla -->
                      <tbody>
                        <?php $item = 1; // Iniciar contador para el ítem 
                        ?>
                        <?php foreach ($resultadoIncidenciasEquipos as $totales): ?>
                          <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                            <td class="px-3 py-2 text-center"><?= $item++ ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_numero_formato']) ?></td>
                            <td class="px-3 py-2 text-center hidden"><?= htmlspecialchars($totales['ARE_nombre']) ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['fechaIncidenciaFormateada']); ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_asunto']); ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_documento']); ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_codigoPatrimonial']); ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['BIE_nombre']); ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['PRI_nombre']); ?></td>
                            <td class="px-3 py-2 text-center text-xs align-middle">
                              <?php
                              $condicionDescripcion = htmlspecialchars($totales['CON_descripcion']);
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
                        <?php if (empty($resultadoIncidenciasEquipos)): ?>
                          <tr>
                            <td colspan="10" class="text-center py-3">No se encontraron registros de incidencias.</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                      <!-- Fin de cuerpo de tabla -->
                    </table>
                  </div>
                </div>
                <!-- Fin de tabla de resultados de incidencias asignadas -->
              </form>
            </div>
            <!-- Fin del contenido de la tercera pestaña -->

            <!-- Contenido de la cuarta pestaña - REPORTES POR AREAS -->
            <div class="tab-pane fade" id="areas" role="tabpanel" aria-labelledby="areas-tab">
              <!-- Inicio formulario de consulta de incidencias asignadas -->
              <form id="formIncidenciasAreas" action="reportes.php?action=consultarIncidenciasAreas" method="GET" class="bg-white w-full text-xs ">
                <!-- Inputs y botones para filtrar incidencias -->
                <div class="flex justify-center items-center mt-2">
                  <!-- input código patrimonial -->
                  <div class="text-center w-full md:w-1/4 px-2 mb-2">
                    <label for="areaIncidencia" class="block mb-1 font-bold text-xs">&Aacute;rea seleccionada:</label>
                    <select id="areaIncidencia" name="areaIncidencia" class="border p-2 w-full text-xs cursor-pointer">
                    </select>
                    <input type="hidden" id="codigoArea" name="codigoArea" readonly>
                    <input type="hidden" id="nombreArea" name="nombreArea" readonly>
                  </div>

                  <!-- Fecha de inicio -->
                  <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                    <label for="fechaInicioIncidenciasArea" class="block mb-1 font-bold text-center text-xs">Fecha de inicio:</label>
                    <input type="date" id="fechaInicioIncidenciasArea" name="fechaInicioIncidenciasArea" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                  </div>

                  <!-- Fecha de fin -->
                  <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                    <label for="fechaFinIncidenciasArea" class="block mb-1 font-bold text-center text-xs">Fecha fin:</label>
                    <input type="date" id="fechaFinIncidenciasArea" name="fechaFinIncidenciasArea" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                  </div>

                  <!-- Botones alineados horizontalmente -->
                  <div class="ml-5 flex space-x-2">
                    <!-- Botón de buscar -->
                    <button type="submit" id="filtrarListaIncidenciasArea" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-primary rounded-md flex justify-center items-center" title="Previsualizar reporte">
                      <i class="feather icon-filter"></i> </button>
                    <!-- Botón de nueva consulta -->
                    <button type="button" id="limpiarCamposIncidenciasArea" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-secondary rounded-md flex justify-center items-center" title="Limpiar campos">
                      <i class="feather icon-refresh-cw"></i>
                    </button>

                    <!-- Boton generar reporte -->
                    <div class="btn-group mr-2">
                      <div class="flex justify-center space-x-2">
                        <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="feather mr-2 icon-file"></i>Reporte
                        </button>
                        <div class="dropdown-menu">
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteTotalAreas">Todas las incidencias</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteAreaFecha">Reporte por fechas</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasArea">Reporte por &aacute;rea</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteIncidenciasAreaFecha">Reporte por &aacute;rea y fecha</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Fin de botones alineados horizontalmente -->
                </div>

                <div class="relative sm:rounded-lg mt-2">
                  <div class="max-w-full overflow-hidden sm:rounded-lg">
                    <table id="tablaIncidenciasArea" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                      <!-- Encabezado de la tabla -->
                      <thead class="text-xs text-gray-700 uppercase bg-teal-200">
                        <tr>
                          <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                          <th scope="col" class="px-3 py-2 text-center">Incidencia</th>
                          <th scope="col" class="px-3 py-2 text-center hidden">Area</th>
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

                      <!-- Cuerpo de la tabla -->
                      <tbody>
                        <?php $item = 1; // Iniciar contador para el ítem 
                        ?>
                        <?php foreach ($resultadoIncidenciasAreas as $totales): ?>
                          <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                            <td class="px-3 py-2 text-center"><?= $item++ ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_numero_formato']) ?></td>
                            <td class="px-3 py-2 text-center hidden"><?= htmlspecialchars($totales['ARE_nombre']) ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['fechaIncidenciaFormateada']); ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_asunto']); ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_documento']); ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['INC_codigoPatrimonial']); ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['BIE_nombre']); ?></td>
                            <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['PRI_nombre']); ?></td>
                            <td class="px-3 py-2 text-center text-xs align-middle">
                              <?php
                              $condicionDescripcion = htmlspecialchars($totales['CON_descripcion']);
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
                        <?php if (empty($resultadoIncidenciasAreas)): ?>
                          <tr>
                            <td colspan="10" class="text-center py-3">No se encontraron registros de incidencias.</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                      <!-- Fin de cuerpo de tabla -->
                    </table>
                  </div>
                </div>
                <!-- Fin de tabla de resultados de incidencias asignadas -->
              </form>
            </div>
            <!-- Fin del contenido de la tercera pestaña -->

            <!-- Contenido de la tercera pestaña - REPORTES OTROS -->
            <div class="tab-pane fade" id="otros" role="tabpanel" aria-labelledby="otros-tab">
              <div class="col-sm-20">
                <!-- <div class="card"> -->
                <div class="card-body">
                  <div class="row">
                    <!-- Pestañas verticales -->
                    <div class="col-md-2 col-sm-10">
                      <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <li><a class="nav-link text-left active" id="v-pills-area-tab" data-toggle="pill" href="#v-pills-area" role="tab" aria-controls="v-pills-area" aria-selected="false">&Aacute;reas con m&aacute;s incidencias</a></li>
                        <li><a class="nav-link text-left" id="v-pills-codPatrimonial-tab" data-toggle="pill" href="#v-pills-codPatrimonial" role="tab" aria-controls="v-pills-codPatrimonial" aria-selected="false">Equipos m&aacute;s afectados</a></li>
                        <li><a class="nav-link text-left" id="v-pills-graficos-tab" data-toggle="pill" href="#v-pills-graficos" role="tab" aria-controls="v-pills-graficos" aria-selected="false">Gr&aacute;ficos</a></li>
                      </ul>
                    </div>
                    <!-- Fin de pestañas verticales -->

                    <!-- Contenido de las pestañas -->
                    <div class="col-md-10 col-sm-18">
                      <div class="tab-content" id="v-pills-tabContent">
                        <!-- Contenido de la primera pestaña -->
                        <div class="tab-pane fade show active" id="v-pills-area" role="tabpanel" aria-labelledby="v-pills-area-tab">
                          <form id="formAreasMasAfectadas" action="reportes.php?action=consultarAreasMasAfectadas" method="GET" class="bg-white w-full text-xs ">

                            <!-- Inputs y botones para filtrar incidencias -->
                            <div class="flex justify-center items-center mt-2">
                              <!-- Nombre de categoria seleccionada -->
                              <div class="w-full px-2 mb-2" style="max-width: 250px;">
                                <label for="categoriaSeleccionada" class="block mb-1 font-bold text-xs">Categor&iacute;a seleccionada:</label>
                                <select id="categoriaSeleccionada" name="categoriaSeleccionada" class="border p-2 w-full text-xs cursor-pointer">
                                </select>
                                <input type="hidden" id="codigoCategoriaSeleccionada" name="codigoCategoriaSeleccionada" readonly>
                                <input type="hidden" id="nombreCategoriaSeleccionada" name="nombreCategoriaSeleccionada" readonly>
                              </div>
                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 ml-5 px-2 mb-2">
                                <label for="fechaInicioAreaMasAfectada" class="block mb-1 font-bold text-center text-xs">Fecha de inicio:</label>
                                <input type="date" id="fechaInicioAreaMasAfectada" name="fechaInicioAreaMasAfectada" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinAreaMasAfectada" class="block mb-1 font-bold text-center text-xs">Fecha fin:</label>
                                <input type="date" id="fechaFinAreaMasAfectada" name="fechaFinAreaMasAfectada" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAreaMasAfectadas" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-primary rounded-md flex justify-center items-center" title="Previsualizar reporte">
                                  <i class="feather icon-filter"></i> </button>
                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposAreasMasAfectadas" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-secondary rounded-md flex justify-center items-center" title="Limpiar campos">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group mr-2">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>Reporte
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteAreaMasIncidencias">Todas las &aacute;reas afectadas</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteAreaMasIncidenciasFecha">Reporte por fecha</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteAreaMasIncidenciasCategoria">Reporte por categor&iacute;a</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteAreaMasIncidenciasCategoriaFecha">Reporte por categor&iacute;a y fecha</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Tabla de resultados las areas mas afectadas -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaAreasMasAfectadas" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                                    <tr>
                                      <th scope="col" class="px-1 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-5 py-2 text-center">&Aacute;rea afectada</th>
                                      <th scope="col" class="px-1 py-2 text-center">Total de incidencias</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->
                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php $item = 1; // Iniciar contador para el ítem 
                                    ?>
                                    <?php foreach ($resultadoAreaMasAfectadas as $areasAfectadas): ?>
                                      <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                        <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                        <td class="px-3 py-2 text-center"><?= htmlspecialchars($areasAfectadas['areaMasIncidencia']) ?></td>
                                        <td class="px-3 py-2 text-center"><?= htmlspecialchars($areasAfectadas['cantidadIncidencias']) ?></td>
                                      </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($resultadoAreaMasAfectadas)): ?>
                                      <tr>
                                        <td colspan="3" class="text-center py-3">No se encontraron registros de incidencias.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                  <!-- Fin de cuerpo de tabla -->
                                </table>
                              </div>
                            </div>
                            <!-- Fin de tabla de resultados de las areas mas afectadas -->
                          </form>
                        </div>
                        <!-- Fin de contenido de la primera pestaña -->

                        <!-- Contenido de la segunda pestaña -->
                        <div class="tab-pane fade" id="v-pills-codPatrimonial" role="tabpanel" aria-labelledby="v-pills-codPatrimonial-tab">
                          <form id="formEquiposMasAfectados" action="reportes.php?action=consultarEquiposMasAfectados" method="GET" class="bg-white w-full text-xs ">
                            <!-- Inputs y botones para filtrar incidencias -->
                            <div class="flex justify-center items-center mt-2">
                              <!-- input código patrimonial -->
                              <div class="text-center w-full md:w-1/4 px-2 mb-2">
                                <label for="codigoEquipo" class="block mb-1 font-bold text-xs">C&oacute;digo Patrimonial:</label>
                                <input type="text" id="codigoEquipo" name="codigoEquipo" class="border p-2 w-full text-xs text-center rounded-md" maxlength="12" pattern="\d{1,12}" inputmode="numeric" title="Ingrese solo dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Ingrese c&oacute;digo patrimonial">
                              </div>

                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicioIncidenciasEquipos" class="block mb-1 font-bold text-center text-xs">Fecha de inicio:</label>
                                <input type="date" id="fechaInicioIncidenciasEquipos" name="fechaInicioIncidenciasEquipos" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinIncidenciasEquipos" class="block mb-1 font-bold text-center text-xs">Fecha fin:</label>
                                <input type="date" id="fechaFinIncidenciasEquipos" name="fechaFinIncidenciasEquipos" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaIncidenciasEquipos" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-primary rounded-md flex justify-center items-center" title="Previsualizar reporte">
                                  <i class="feather icon-filter"></i> </button>
                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposIncidenciasEquipos" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-secondary rounded-md flex justify-center items-center" title="Limpiar campos">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group mr-2">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>Reporte
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEquiposAfectados">Todos las incidencias</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEquiposAfectadosFecha">Reporte por fecha</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEquiposAfectadosCodigoPatrimonial">Reporte por equipo</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEquiposAfectadosCodigoPatrimonialFecha">Reporte por equipo y fecha</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>
                            <!-- input tipo de bien (más largo) -->
                            <div class="flex justify-center items-center text-center mt-2">
                              <input type="text" id="tipoBienEquiposAfectados" name="tipoBienEquiposAfectados" class="border p-2 w-3/4 text-xs text-center rounded-md" disabled readonly placeholder="Nombre del bien">
                            </div>

                            <!-- Tabla de resultados los equipos mas afectadas -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaEquiposMasAfectados" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                                    <tr>
                                      <th scope="col" class="px-1 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-3 py-2 text-center">C&oacute;digo Patrimonial</th>
                                      <th scope="col" class="px-5 py-2 text-center">Nombre del bien</th>
                                      <th scope="col" class="px-5 py-2 text-center">&Aacute;rea afectada</th>
                                      <th scope="col" class="px-1 py-2 text-center">Total incidencias</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->
                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php $item = 1; // Iniciar contador para el ítem 
                                    ?>
                                    <?php foreach ($resultadoEquiposMasAfectados as $equiposAfectados): ?>
                                      <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                        <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                        <td class="px-3 py-2 text-center"><?= htmlspecialchars($equiposAfectados['codigoPatrimonial']) ?></td>
                                        <td class="px-3 py-2 text-center"><?= htmlspecialchars($equiposAfectados['nombreBien']) ?></td>
                                        <td class="px-3 py-2 text-center"><?= htmlspecialchars($equiposAfectados['nombreArea']) ?></td>
                                        <td class="px-3 py-2 text-center"><?= htmlspecialchars($equiposAfectados['cantidadIncidencias']) ?></td>
                                      </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($resultadoEquiposMasAfectados)): ?>
                                      <tr>
                                        <td colspan="5" class="text-center py-3">No se encontraron registros de incidencias.</td>
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
                        <!-- Fin de contenido de la segunda pestaña -->

                        <!-- Contenido de la tercera pestaña -->
                        <div class="tab-pane fade" id="v-pills-graficos" role="tabpanel" aria-labelledby="v-pills-graficos-tab">
                          <form id="formGraficos" action="reportes.php?action=graficos" method="GET" class="bg-white w-full text-xs ">
                            <!-- Widget del grafico -->
                            <div id="grafico" class="w-full">
                              <div class="card support-bar overflow-hidden w-full">
                                <div class="card-body w-full flex flex-col items-start">
                                  <!-- Título principal -->
                                  <span class="text-c-blue text-3xl font-bold mb-3">INCIDENCIAS ANUALES</span>

                                  <!-- Subtítulos -->
                                  <div class="flex items-center justify-between w-full">
                                    <!-- Selector de año -->
                                    <div class="flex items-center">
                                      <p class="mb-3 mt-2 flex items-center">
                                      <p class="mr-2">Incidencias en el año</p> <!-- Aumentar margen derecho -->
                                      <select id="anioSeleccionado" name="anioSeleccionado" class="p-2 text-xs cursor-pointer ml-2 border-0"> <!-- Aumentar margen izquierdo -->
                                      </select>
                                      </p>
                                    </div>

                                    <!-- Total de incidencias -->
                                    <p class="mb-3 mt-3" id="totalIncidencias">Total de incidencias: </p>
                                  </div>
                                </div>

                                <!-- Grafica -->
                                <div id="support-chart_report"></div>

                                <!-- etiquetas inferiores del gráfico -->
                                <div class="card-footer bg-primary text-white">
                                  <div class="row text-center">
                                    <div class="col">
                                      <h4 class="m-0 text-white font-bold"><?php echo $cantidades['incidencias_enero']; ?></h4>
                                      <span>Enero</span>
                                    </div>
                                    <div class="col">
                                      <h4 class="m-0 text-white font-bold"><?php echo $cantidades['incidencias_febrero']; ?></h4>
                                      <span>Febrero</span>
                                    </div>
                                    <div class="col">
                                      <h4 class="m-0 text-white font-bold"><?php echo $cantidades['incidencias_marzo']; ?></h4>
                                      <span>Marzo</span>
                                    </div>
                                    <div class="col">
                                      <h4 class="m-0 text-white font-bold"><?php echo $cantidades['incidencias_abril']; ?></h4>
                                      <span>Abril</span>
                                    </div>
                                    <div class="col">
                                      <h4 class="m-0 text-white font-bold"><?php echo $cantidades['incidencias_mayo']; ?></h4>
                                      <span>Mayo</span>
                                    </div>
                                    <div class="col">
                                      <h4 class="m-0 text-white font-bold"><?php echo $cantidades['incidencias_junio']; ?></h4>
                                      <span>Junio</span>
                                    </div>
                                    <div class="col">
                                      <h4 class="m-0 text-white font-bold"><?php echo $cantidades['incidencias_julio']; ?></h4>
                                      <span>Julio</span>
                                    </div>
                                    <div class="col">
                                      <h4 class="m-0 text-white font-bold"><?php echo $cantidades['incidencias_agosto']; ?></h4>
                                      <span>Agosto</span>
                                    </div>
                                    <div class="col">
                                      <h4 class="m-0 text-white font-bold"><?php echo $cantidades['incidencias_setiembre']; ?></h4>
                                      <span>Setiembre</span>
                                    </div>
                                    <div class="col">
                                      <h4 class="m-0 text-white font-bold"><?php echo $cantidades['incidencias_octubre']; ?></h4>
                                      <span>Octubre</span>
                                    </div>
                                    <div class="col">
                                      <h4 class="m-0 text-white font-bold"><?php echo $cantidades['incidencias_noviembre']; ?></h4>
                                      <span>Noviembre</span>
                                    </div>
                                    <div class="col">
                                      <h4 class="m-0 text-white font-bold"><?php echo $cantidades['incidencias_diciembre']; ?></h4>
                                      <span>Diciembre</span>
                                    </div>
                                  </div>
                                </div>
                                <!-- fin de etiquetas inferiores del gráfico -->
                              </div>

                              <!-- Boton para generar reporte -->
                              <div class="flex justify-center space-x-2">
                                <button type="button" id="reporteGrafica" class="bn h-10 btn-secondary text-xs text-white font-bold py-2 px-3  rounded-md"> <i class="feather mr-2 icon-file"></i>Generar reporte</button>
                              </div>
                            </div>
                            <!-- Fin del widget del grafico -->

                            <!-- Tarjetas de las cantidades  -->
                            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

                            <script>
                              // Pasar datos de PHP a JavaScript
                              var incidenciasPorMes = <?php echo json_encode([
                                                        (int)$cantidades['incidencias_enero'],
                                                        (int)$cantidades['incidencias_febrero'],
                                                        (int)$cantidades['incidencias_marzo'],
                                                        (int)$cantidades['incidencias_abril'],
                                                        (int)$cantidades['incidencias_mayo'],
                                                        (int)$cantidades['incidencias_junio'],
                                                        (int)$cantidades['incidencias_julio'],
                                                        (int)$cantidades['incidencias_agosto'],
                                                        (int)$cantidades['incidencias_setiembre'],
                                                        (int)$cantidades['incidencias_octubre'],
                                                        (int)$cantidades['incidencias_noviembre'],
                                                        (int)$cantidades['incidencias_diciembre']
                                                      ]); ?>;
                            </script>
                            <!-- Fin de las tarjetas de las cantidades -->
                          </form>
                        </div>
                        <!-- Fin del contenido de la tercera pestaña -->
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
<script src="./app/View/func/ReportesIncidencias/ReportesOtros/Graficos/func_reportesGraficos.js"></script>