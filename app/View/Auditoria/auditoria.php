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
              <a class="nav-link text-uppercase" id="registros-tab" data-toggle="tab" href="#registros" role="tab" aria-controls="registros" aria-selected="false">Incidencias</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="mantenedor-tab" data-toggle="tab" href="#mantenedor" role="tab" aria-controls="mantenedor" aria-selected="false">Mantenedor</a>
            </li>
          </ul>
          <!-- Fin de los titulos de las pestañas -->

          <!-- Contenido de las pestañas -->
          <div class="tab-content grow" id="myTabContent">
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
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporte-eventos-totales">Todos los eventos</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosTotalesFecha">Por fechas</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosTotalesUsuario">Por usuario</div>
                          <div class="dropdown-item hover:text-white cursor-pointer" id="reporteEventosTotalesUsuarioFecha">Usuario y fechas</div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Fin de botones alineados horizontalmente -->
                </div>

                <!-- Inicio de tabla de eventos totales -->
                <div class="relative sm:rounded-lg mt-2">
                  <div class="max-w-full overflow-hidden sm:rounded-lg">
                    <table id="tablaEventosTotales" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                      <!-- Encabezado de la tabla -->
                      <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-3 py-2 text-center">&iacute;tem</th>
                          <th scope="col" class="px-3 py-2 text-center">Fecha y Hora del evento </th>
                          <th scope="col" class="px-3 py-2 text-center">Evento</th>
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
                        <?php if (!empty($resultadoEventosTotales)): ?>
                          <?php $item = 1; // Iniciar contador para el ítem 
                          ?>
                          <?php foreach ($resultadoEventosTotales as $totales): ?>
                            <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                              <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['fechaFormateada']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['AUD_operacion']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['ROL_nombre']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['USU_nombre']) ?></td>
                              <td class="px-3 py-2 text-center"><?= htmlspecialchars($totales['NombreCompleto']) ?></td>
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
                    <button type="submit" id="filtrarListaEventosLogin" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 btn-primary rounded-md flex justify-center items-center">
                      <i class="feather icon-filter"></i>
                    </button>

                    <!-- Boton generar reporte filtrado-->
                    <button type="button" id="reporteEventosLoginFiltro" class="bn text-xs font-bold py-2 px-3 rounded-md text-white bg-gray-300 cursor-not-allowed" disabled>
                      <i class="feather mr-2 icon-file"></i>Reporte filtrado
                    </button>

                    <!-- Botón de nueva consulta -->
                    <button type="button" id="limpiarCamposEventosLogin" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-500 rounded-md flex justify-center items-center">
                      <i class="feather icon-refresh-cw"></i>
                    </button>

                    <!-- Boton generar reporte -->
                    <div class="flex justify-center space-x-2">
                      <button type="button" id="reporteEventosLogin" class="bn btn-primary text-xs text-white font-bold py-2 px-3 rounded-md"><i class="feather mr-2 icon-file"></i>Reporte totales</button>
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
                          <th scope="col" class="px-3 py-2 text-center">&iacute;tem</th>
                          <th scope="col" class="px-3 py-2 text-center">Fecha y Hora de logeo </th>
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
                        <li><a class="nav-link text-left active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Incidencias</a></li>
                        <li><a class="nav-link text-left" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Recepciones</a></li>
                        <li><a class="nav-link text-left" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Asignaciones</a></li>
                        <li><a class="nav-link text-left" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Mantenimiento</a></li>
                        <li><a class="nav-link text-left" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Cierres</a></li>
                      </ul>
                    </div>
                    <!-- Fin de pestañas verticales -->

                    <!-- Contenido de las pestañas -->
                    <div class="col-md-10 col-sm-18">
                      <div class="tab-content" id="v-pills-tabContent">

                        <!-- Contenido de la primera pestaña -->
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
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
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaIncidenciasRegistradas" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-red-300">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">&iacute;tem</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora de registro</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario Registro</th>
                                      <th scope="col" class="px-3 py-2 text-center">Incidencia</th>
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
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['NombreCompleto']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['INC_numero_formato']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['ARE_nombre']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($incidencias['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="7" class="text-center py-3">No se encontraron registros de incidencias.</td>
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
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                          <!-- Inicio de formulario de registros de recepciones de auditoría -->
                          <form id="formAuditoriaRecepciones" action="auditoria.php?action=listarRegistrosRecepciones" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicio_auditoria_recepciones" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicio_auditoria_recepciones" name="fechaInicio_auditoria_recepciones" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFin_auditoria_recepciones" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFin_auditoria_recepciones" name="fechaFin_auditoria_recepciones" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaRecepciones" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-red-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-search"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCampos_auditoria_recepciones" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-500 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de registros de recepciones -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaRecepciones" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-yellow-300">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">&iacute;tem</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora de registro</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario receptor</th>
                                      <th scope="col" class="px-3 py-2 text-center">Incidencia</th>
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
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepcion['NombreCompleto']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepcion['INC_numero_formato']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepcion['ARE_nombre']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepcion['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($recepcion['AUD_nombreEquipo']) ?></td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="7" class="text-center py-3">No se encontraron incidencias recepcionadas.</td>
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
                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                          <form id="formAuditoriaAsignaciones" action="auditoria.php?action=listarRegistrosAsignaciones" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicio_auditoria_asignaciones" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicio_auditoria_asignaciones" name="fechaInicio_auditoria_asignaciones" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFin_auditoria_asignaciones" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFin_auditoria_asignaciones" name="fechaFin_auditoria_asignaciones" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaAsignaciones" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-red-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-search"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCampos_auditoria_asignaciones" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-500 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de registros de asignaciones -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaAsignaciones" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-purple-300">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">&iacute;tem</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora de registro</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario asignador</th>
                                      <th scope="col" class="px-3 py-2 text-center">Incidencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">&Aacute;rea</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoAuditoriaRegistroAsignaciones)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoAuditoriaRegistroAsignaciones as $asignacion): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['NombreCompleto']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['INC_numero_formato']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['ARE_nombre']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['AUD_nombreEquipo']) ?></td>
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
                        <!-- Fin de contenido de la tercera pestaña -->

                        <!-- TODO: Contenido de la cuarta pestaña -->
                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                          <form id="formAuditoriaMantenimiento" action="auditoria.php?action=listarRegistrosAsignaciones" method="GET" class="bg-white w-full text-xs ">
                            <div class="flex justify-center items-center">
                              <!-- Fecha de inicio -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaInicio_auditoria_asignaciones" class="block mb-1 font-bold text-center text-xs">Fecha Inicio:</label>
                                <input type="date" id="fechaInicio_auditoria_asignaciones" name="fechaInicio_auditoria_asignaciones" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de inicio -->

                              <!-- Fecha de fin -->
                              <div class="w-full sm:w-1/3 md:w-1/6 px-2 mb-2">
                                <label for="fechaFin_auditoria_asignaciones" class="block mb-1 font-bold text-center text-xs">Fecha Fin:</label>
                                <input type="date" id="fechaFin_auditoria_asignaciones" name="fechaFin_auditoria_asignaciones" class="w-full border p-2 text-xs text-center cursor-pointer rounded-md" max="<?php echo date('Y-m-d'); ?>">
                              </div>
                              <!-- Fin de fecha de fin -->

                              <!-- Botones alineados horizontalmente -->
                              <div class="ml-5 flex space-x-2">
                                <!-- Botón de buscar -->
                                <button type="submit" id="filtrarListaAuditoriaAsignaciones" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-red-400 rounded-md flex justify-center items-center">
                                  <i class="feather icon-search"></i>
                                </button>

                                <!-- Botón de nueva consulta -->
                                <button type="button" id="limpiarCampos_auditoria_asignaciones" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-500 rounded-md flex justify-center items-center">
                                  <i class="feather icon-refresh-cw"></i>
                                </button>
                              </div>
                              <!-- Fin de botones alineados horizontalmente -->
                            </div>

                            <!-- Inicio de tabla de registros de asignaciones -->
                            <div class="relative sm:rounded-lg mt-2">
                              <div class="max-w-full overflow-hidden sm:rounded-lg">
                                <table id="tablaAsignaciones" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
                                  <!-- Encabezado de la tabla -->
                                  <thead class="text-xs text-gray-700 uppercase bg-purple-300">
                                    <tr>
                                      <th scope="col" class="px-3 py-2 text-center">&iacute;tem</th>
                                      <th scope="col" class="px-3 py-2 text-center">Fecha y Hora de registro</th>
                                      <th scope="col" class="px-3 py-2 text-center">Usuario asignador</th>
                                      <th scope="col" class="px-3 py-2 text-center">Incidencia</th>
                                      <th scope="col" class="px-3 py-2 text-center">&Aacute;rea</th>
                                      <th scope="col" class="px-3 py-2 text-center">IP</th>
                                      <th scope="col" class="px-3 py-2 text-center">Nombre del Equipo</th>
                                    </tr>
                                  </thead>
                                  <!-- Fin de encabezado de la tabla -->

                                  <!-- Cuerpo de la tabla -->
                                  <tbody>
                                    <?php if (!empty($resultadoAuditoriaRegistroAsignaciones)): ?>
                                      <?php $item = 1; // Iniciar contador para el ítem 
                                      ?>
                                      <?php foreach ($resultadoAuditoriaRegistroAsignaciones as $asignacion): ?>
                                        <tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">
                                          <td class="px-3 py-2 text-center"><?= $item++ ?></td> <!-- Columna de ítem -->
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['fechaFormateada']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['NombreCompleto']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['INC_numero_formato']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['ARE_nombre']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['AUD_ip']) ?></td>
                                          <td class="px-3 py-2 text-center"><?= htmlspecialchars($asignacion['AUD_nombreEquipo']) ?></td>
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
                      </div>
                    </div>
                    <!-- Fin de contenido de las pestañas -->

                  </div>
                </div>
                <!-- </div> -->
              </div>

            </div>

            <div class="tab-pane fade" id="mantenedor" role="tabpanel" aria-labelledby="mantenedor-tab">
              <p class="mb-0">Que miras sapo...</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Final del tab pane -->
  </div>
</div>