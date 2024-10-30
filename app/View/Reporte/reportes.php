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
              <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Incidencias totales</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Pendientes de cierre</a>
                </li>
              </ul>
              <div class="tab-content" id="pills-tabContent">

                <!-- Primer tab-->
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                  <!-- Boton generar reporte -->
                  <div class="flex justify-center space-x-2">
                    <button type="button" id="reporte-incidencias-totales" class="bn bg-red-400 text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-file"></i>Generar reporte</button>
                  </div>
                  <!-- Fin de boton de generar reporte -->

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
                                <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['ESTADO']) ?></td>
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
                </div>
                <!-- Fin de primer tab -->

                <!-- Segundo tab -->
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                  <div class="flex justify-center space-x-2">
                    <button type="button" id="reporte-pendientes-cierre" class="bn bg-red-400 text-xs text-white font-bold py-2 px-3 rounded-md"> <i class="feather mr-2 icon-file"></i>Generar reporte</button>
                  </div>

                  <!-- Tabla de resultados de incidencias pendientes de cierre -->
                  <div class="relative sm:rounded-lg mt-2">
                    <div class="max-w-full overflow-hidden sm:rounded-lg">
                      <table id="tablaIncidenciasTotales" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
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
                                <td class="px-3 py-2 text-center"><?= htmlspecialchars($pendientes['ESTADO']) ?></td>
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

                </div>
                <!--Fin de segundo tab -->
              </div>
            </div>
            <!-- Fin de contenido de la primera pestaña -->

            <!-- Contenido de la segunda pestaña -->
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
                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['ESTADO']); ?></td>
                          <td class="px-6 py-3 text-center align-middle flex space-x-2"> <!-- Columna de Acción con botones -->
                            <!-- Botón de Imprimir detalle de incidencia -->
                            <button type="button" id="imprimir-incidencia" class="bn btn-warning text-xs text-white font-bold py-2 px-3 rounded-md flex items-center justify-center" title="Detalle de incidencia">
                              <i class="feather icon-file"></i>
                            </button>

                            <!-- Botón de imprimir detalle de cierre -->
                            <button type="button" id="imprimir-cierre" class="bn bg-blue-400 text-xs text-white font-bold py-2 px-3 rounded-md flex items-center justify-center" title="Detalle de cierre">
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
                        <li><a class="nav-link text-left active" id="v-pills-totales-tab" data-toggle="pill" href="#v-pills-totales" role="tab" aria-controls="v-pills-totales" aria-selected="true">Totales</a></li>
                        <li><a class="nav-link text-left" id="v-pills-cerradas-tab" data-toggle="pill" href="#v-pills-cerradas" role="tab" aria-controls="v-pills-cerradas" aria-selected="false">Cerradas</a></li>
                        <li><a class="nav-link text-left" id="v-pills-area-tab" data-toggle="pill" href="#v-pills-area" role="tab" aria-controls="v-pills-area" aria-selected="false">&Aacute;rea</a></li>
                        <li><a class="nav-link text-left" id="v-pills-codPatrimonial-tab" data-toggle="pill" href="#v-pills-codPatrimonial" role="tab" aria-controls="v-pills-codPatrimonial" aria-selected="false">C&oacute;d. Patrimonial</a></li>
                      </ul>
                    </div>
                    <!-- Fin de pestañas verticales -->

                    <!-- Contenido de las pestañas -->
                    <div class="col-md-10 col-sm-18">
                      <div class="tab-content" id="v-pills-tabContent">

                        <!-- Contenido de la primera pestaña -->
                        <div class="tab-pane fade show active" id="v-pills-totales" role="tabpanel" aria-labelledby="v-pills-totales-tab">
                          <!-- Inicio de formulario de registros de incidencias de auditoría -->
                          <form id="formAuditoriaIncidencias" action="auditoria.php?action=listarRegistrosIncidencias" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicio" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicio_registro_incidencias" name="fechaInicio" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFin" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFin_registro_incidencias" name="fechaFin" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaIncidencias" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-red-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-search"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCampos_registro_incidencias" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-500 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Botón de reporte -->
                                <button type="button" id="limpiarCampos_registro_incidencias" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-orange-500 rounded-md flex justify-center items-center">
                                  <i class="feather icon-file"></i>
                                </button>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de inicios de sesion -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden">
                                <table id="tablaIncidenciasRegistradas" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-red-300">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">&iacute;tem</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora de registro</th>
                                      <th scope="col" class="px-3 py-2 text-center">Rol</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre Completo</th>
                                      <th scope="col" class="px-3 py-2 text-center">&Aacute;rea</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoAuditoriaRegistroIncidencias)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoAuditoriaRegistroIncidencias as $incidencias): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['ROL_nombre']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['USU_nombre']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['NombreCompleto']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['ARE_nombre']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="8" class="text-center py-3">No se encontraron registros de incidencias.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                  <!-- Fin de cuerpo de tabla -->
                                </table>
                              </div>
                            </div>
                            <!-- Fin de tabla de inicios de sesion -->
                          </form>
                          <!-- Fin de formulario de registros de incidencias de auditoría -->
                        </div>
                        <!-- Fin de contenido de la primera pestaña -->

                        <!-- Contenido de la segunda pestaña -->
                        <div class="tab-pane fade" id="v-pills-cerradas" role="tabpanel" aria-labelledby="v-pills-cerradas-tab">
                          <!-- Inicio de formulario de registros de recepciones de auditoría -->
                          <form id="formaAuditoriaRecepciones" action="auditoria.php?action=listarRegistrosRecepciones" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicio" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicio" name="fechaInicio" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFin" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFin" name="fechaFin" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaRecepciones" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-red-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-search"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCampos" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-500 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de registros de recepciones -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden">
                                <table id="tablaRecepciones" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-red-300">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">&iacute;tem</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora de registro</th>
                                      <th scope="col" class="px-3 py-2 text-center">Rol</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre Completo</th>
                                      <th scope="col" class="px-3 py-2 text-center">&Aacute;rea</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoAuditoriaRegistroRecepciones)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoAuditoriaRegistroRecepciones as $recepcion): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepcion['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepcion['ROL_nombre']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepcion['USU_nombre']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepcion['NombreCompleto']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepcion['ARE_nombre']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepcion['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepcion['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="10" class="text-center py-3">No se encontraron incidencias recepcionadas.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                  <!-- Fin de cuerpo de tabla -->
                                </table>
                              </div>
                            </div>
                            <!-- Fin de tabla de registros de recepciones -->
                          </form>
                          <!-- Fin de formulario de registros de recepciones de auditoría -->
                        </div>
                        <!-- Fin de contenido de la segunda pestaña -->

                        <!-- Contenido de la tercera pestaña -->
                        <div class="tab-pane fade" id="v-pills-area" role="tabpanel" aria-labelledby="v-pills-area-tab">
                          <div class="flex justify-center space-x-4">
                            <!-- Buscar por área -->
                            <div class="text-center w-full md:w-3/4">
                              <select id="area" name="area" class="border p-2 w-full text-xs cursor-pointer">
                              </select>
                              <input type="hidden" id="codigoArea" name="codigoArea" readonly>
                              <input type="hidden" id="nombreArea" name="nombreArea" readonly>
                            </div>

                            <!-- Botones -->
                            <div class="text-center w-full md:w-1/4">
                              <button type="button" id="reportes-areas" class="bn btn-primary text-xs text-white font-bold p-2 rounded-md">
                                <i class="feather mr-2 icon-printer"></i>Generar reporte
                              </button>
                            </div>
                          </div>

                        </div>
                        <!-- Fin de contenido de la tercera pestaña -->

                        <!-- Contenido de la cuarta pestaña -->
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
                        <!-- Fin de contenido de la cuarta pestaña -->
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

</script>