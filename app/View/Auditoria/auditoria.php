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
              <a class="nav-link active text-uppercase" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">Inicio de Sesi&oacute;n</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="registros-tab" data-toggle="tab" href="#registros" role="tab" aria-controls="registros" aria-selected="false">Registros</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-uppercase" id="mantenedor-tab" data-toggle="tab" href="#mantenedor" role="tab" aria-controls="mantenedor" aria-selected="false">Mantenedor</a>
            </li>
          </ul>
          <!-- Fin de los titulos de las pestañas -->

          <!-- Contenido de las pestañas -->
          <div class="tab-content grow" id="myTabContent">
            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">

              <!-- Inicio de formulario de inicio de sesion de auditoría -->
              <form id="formaAuditoriaLogin" action="auditoria.php?action=listarRegistrosInicioSesion" method="GET" class="bg-white w-full text-xs ">
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
                    <button type="submit" id="filtrarListaAuditoriaLogeos" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-orange-500 rounded-md flex justify-center items-center">
                      <i class="feather icon-search"></i>
                    </button>

                    <!-- Botón de nueva consulta -->
                    <button type="button" id="limpiarCampos" class="h-8 w-12 text-xs text-white font-bold py-1 px-3 bg-gray-500 rounded-md flex justify-center items-center">
                      <i class="feather icon-refresh-cw"></i>
                    </button>
                  </div>
                  <!-- Fin de botones alineados horizontalmente -->
                </div>

                <!-- Inicio de tabla de inicios de sesion -->
                <div class="relative sm:rounded-lg mt-2">
                  <div class="max-w-full overflow-hidden">
                    <table id="tablaLogeos" class="bg-white w-full text-xs text-left rtl:text-right text-gray-500">
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
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
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
                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                          <p class="mb-0">Fugiat id quis dolor culpa eiusmod anim velit excepteur proident dolor aute qui magna. Ad proident laboris ullamco esse anim Lorem Lorem veniam quis Lorem irure occaecat velit
                            nostrud magna
                            nulla. Velit et et proident Lorem do ea tempor officia dolor. Reprehenderit Lorem aliquip labore est magna commodo est ea veniam consectetur.</p>
                        </div>
                        <!-- Fin de contenido de la tercera pestaña -->
                      </div>
                    </div>
                    <!-- Fin de contenido de las pestañas -->

                  </div>
                </div>
                <!-- </div> -->
              </div>

            </div>

            <div class="tab-pane fade" id="mantenedor" role="tabpanel" aria-labelledby="mantenedor-tab">
              <p class="mb-0">Etsy mixtape wayfarers...</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Final del tab pane -->
  </div>
</div>