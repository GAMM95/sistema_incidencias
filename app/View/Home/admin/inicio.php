<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <!-- Inicio de miga de pan -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h5 class="text-2xl font-bold mb-2">Panel Informativo</h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="inicio.php"><i class="feather icon-home"></i></a>
              </li>
              <li class="breadcrumb-item"><a href="inicio.php">Inicio</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin de miga de pan -->

    <!-- Inicio de contenido principal -->
    <div class="row">
      <!-- Widget del grafico -->
      <div id="grafico" class="col-md-12 col-xl-8">
        <div class="card support-bar overflow-hidden">
          <div class="card-body pb-0">
            <!-- Contar el total de incidencias en el mes -->
            <h2 class="m-0 text-lg font-bold"><?php echo $cantidades['incidencias_mes_actual']; ?></h2>
            <span class="text-c-blue font-bold">INCIDENCIAS</span>

            <?php
            // Establecer la configuración regional para el idioma español
            setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain', 'Spanish');

            // Establecer la zona horaria
            date_default_timezone_set('America/Lima');

            // Crear un objeto DateTime para la fecha actual
            $dateTimeObj = new DateTime('now', new DateTimeZone('America/Lima'));

            // Crear un objeto IntlDateFormatter para formatear la fecha
            $formatter = new IntlDateFormatter(
              'es_ES', // Configuración regional para el idioma español
              IntlDateFormatter::NONE, // Sin formato de fecha completa
              IntlDateFormatter::NONE, // Sin formato de tiempo
              null, // Usar la zona horaria predeterminada
              null, // Calendario gregoriano
              'MMMM' // Formato para mes
            );

            // Obtener el nombre del mes
            $nombreMes = $formatter->format($dateTimeObj);
            ?>

            <!-- Integrar el selector de mes en la línea de texto sin bordes -->
            <p class="mb-3 mt-3">
              Total de incidencias en el mes de <?php echo $nombreMes; ?>
              <!-- <select id="mes-selector" class="bg-transparent text-md font-bold outline-none cursor-pointer">
                <?php
                // Crear opciones de mes
                for ($i = 1; $i <= 12; $i++) {
                  // Crear un objeto DateTime para cada mes
                  $mesObj = DateTime::createFromFormat('!m', $i);
                  $nombreMesOption = $formatter->format($mesObj);
                  // Si el mes actual coincide con el mes en el bucle, seleccionarlo
                  $selected = ($i == $dateTimeObj->format('n')) ? 'selected' : '';
                  echo "<option value=\"$i\" $selected>$nombreMesOption</option>";
                }
                ?>
              </select> -->
              del <?php echo date('Y'); ?>.
            </p>

          </div>
          <div id="support-chart"></div>
          <!-- etiquetas inferiores del gráfico -->
          <div class="card-footer bg-primary text-white">
            <div class="row text-center">
              <div class="col">
                <h4 class="m-0 text-white font-bold"><?php echo $cantidades['pendientes_mes_actual']; ?></h4>
                <span>Incidencias Nuevas</span>
              </div>
              <div class="col">
                <h4 class="m-0 text-white font-bold"><?php echo $cantidades['recepciones_mes_actual']; ?></h4>
                <span>Incidencias Pendientes</span>
              </div>
              <div class="col">
                <h4 class="m-0 text-white font-bold"><?php echo $cantidades['cierres_mes_actual']; ?></h4>
                <span>Incidencias Cerradas</span>
              </div>
            </div>
          </div>
          <!-- fin de etiquetas inferiores del gráfico -->
        </div>
      </div>

      <script>
        document.getElementById('mes-selector').addEventListener('change', function() {
          var mesNombre = this.options[this.selectedIndex].text;
          document.getElementById('mes-nombre').textContent = mesNombre;
        });
      </script>
      <!-- Fin del widget del grafico -->

      <!-- Tarjetas de las cantidades  -->
      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script>
        // Pasar datos de PHP a JavaScript
        var incidenciasData = <?php echo json_encode([
                                (int)$cantidades['pendientes_mes_actual'],
                                (int)$cantidades['recepciones_mes_actual'],
                                (int)$cantidades['cierres_mes_actual']
                              ]); ?>;
      </script>
      <!-- Fin de las tarjetas de las cantidades -->

      <!-- Inicio del widget de los contadores -->
      <div id="contador" class="col-md-12 col-xl-4">
        <!-- Widget del grafico -->
        <div id="grafico" class="">
          <div class="card support-bar overflow-hidden">
            <div class="card-body pb-0">
              <span class="text-c-blue font-bold">INCIDENCIAS EN MANTENIMIENTO - <?php echo $cantidades['total_recepciones_mes_actual']; ?></span>

              <?php
              // Establecer la configuración regional para el idioma español
              setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain', 'Spanish');

              // Establecer la zona horaria
              date_default_timezone_set('America/Lima');

              // Crear un objeto DateTime para la fecha actual
              $dateTimeObj = new DateTime('now', new DateTimeZone('America/Lima'));

              // Crear un objeto IntlDateFormatter para formatear la fecha
              $formatter = new IntlDateFormatter(
                'es_ES', // Configuración regional para el idioma español
                IntlDateFormatter::NONE, // Sin formato de fecha completa
                IntlDateFormatter::NONE, // Sin formato de tiempo
                null, // Usar la zona horaria predeterminada
                null, // Calendario gregoriano
                'MMMM' // Formato para mes
              );

              // Obtener el nombre del mes
              $nombreMes = $formatter->format($dateTimeObj);
              ?>

              <!-- Integrar el selector de mes en la línea de texto sin bordes -->
              <p class="mb-2 mt-3 text-center">
                Incidencias en mantenimiento para <?php echo $nombreMes; ?>
                <!-- <select id="mes-selector" class="bg-transparent text-md font-bold outline-none cursor-pointer">
                <?php
                // Crear opciones de mes
                for ($i = 1; $i <= 12; $i++) {
                  // Crear un objeto DateTime para cada mes
                  $mesObj = DateTime::createFromFormat('!m', $i);
                  $nombreMesOption = $formatter->format($mesObj);
                  // Si el mes actual coincide con el mes en el bucle, seleccionarlo
                  $selected = ($i == $dateTimeObj->format('n')) ? 'selected' : '';
                  echo "<option value=\"$i\" $selected>$nombreMesOption</option>";
                }
                ?>
              </select> -->
                del <?php echo date('Y'); ?>.
              </p>

            </div>
            <div id="support-chart2"></div>
          </div>
        </div>
        <script>
          document.getElementById('mes-selector').addEventListener('change', function() {
            var mesNombre = this.options[this.selectedIndex].text;
            document.getElementById('mes-nombre').textContent = mesNombre;
            // Aquí puedes agregar una llamada AJAX para actualizar los datos según el mes seleccionado
          });
        </script>
        <!-- Fin del widget del grafico -->

        <!-- Tarjetas de las cantidades  -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
          // Pasar datos de PHP a JavaScript
          var recepcionesData = <?php echo json_encode([
                                  (int)$cantidades['recepciones_en_espera_mes_actual'],
                                  (int)$cantidades['recepciones_finalizadas_mes_actual'],
                                ]); ?>;
        </script>
        <!-- Fin de las tarjetas de las cantidades -->

        <!-- widget del area con mas incidencias -->
        <div class="card flat-card widget-primary-card">
          <div class="row-table">
            <div class="col-sm-3 card-body">
              <i class="feather icon-alert-triangle"></i>
            </div>
            <div class="col-sm-9">
              <h6 class="text-xs mb-2">
                <!-- Area con más incidencias del mes actual -->
                <p>
                  &Aacute;rea con m&aacute;s incidencias en el mes <?php echo $nombreMes; ?>
                  <!-- <select id="mes-selector" class="bg-transparent text-md font-bold outline-none cursor-pointer">
                <?php
                // Crear opciones de mes
                for ($i = 1; $i <= 12; $i++) {
                  // Crear un objeto DateTime para cada mes
                  $mesObj = DateTime::createFromFormat('!m', $i);
                  $nombreMesOption = $formatter->format($mesObj);
                  // Si el mes actual coincide con el mes en el bucle, seleccionarlo
                  $selected = ($i == $dateTimeObj->format('n')) ? 'selected' : '';
                  echo "<option value=\"$i\" $selected>$nombreMesOption</option>";
                }
                ?>
              </select> -->
                  del <?php echo date('Y'); ?>.
                </p>
              </h6>
              <h5 class="text-white font-bold"><?php echo $cantidades['areaMasIncidencia']; ?></h5>
            </div>
          </div>
        </div>
        <!-- Fin del widget con mas incidencias -->
      </div>
      <!-- Fin del widget de los contadores -->

      <!-- Tabla de incidencias -->
      <div class="col-xl-12 col-md-12">
        <div class="card table-card">
          <div class="card-header py-2 flex items-center justify-between">
            <!-- Subtitulo -->
            <h5 class="flex-shrink-0">Incidencias del d&iacute;a</h5>

            <!-- Input de la fecha seleccionada -->
            <div class="flex-grow flex justify-center">
              <input
                type="date"
                name="fecha"
                id="fechaInput"
                class="form-input mr-4 cursor-pointer"
                aria-label="Seleccionar fecha"
                value="<?= isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d'); ?>"
                max="<?= date('Y-m-d'); ?>">
            </div>

            <!-- Fin de la fecha seleccionada -->

            <!-- Botones alineados a la derecha -->
            <div class="btn-group card-option flex-shrink-0">
              <button type="button" class="btn " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="feather icon-more-horizontal"></i>
              </button>
              <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> Maximizar</span><span style="display:none"><i class="feather icon-minimize"></i> Restaurar</span></a></li>
                <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> Minimizar</span><span style="display:none"><i class="feather icon-plus"></i> Expandir</span></a></li>
                <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> Recargar</a></li>
                <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> Eliminar</a></li>
              </ul>
            </div>
            <!-- Fin de botones alineados a la derecha -->
          </div>

          <!-- TABLA DE NUEVAS INCIDENCIAS -->
          <div id="tabla-incidencias" class="card-body p-0">
            <div class="table-responsive overflow-y-auto max-h-96">
              <table class="table table-hover mb-0 text-xs" id="incidenciasTable">
                <!-- Encabezado -->
                <thead>
                  <tr>
                    <th class="text-center">N&deg; INCIDENCIA</th>
                    <th class="text-center">Usuario</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Incidencia</th>
                    <th class="text-center">Documento</th>
                    <th class="text-center">Estado</th>
                  </tr>
                </thead>
                <!-- Fin de encabezado -->

                <!-- Cuerpo -->
                <tbody id="incidenciasBody">
                </tbody>
                <!-- Fin del cuerpo -->
              </table>
            </div>
          </div>
          <!-- Fin tabla de nuevas incidencias -->
        </div>
      </div>
      <!-- Fin de tabla de incidencias -->
    </div>
    <!-- Fin del contenido principal -->
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">
<script src="./app/View/func/Inicio/func_inicio_admin.js"></script>