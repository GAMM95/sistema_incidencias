<div class="pcoded-main-container h-screen flex flex-col mt-5">
  <div class="pcoded-content flex flex-col grow">
    <!-- Inicio de breadcrumb -->
    <div class="page-header flex-none">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h1 class="text-2xl font-bold mb-2">Auditor&iacute;a</h1>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item">
                <a href=""><i class="feather icon-list"></i></a>
              </li>
              <li class="breadcrumb-item">
                <a href="auditoria.php">Reportes de auditor&iacute;a</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de breadcrumb -->

    <!-- Inicio del tab pane -->
    <div class="h-full flex flex-col grow mb-0">
      <div class="card grow">
        <div class="card-body flex flex-col grow">
          <!-- Inicio de titulos de las pestañas -->
          <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active text-uppercase" id="totales-tab" data-toggle="tab" href="#totales" role="tab" aria-controls="totales" aria-selected="true">Todos los eventos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">Eventos de login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="registros-tab" data-toggle="tab" href="#registros" role="tab" aria-controls="registros" aria-selected="false">Eventos de Incidencias</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="mantenedor-tab" data-toggle="tab" href="#mantenedor" role="tab" aria-controls="mantenedor" aria-selected="false">Eventos de Mantenedores</a>
            </li>
          </ul>
          <!-- Fin de los titulos de las pestañas -->

          <!-- Contenido de las pestañas -->
          <div class="tab-content grow" id="myTabContent">

            <!-- Eventos totales - Auditoria -->
            <div class="tab-pane fade show active" id="totales" role="tabpanel" aria-labelledby="totales-tab">
              <!-- Inicio de formulario de inicio de sesion de auditoría -->
              <form id="formEventosTotales" action="auditoria.php?action=consultarEventosTotales" method="GET" class="bg-white w-full text-xs ">
                <div class="flex justify-center items-center">
                  <!-- Nombre de persona -->
                  <div class="w-full px-2 mb-2" style="max-width: 250px;">
                    <label for="personaEventosTotales" class="block mb-1 font-bold text-xs">Usuario:</label>
                    <select id="personaEventosTotales" name="personaEventosTotales" class="border p-2 w-full text-xs cursor-pointer">
                    </select>
                    <input type="hidden" id="codigoUsuarioEventosTotales" name="codigoUsuarioEventosTotales" readonly>
                    <input type="hidden" id="nombreUsuarioEventosTotales" name="nombreUsuarioEventosTotales" readonly>
                  </div>

                  <!-- Fecha de inicio -->
                  <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                    <label for="fechaInicioEventosTotales" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                    <input type="date" id="fechaInicioEventosTotales" name="fechaInicioEventosTotales" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                  </div>

                  <!-- Fecha de fin -->
                  <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                    <label for="fechaFinEventosTotales" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                    <input type="date" id="fechaFinEventosTotales" name="fechaFinEventosTotales" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                  </div>

                  <!-- Botones alineados horizontalmente -->
                  <div class="ml-5 flex space-x-2">
                    <!-- Botón de filtrar -->
                    <button type="submit" id="filtrarListaEventosTotales" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-primary rounded-md flex justify-center items-center" title="Previsualizar reporte">
                      <i class="feather icon-filter"></i>
                    </button>

                    <!-- Botón de nueva consulta -->
                    <button type="button" id="limpiarCamposEventosTotales" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 bg-gray-500 rounded-md flex justify-center items-center">
                      <i class="feather icon-refresh-cw"></i>
                    </button>

                    <!-- Boton generar reporte -->
                    <div class="btn-group mr-2">
                      <div class="flex justify-center space-x-2">
                        <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="feather mr-2 icon-file"></i>Reporte
                        </button>
                        <div class="dropdown-menu">
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporte-eventos-totales">Reporte total de eventos</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosTotalesFecha">Reporte por fechas</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosTotalesUsuario">Reporte por usuario</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosTotalesUsuarioFecha">Reporte por usuario y fechas</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Tabla de eventos totales - Auditoria -->
                <div class="relative sm:rounded-lg mt-2">
                  <div class="max-w-full overflow-hidden sm:rounded-lg">
                    <table id="tablaEventosTotales" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                      <!-- Encabezado de la tabla -->
                      <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                          <th scope="col" class="px-3 py-2 text-center">Fecha y Hora del evento </th>
                          <th scope="col" class="px-3 py-2 text-center">Evento</th>
                          <th scope="col" class="px-3 py-2 text-center">Nombre Completo</th>
                          <th scope="col" class="px-3 py-2 text-center">Rol</th>
                          <th scope="col" class="px-3 py-2 text-center">&Aacute;rea</th>
                          <th scope="col" class="px-3 py-2 text-center">IP</th>
                          <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                        </tr>
                      </thead>
                      <!-- Fin de encabezado de la tabla -->

                      <!-- Cuerpo de la tabla -->
                      <tbody>
                        <?php if (!empty($resultadoEventosTotales)): ?>
                          <?php $item = 1; // Iniciar contador para el ítem 
                          ?>
                          <?php foreach ($resultadoEventosTotales as $totales): ?>
                            <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                              <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['fechaFormateada']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['AUD_operacion']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['NombreCompleto']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['ROL_nombre']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['ARE_nombre']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['AUD_ip']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['AUD_nombreEquipo']) ?></td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="9" class="text-center py-3">No se ha registrado ning&uacute;n evento.</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                      <!-- Fin de cuerpo de tabla -->
                    </table>
                  </div>
                </div>
              </form>
            </div>
            <!-- Fin de eventos totales - Auditoria -->

            <!-- Eventos de login - Auditoria -->
            <div class="tab-pane fade" id="login" role="tabpanel" aria-labelledby="login-tab">
              <!-- Inicio de formulario de inicio de sesion de auditoría -->
              <form id="formAuditoriaLogin" action="auditoria.php?action=consultarEventosLogin" method="GET" class="bg-white w-full text-xs ">
                <div class="flex justify-center items-center">
                  <!-- Nombre de persona -->
                  <div class="w-full px-2 mb-2" style="max-width: 250px;">
                    <label for="usuarioEventosLogin" class="block mb-1 font-bold text-xs">Usuario:</label>
                    <select id="usuarioEventosLogin" name="usuarioEventosLogin" class="border p-2 w-full text-xs cursor-pointer">
                    </select>
                    <input type="hidden" id="codigoUsuarioEventosLogin" name="codigoUsuarioEventosLogin" readonly>
                    <input type="hidden" id="nombreUsuarioEventosLogin" name="nombreUsuarioEventosLogin" readonly>
                  </div>

                  <!-- Fecha de inicio -->
                  <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                    <label for="fechaInicioEventosLogin" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                    <input type="date" id="fechaInicioEventosLogin" name="fechaInicioEventosLogin" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                  </div>

                  <!-- Fecha de fin -->
                  <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                    <label for="fechaFinEventosLogin" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                    <input type="date" id="fechaFinEventosLogin" name="fechaFinEventosLogin" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                  </div>

                  <!-- Botones alineados horizontalmente -->
                  <div class="ml-5 flex space-x-2">
                    <!-- Botón de filtrar -->
                    <button type="submit" id="filtrarListaEventosLogin" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 btn-primary rounded-md flex justify-center items-center">
                      <i class="feather icon-filter"></i>
                    </button>

                    <!-- Botón de nueva consulta -->
                    <button type="button" id="limpiarCamposEventosLogin" class="h-10 w-12 text-xs text-white font-bold py-2 px-3 bg-gray-500 rounded-md flex justify-center items-center">
                      <i class="feather icon-refresh-cw"></i>
                    </button>

                    <!-- Boton generar reporte -->
                    <div class="btn-group mr-2">
                      <div class="flex justify-center space-x-2">
                        <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="feather mr-2 icon-file"></i>Reporte
                        </button>
                        <div class="dropdown-menu">
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosTotalesLogin">Reporte total de eventos</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosLoginFecha">Reporte por fechas</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosLoginUsuario">Reporte por usuario</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosLoginUsuarioFecha">Reporte por usuario y fechas</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Fin de botones alineados horizontalmente -->
                </div>

                <!-- Inicio de tabla de inicios de sesion -->
                <div class="relative sm:rounded-lg mt-2">
                  <div class="max-w-full overflow-hidden sm:rounded-lg">
                    <table id="tablaEventosLogin" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                      <!-- Encabezado de la tabla -->
                      <thead class="text-xs text-gray-700 uppercase bg-orange-300">
                        <tr>
                          <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                          <th scope="col" class="px-3 py-2 text-center">Fecha y Hora </th>
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
                        <?php if (!empty($resultadoAuditoriaLogin)): ?>
                          <?php $item = 1; // Iniciar contador para el ítem 
                          ?>
                          <?php foreach ($resultadoAuditoriaLogin as $login): ?>
                            <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                              <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($login['fechaFormateada']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($login['ROL_nombre']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($login['USU_nombre']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($login['NombreCompleto']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($login['ARE_nombre']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($login['AUD_ip']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($login['AUD_nombreEquipo']) ?></td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="10" class="text-center py-3">No se encontraron inicios de sesi&oacute;n.</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                      <!-- Fin de cuerpo de tabla -->
                    </table>
                  </div>
                </div>
                <!-- Fin de tabla de inicios de sesion -->
              </form>
              <!-- Fin de formulario de inicio de sesion de auditoría -->
            </div>

            <div class="tab-pane fade" id="registros" role="tabpanel" aria-labelledby="registros-tab">
              <div class="col-sm-20">
                <!-- <div class="card"> -->
                <div class="card-body">
                  <div class="row">
                    <!-- Pestañas verticales -->
                    <div class="col-md-2 col-sm-10">
                      <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <li><a class="nav-link text-left active" id="v-pills-incidencias-tab" data-toggle="pill" href="#v-pills-incidencias" role="tab" aria-controls="v-pills-incidencias" aria-selected="true">Incidencias</a></li>
                        <li><a class="nav-link text-left" id="v-pills-recepciones-tab" data-toggle="pill" href="#v-pills-recepciones" role="tab" aria-controls="v-pills-recepciones" aria-selected="false">Recepciones</a></li>
                        <li><a class="nav-link text-left" id="v-pills-asignaciones-tab" data-toggle="pill" href="#v-pills-asignaciones" role="tab" aria-controls="v-pills-asignaciones" aria-selected="false">Asignaciones</a></li>
                        <li><a class="nav-link text-left" id="v-pills-mantenimiento-tab" data-toggle="pill" href="#v-pills-mantenimiento" role="tab" aria-controls="v-pills-mantenimiento" aria-selected="false">Mantenimiento</a></li>
                        <li><a class="nav-link text-left" id="v-pills-cierres-tab" data-toggle="pill" href="#v-pills-cierres" role="tab" aria-controls="v-pills-cierres" aria-selected="false">Cierres</a></li>
                      </ul>
                    </div>
                    <!-- Fin de pestañas verticales -->

                    <!-- Contenido de las pestañas -->
                    <div class="col-md-10 col-sm-18">
                      <div class="tab-content" id="v-pills-tabContent">

                        <!-- Contenido de la primera pestaña -->
                        <div class="tab-pane fade show active" id="v-pills-incidencias" role="tabpanel" aria-labelledby="v-pills-incidencias-tab">
                          <!-- Inicio de formulario de registros de incidencias de auditoría -->
                          <form id="formAuditoriaIncidencias" action="auditoria.php?action=consultarEventosIncidencias" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Nombre de usuario de evento -->
                              <div class="w-full px-2 mb-2" style="max-width: 250px;">
                                <label for="usuarioEventoIncidencias" class="block mb-1 font-bold text-xs">Usuario de evento:</label>
                                <select id="usuarioEventoIncidencias" name="usuarioEventoIncidencias" class="border p-2 w-full text-xs cursor-pointer">
                                </select>
                                <input type="hidden" id="codigoUsuarioEventoIncidencias" name="codigoUsuarioEventoIncidencias" readonly>
                                <input type="hidden" id="nombreUsuarioEventoIncidencias" name="nombreUsuarioEventoIncidencias" readonly>
                              </div>

                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicioEventosIncidencias" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicioEventosIncidencias" name="fechaInicioEventosIncidencias" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinEventosIncidencias" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFinEventosIncidencias" name="fechaFinEventosIncidencias" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaIncidencias" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-teal-500 rounded-md flex justify-center items-center">
                                  <i class="feather icon-filter"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposEventosIncidencias" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosIncidenciasTotales">Reporte total de eventos</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosFecha">Reporte por fechas</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosUsuario">Reporte por usuario</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosUsuarioFecha">Reporte por usuario y fechas</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de inicios de sesion -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaEventosIncidencias" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-teal-200">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario Evento</th>
                                      <th scope="col" class="px-3 py-2 text-center">Operaci&oacute;n</th>
                                      <th scope="col" class="px-3 py-2 text-center">Referencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">&Aacute;rea</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoEventosIncidencias)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoEventosIncidencias as $incidencias): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['NombreCompleto']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['AUD_operacion']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['referencia']) ?></td>
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
                        <div class="tab-pane fade" id="v-pills-recepciones" role="tabpanel" aria-labelledby="v-pills-recepciones-tab">
                          <!-- Inicio de formulario de registros de recepciones de auditoría -->
                          <form id="formAuditoriaRecepciones" action="auditoria.php?action=consultarEventosRecepciones" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Nombre de usuario de evento -->
                              <div class="w-full px-2 mb-2" style="max-width: 250px;">
                                <label for="usuarioEventoRecepciones" class="block mb-1 font-bold text-xs">Usuario de evento:</label>
                                <select id="usuarioEventoRecepciones" name="usuarioEventoRecepciones" class="border p-2 w-full text-xs cursor-pointer">
                                </select>
                                <input type="hidden" id="codigoUsuarioEventoRecepciones" name="codigoUsuarioEventoRecepciones" readonly>
                                <input type="hidden" id="nombreUsuarioEventoRecepciones" name="nombreUsuarioEventoRecepciones" readonly>
                              </div>

                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicioEventosRecepciones" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicioEventosRecepciones" name="fechaInicioEventosRecepciones" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinEventosRecepciones" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFinEventosRecepciones" name="fechaFinEventosRecepciones" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaRecepciones" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-teal-500 rounded-md flex justify-center items-center">
                                  <i class="feather icon-filter"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposEventosRecepciones" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosTotales">Reporte total de eventos</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosRecepcionesFecha">Reporte por fechas</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosRecepcionesUsuario">Reporte por usuario</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosRecepcionesUsuarioFecha">Reporte por usuario y fechas</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de inicios de sesion -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaEventosRecepciones" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-teal-200">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario Evento</th>
                                      <th scope="col" class="px-3 py-2 text-center">Operaci&oacute;n</th>
                                      <th scope="col" class="px-3 py-2 text-center">Referencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoEventosRecepciones)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoEventosRecepciones as $recepciones): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepciones['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepciones['NombreCompleto']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepciones['AUD_operacion']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepciones['referencia']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepciones['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepciones['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="8" class="text-center py-3">No se encontraron registros de incidencias recepcionadas.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                  <!-- Fin de cuerpo de tabla -->
                                </table>
                              </div>
                            </div>
                            <!-- Fin de tabla de inicios de sesion -->
                          </form>
                          <!-- Fin de formulario de registros de recepciones de auditoría -->
                        </div>
                        <!-- Fin de contenido de la segunda pestaña -->

                        <!-- Contenido de la tercera pestaña -->
                        <div class="tab-pane fade" id="v-pills-asignaciones" role="tabpanel" aria-labelledby="v-pills-asignaciones-tab">
                          <form id="formAuditoriaAsignaciones" action="auditoria.php?action=listarEventosAsignaciones" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Nombre de usuario de evento -->
                              <div class="w-full px-2 mb-2" style="max-width: 250px;">
                                <label for="usuarioEventoAsignaciones" class="block mb-1 font-bold text-xs">Usuario de evento:</label>
                                <select id="usuarioEventoAsignaciones" name="usuarioEventoAsignaciones" class="border p-2 w-full text-xs cursor-pointer">
                                </select>
                                <input type="hidden" id="codigoUsuarioEventoAsignaciones" name="codigoUsuarioEventoAsignaciones" readonly>
                                <input type="hidden" id="nombreUsuarioEventoAsignaciones" name="nombreUsuarioEventoAsignaciones" readonly>
                              </div>

                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicioEventosAsignaciones" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicioEventosAsignaciones" name="fechaInicioEventosAsignaciones" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinEventosAsignaciones" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFinEventosAsignaciones" name="fechaFinEventosAsignaciones" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaAsignaciones" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-teal-500 rounded-md flex justify-center items-center">
                                  <i class="feather icon-filter"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposEventosAsignaciones" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosTotalesAsignaciones">Reporte total de eventos</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosAsignacionesFecha">Reporte por fechas</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosAsignacionesUsuario">Reporte por usuario</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosAsignacionesUsuarioFecha">Reporte por usuario y fechas</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de registros de asignaciones -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaEventosAsignaciones" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-teal-200">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario Evento</th>
                                      <th scope="col" class="px-3 py-2 text-center">Operaci&oacute;n</th>
                                      <th scope="col" class="px-3 py-2 text-center">Referencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoEventosAsignaciones)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoEventosAsignaciones as $asignacion): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['NombreCompleto']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['AUD_operacion']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['referencia']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="7" class="text-center py-3">No se encontraron incidencias en mantenimiento.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                  <!-- Fin de cuerpo de tabla -->
                                </table>
                              </div>
                            </div>
                            <!-- Fin de tabla de registros de recepciones -->
                          </form>
                        </div>
                        <!-- Fin de contenido de la tercera pestaña -->

                        <!-- Contenido de la cuarta pestaña -->
                        <div class="tab-pane fade" id="v-pills-mantenimiento" role="tabpanel" aria-labelledby="v-pills-mantenimiento-tab">
                          <form id="formAuditoriaMantenimiento" action="auditoria.php?action=consultarEventosMantenimiento" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Nombre de usuario de evento -->
                              <div class="w-full px-2 mb-2" style="max-width: 250px;">
                                <label for="usuarioEventoMantenimiento" class="block mb-1 font-bold text-xs">Usuario de evento:</label>
                                <select id="usuarioEventoMantenimiento" name="usuarioEventoMantenimiento" class="border p-2 w-full text-xs cursor-pointer">
                                </select>
                                <input type="hidden" id="codigoUsuarioEventoMantenimiento" name="codigoUsuarioEventoMantenimiento" readonly>
                                <input type="hidden" id="nombreUsuarioEventoMantenimiento" name="nombreUsuarioEventoMantenimiento" readonly>
                              </div>

                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicioEventosMantenimiento" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicioEventosMantenimiento" name="fechaInicioEventosMantenimiento" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinEventosMantenimiento" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFinEventosMantenimiento" name="fechaFinEventosMantenimiento" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaMantenimiento" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-teal-500 rounded-md flex justify-center items-center">
                                  <i class="feather icon-search"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposEventosMantenimiento" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosTotalesMantenimiento">Reporte total de eventos</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosMantenimientoFecha">Reporte por fechas</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosMantenimientoUsuario">Reporte por usuario</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosMantenimientoUsuarioFecha">Reporte por usuario y fechas</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de registros de mantenimiento -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaEventosMantenimiento" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-teal-200">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario evento</th>
                                      <th scope="col" class="px-3 py-2 text-center">Operaci&oacute;n</th>
                                      <th scope="col" class="px-3 py-2 text-center">Referencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoEventosMantenimiento)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoEventosMantenimiento as $mantenimiento): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($mantenimiento['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($mantenimiento['NombreCompleto']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($mantenimiento['AUD_operacion']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($mantenimiento['referencia']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($mantenimiento['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($mantenimiento['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="7" class="text-center py-3">No se encontraron incidencias asignadas.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                  <!-- Fin de cuerpo de tabla -->
                                </table>
                              </div>
                            </div>
                            <!-- Fin de tabla de registros de recepciones -->
                          </form>
                        </div>
                        <!-- Fin de contenido de la cuarta pestaña -->

                        <!-- Contenido de la quinta pestaña -->
                        <div class="tab-pane fade" id="v-pills-cierres" role="tabpanel" aria-labelledby="v-pills-cierres-tab">
                          <form id="formAuditoriaCierres" action="auditoria.php?action=consultarEventosCierres" method="GET" class="bg-white w-full text-xs ">  
                            <div class="flex justify-center items-center">
                              <!-- Nombre de usuario de evento -->
                              <div class="w-full px-2 mb-2" style="max-width: 250px;">
                                <label for="usuarioEventoCierres" class="block mb-1 font-bold text-xs">Usuario de evento:</label>
                                <select id="usuarioEventoCierres" name="usuarioEventoCierres" class="border p-2 w-full text-xs cursor-pointer">
                                </select>
                                <input type="hidden" id="codigoUsuarioEventoCierres" name="codigoUsuarioEventoCierres" readonly>
                                <input type="hidden" id="nombreUsuarioEventoCierres" name="nombreUsuarioEventoCierres" readonly>
                              </div>

                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicioEventosCierres" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicioEventosCierres" name="fechaInicioEventosCierres" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinEventosCierres" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label> 
                                <input type="date" id="fechaFinEventosCierres" name="fechaFinEventosCierres" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaCierres" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-teal-500 rounded-md flex justify-center items-center">
                                  <i class="feather icon-search"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposEventosCierres" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosTotalesCierres">Reporte total de eventos</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosCierresFecha">Reporte por fechas</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosCierresUsuario">Reporte por usuario</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosCierresUsuarioFecha">Reporte por usuario y fechas</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de registros de cierres -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaEventosCierres" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-teal-200">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario evento</th>
                                      <th scope="col" class="px-3 py-2 text-center">Operaci&oacute;n</th>
                                      <th scope="col" class="px-3 py-2 text-center">Referencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoEventosCierres)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoEventosCierres as $cierres): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($cierres['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($cierres['NombreCompleto']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($cierres['AUD_operacion']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($cierres['referencia']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($cierres['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($cierres['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="7" class="text-center py-3">No se encontraron incidencias cerradas.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                  <!-- Fin de cuerpo de tabla -->
                                </table>
                              </div>
                            </div>
                            <!-- Fin de tabla de registros de recepciones -->
                          </form>
                        </div>
                        <!-- Fin de contenido de la quinta pestaña -->
                      </div>
                    </div>
                    <!-- Fin de contenido de las pestañas -->

                  </div>
                </div>
                <!-- </div> -->
              </div>
            </div>

            <div class="tab-pane fade" id="mantenedor" role="tabpanel" aria-labelledby="mantenedor-tab">
              <div class="col-sm-20">
                <!-- <div class="card"> -->
                <div class="card-body">
                  <div class="row">
                    <!-- Pestañas verticales -->
                    <div class="col-md-2 col-sm-10">
                      <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <li><a class="nav-link text-left active" id="v-pills-usuarios-tab" data-toggle="pill" href="#v-pills-usuarios" role="tab" aria-controls="v-pills-usuarios" aria-selected="true">Usuarios</a></li>
                        <li><a class="nav-link text-left" id="v-pills-personas-tab" data-toggle="pill" href="#v-pills-personas" role="tab" aria-controls="v-pills-personas" aria-selected="false">Personas</a></li>
                        <li><a class="nav-link text-left" id="v-pills-areas-tab" data-toggle="pill" href="#v-pills-areas" role="tab" aria-controls="v-pills-areas" aria-selected="false">Áreas</a></li>
                        <li><a class="nav-link text-left" id="v-pills-bienes-tab" data-toggle="pill" href="#v-pills-bienes" role="tab" aria-controls="v-pills-bienes" aria-selected="false">Bienes</a></li>
                        <li><a class="nav-link text-left" id="v-pills-categorias-tab" data-toggle="pill" href="#v-pills-categorias" role="tab" aria-controls="v-pills-categorias" aria-selected="false">Categor&iacute;as</a></li>
                        <li><a class="nav-link text-left" id="v-pills-soluciones-tab" data-toggle="pill" href="#v-pills-soluciones" role="tab" aria-controls="v-pills-soluciones" aria-selected="false">Soluciones</a></li>
                      </ul>
                    </div>
                    <!-- Fin de pestañas verticales -->

                    <!-- Contenido de las pestañas -->
                    <div class="col-md-10 col-sm-18">
                      <div class="tab-content" id="v-pills-tabContent">

                        <!-- Contenido de la primera pestaña -->
                        <div class="tab-pane fade show active" id="v-pills-usuarios" role="tabpanel" aria-labelledby="v-pills-usuarios-tab">
                          <!-- Inicio de formulario de registros de eventos de usuarios -->
                          <form id="formAuditoriaUsuarios" action="auditoria.php?action=consultarEventosUsuarios" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Nombre de persona -->
                              <div class="w-full px-2 mb-2" style="max-width: 250px;">
                                <label for="usuarioEventoUsuarios" class="block mb-1 font-bold text-xs">Usuario de evento:</label>
                                <select id="usuarioEventoUsuarios" name="usuarioEventoUsuarios" class="border p-2 w-full text-xs cursor-pointer">
                                </select>
                                <input type="hidden" id="codigoUsuarioEventoUsuarios" name="codigoUsuarioEventoUsuarios" readonly>
                                <input type="hidden" id="nombreUsuarioEventoUsuarios" name="nombreUsuarioEventoUsuarios" readonly>
                              </div>

                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicioEventosUsuarios" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicioEventosUsuarios" name="fechaInicioEventosUsuarios" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinEventosUsuarios" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFinEventosUsuarios" name="fechaFinEventosUsuarios" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaEventosUsuarios" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-red-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-filter"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposEventosUsuarios" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group mr-2">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosTotalesUsuarios">Reporte total de eventos</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteUserEventosFecha">Reporte por fechas</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteUserEventosUsuario">Reporte por usuario</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteUserEventosUsuariosFecha">Reporte por usuario y fechas</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de inicios de sesion -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaEventosUsuarios" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario evento</th>
                                      <th scope="col" class="px-3 py-2 text-center">Operaci&oacute;n</th>
                                      <th scope="col" class="px-3 py-2 text-center">Referencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoAuditoriaEventosUsuarios)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoAuditoriaEventosUsuarios as $usuarios): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($usuarios['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($usuarios['UsuarioEvento']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($usuarios['AUD_operacion']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($usuarios['UsuarioReferencia']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($usuarios['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($usuarios['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="7" class="text-center py-3">No se encontraron eventos.</td>
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

                        <!-- Contenido de la segunda pestaña - Personas -->
                        <div class="tab-pane fade" id="v-pills-personas" role="tabpanel" aria-labelledby="v-pills-personas-tab">
                          <!-- Inicio de formulario de eventos de personas-->
                          <form id="formAuditoriaPersonas" action="auditoria.php?action=consultarEventosPersonas" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Nombre de usuario de evento -->
                              <div class="w-full px-2 mb-2" style="max-width: 250px;">
                                <label for="usuarioEventoPersonas" class="block mb-1 font-bold text-xs">Usuario de evento:</label>
                                <select id="usuarioEventoPersonas" name="usuarioEventoPersonas" class="border p-2 w-full text-xs cursor-pointer">
                                </select>
                                <input type="hidden" id="codigoUsuarioEventoPersonas" name="codigoUsuarioEventoPersonas" readonly>
                                <input type="hidden" id="nombreUsuarioEventoPersonas" name="nombreUsuarioEventoPersonas" readonly>
                              </div>
                              <!-- Operacion realizada -->
                              <!-- <div class="w-full px-2 mb-2" style="max-width: 150px;">
                                <label for="operacionPersona" class="block mb-1 font-bold text-xs">Operaci&oacute;n realizada:</label>
                                <select id="operacionPersona" name="operacionPersona" class="border p-2 w-full text-xs cursor-pointer rounded-md">
                                  <option value="" selected disabled>Seleccionar</option>
                                  <option value="Registro">Registro</option>
                                  <option value="Actualizar">Actualizaci&oacute;n</option>
                                  <option value="Habilitar">Habilitar</option>
                                  <option value="Deshabilitar">Deshabilitar</option>
                                </select>
                                <input type="hidden" id="codigoOperacionPersona" name="codigoOperacionPersona" readonly>
                                <input type="hidden" id="nombreOperacionPersona" name="nombreOperacionPersona" readonly>
                              </div> -->
                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicioEventosPersonas" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicioEventosPersonas" name="fechaInicioEventosPersonas" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinEventosPersonas" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFinEventosPersonas" name="fechaFinEventosPersonas" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-2 flex space-x-2 ml-5">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaPersonas" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-red-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-filter"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposEventosPersonas" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosPersonasTotales">Reporte total de eventos</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosPersonasFecha">Reporte por fechas</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosPersonasUsuario">Reporte por usuario</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosPersonasUsuarioFecha">Reporte por usuario y fechas</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de eventos de personas -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaEventosPersonas" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora </th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario Evento</th>
                                      <th scope="col" class="px-3 py-2 text-center">Operaci&oacute;n</th>
                                      <th scope="col" class="px-3 py-2 text-center">Referencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoAuditoriaEventosPersonas)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoAuditoriaEventosPersonas as $personas): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($personas['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($personas['UsuarioEvento']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($personas['AUD_operacion']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($personas['UsuarioReferencia']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($personas['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($personas['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="7" class="text-center py-3">No se encontraron eventos.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                  <!-- Fin de cuerpo de tabla -->
                                </table>
                              </div>
                            </div>
                            <!-- Fin de tabla de eventos de personas -->
                          </form>
                          <!-- Fin de formulario de eventos de personas -->
                        </div>
                        <!-- Fin de contenido de la segunda pestaña -->

                        <!-- Contenido de la tercera pestaña - Areas-->
                        <div class="tab-pane fade" id="v-pills-areas" role="tabpanel" aria-labelledby="v-pills-areas-tab">
                          <form id="formAuditoriaAreas" action="auditoria.php?action=consultarEventosAreas" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Nombre de usuario de evento -->
                              <div class="w-full px-2 mb-2" style="max-width: 250px;">
                                <label for="usuarioEventoAreas" class="block mb-1 font-bold text-xs">Usuario de evento:</label>
                                <select id="usuarioEventoAreas" name="usuarioEventoAreas" class="border p-2 w-full text-xs cursor-pointer">
                                </select>
                                <input type="hidden" id="codigoUsuarioEventoAreas" name="codigoUsuarioEventoAreas" readonly>
                                <input type="hidden" id="nombreUsuarioEventoAreas" name="nombreUsuarioEventoAreas" readonly>
                              </div>

                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicioEventosAreas" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicioEventosAreas" name="fechaInicioEventosAreas" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinEventosAreas" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFinEventosAreas" name="fechaFinEventosAreas" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaAreas" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-red-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-filter"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposEventosAreas" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosAreasTotales">Reporte total de eventos</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosAreasFecha">Reporte por fechas</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosAreasUsuario">Reporte por usuario</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosAreasUsuarioFecha">Reporte por usuario y fechas</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de registros de asignaciones -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaEventosAreas" class="bg-white w-full text-xs text-left rtl:text-right text-gray-400">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario evento</th>
                                      <th scope="col" class="px-3 py-2 text-center">Operaci&oacute;n</th>
                                      <th scope="col" class="px-3 py-2 text-center">Referencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoAuditoriaEventosAreas)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoAuditoriaEventosAreas as $areas): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($areas['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($areas['UsuarioEvento']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($areas['AUD_operacion']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($areas['referencia']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($areas['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($areas['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="7" class="text-center py-3">No se encontraron eventos.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                  <!-- Fin de cuerpo de tabla -->
                                </table>
                              </div>
                            </div>
                            <!-- Fin de tabla de registros de recepciones -->
                          </form>
                        </div>
                        <!-- Fin de contenido de la tercera pestaña - Areas -->

                        <!-- Contenido de la cuarta pestaña - Bienes -->
                        <div class="tab-pane fade" id="v-pills-bienes" role="tabpanel" aria-labelledby="v-pills-bienes-tab">
                          <form id="formAuditoriaBienes" action="auditoria.php?action=consultarEventosBienes" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Nombre de usuario de evento -->
                              <div class="w-full px-2 mb-2" style="max-width: 250px;">
                                <label for="usuarioEventoBienes" class="block mb-1 font-bold text-xs">Usuario de evento:</label>
                                <select id="usuarioEventoBienes" name="usuarioEventoBienes" class="border p-2 w-full text-xs cursor-pointer">
                                </select>
                                <input type="hidden" id="codigoUsuarioEventoBienes" name="codigoUsuarioEventoBienes" readonly>
                                <input type="hidden" id="nombreUsuarioEventoBienes" name="nombreUsuarioEventoBienes" readonly>
                              </div>

                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicioEventosBienes" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicioEventosBienes" name="fechaInicioEventosBienes" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinEventosBienes" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFinEventosBienes" name="fechaFinEventosBienes" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaBienes" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-red-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-filter"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposEventosBienes" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosBienesTotales">Reporte total de eventos</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosBienesFecha">Reporte por fechas</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosBienesUsuario">Reporte por usuario</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosBienesUsuarioFecha">Reporte por usuario y fechas</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de registros de asignaciones -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaEventosBienes" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario evento</th>
                                      <th scope="col" class="px-3 py-2 text-center">Operaci&oacute;n</th>
                                      <th scope="col" class="px-3 py-2 text-center">Referencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoAuditoriaEventosBienes)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoAuditoriaEventosBienes as $bienes): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($bienes['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($bienes['UsuarioEvento']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($bienes['AUD_operacion']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($bienes['referencia']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($bienes['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($bienes['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="7" class="text-center py-3">No se encontraron eventos.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                  <!-- Fin de cuerpo de tabla -->
                                </table>
                              </div>
                            </div>
                            <!-- Fin de tabla de registros de recepciones -->
                          </form>
                        </div>
                        <!-- Fin de contenido de la cuarta pestaña - Bienes -->

                        <!-- Contenido de la quinta pestaña - Categorias -->
                        <div class="tab-pane fade" id="v-pills-categorias" role="tabpanel" aria-labelledby="v-pills-categorias-tab">
                          <form id="formAuditoriaCategorias" action="auditoria.php?action=consultarEventosCategorias" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Nombre de usuario de evento -->
                              <div class="w-full px-2 mb-2" style="max-width: 250px;">
                                <label for="usuarioEventoCategorias" class="block mb-1 font-bold text-xs">Usuario de evento:</label>
                                <select id="usuarioEventoCategorias" name="usuarioEventoCategorias" class="border p-2 w-full text-xs cursor-pointer">
                                </select>
                                <input type="hidden" id="codigoUsuarioEventoCategorias" name="codigoUsuarioEventoCategorias" readonly>
                                <input type="hidden" id="nombreUsuarioEventoCategorias" name="nombreUsuarioEventoCategorias" readonly>
                              </div>

                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicioEventosCategorias" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicioEventosCategorias" name="fechaInicioEventosCategorias" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinEventosCategorias" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFinEventosCategorias" name="fechaFinEventosCategorias" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaCategorias" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-red-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-filter"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposEventosCategorias" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosCategoriasTotales">Reporte total de eventos</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosCategoriasFecha">Reporte por fechas</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosCategoriasUsuario">Reporte por usuario</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosCategoriasUsuarioFecha">Reporte por usuario y fechas</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de registros de categorias -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaEventosCategorias" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario evento</th>
                                      <th scope="col" class="px-3 py-2 text-center">Operaci&oacute;n</th>
                                      <th scope="col" class="px-3 py-2 text-center">Referencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoAuditoriaEventosCategorias)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoAuditoriaEventosCategorias as $categorias): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($categorias['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($categorias['UsuarioEvento']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($categorias['AUD_operacion']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($categorias['referencia']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($categorias['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($categorias['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="7" class="text-center py-3">No se encontraron eventos.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                  <!-- Fin de cuerpo de tabla -->
                                </table>
                              </div>
                            </div>
                            <!-- Fin de tabla de registros de categorias -->
                          </form>
                        </div>
                        <!-- Fin de contenido de la quinta pestaña - Categorias -->

                        <!-- Contenido de la sexta pestaña - Soluciones -->
                        <div class="tab-pane fade" id="v-pills-soluciones" role="tabpanel" aria-labelledby="v-pills-soluciones-tab">
                          <form id="formAuditoriaSoluciones" action="auditoria.php?action=consultarEventosSoluciones" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Nombre de usuario de evento -->
                              <div class="w-full px-2 mb-2" style="max-width: 250px;">
                                <label for="usuarioEventoSoluciones" class="block mb-1 font-bold text-xs">Usuario de evento:</label>
                                <select id="usuarioEventoSoluciones" name="usuarioEventoSoluciones" class="border p-2 w-full text-xs cursor-pointer">
                                </select>
                                <input type="hidden" id="codigoUsuarioEventoSoluciones" name="codigoUsuarioEventoSoluciones" readonly>
                                <input type="hidden" id="nombreUsuarioEventoSoluciones" name="nombreUsuarioEventoSoluciones" readonly>
                              </div>

                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicioEventosSoluciones" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicioEventosSoluciones" name="fechaInicioEventosSoluciones" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFinEventosSoluciones" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFinEventosSoluciones" name="fechaFinEventosSoluciones" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaSoluciones" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-red-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-search"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCamposEventosSoluciones" class="h-10 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>

                                <!-- Boton generar reporte -->
                                <div class="btn-group">
                                  <div class="flex justify-center space-x-2">
                                    <button type="button" class="btn btn-secondary dropdown-toggle h-10 py-2 px-3 rounded-md flex justify-center items-center" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="feather mr-2 icon-file"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosSolucionesTotales">Reporte total de eventos</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosSolucionesFecha">Reporte por fechas</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosSolucionesUsuario">Reporte por usuario</div>
                                      <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosSolucionesUsuarioFecha">Reporte por usuario y fechas</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de eventos de soluciones -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaEventosSoluciones" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">N&deg;</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario evento</th>
                                      <th scope="col" class="px-3 py-2 text-center">Operaci&oacute;n</th>
                                      <th scope="col" class="px-3 py-2 text-center">Referencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoAuditoriaEventosSoluciones)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoAuditoriaEventosSoluciones as $soluciones): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($soluciones['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($soluciones['UsuarioEvento']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($soluciones['AUD_operacion']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($soluciones['referencia']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($soluciones['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($soluciones['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="7" class="text-center py-3">No se encontraron eventos.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                  <!-- Fin de cuerpo de tabla -->
                                </table>
                              </div>
                            </div>
                            <!-- Fin de tabla de eventos de soluciones -->
                          </form>
                        </div>
                        <!-- Fin de contenido de la sexta pestaña - Soluciones -->
                      </div>
                    </div>
                    <!-- Fin de contenido de las pestañas -->

                  </div>
                </div>
                <!-- </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Final del tab pane -->
  </div>
</div>