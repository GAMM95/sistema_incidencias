<div class="pcoded-main-container mt-5">
  <div class="pcoded-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h5 class="text-2xl font-bold mb-2">Panel Informativo</h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a>
              </li>
              <li class="breadcrumb-item"><a href="inicio.php">Inicio</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
      <!-- Widget primary-success card start -->
      <div id="grafico" class="col-md-12 col-xl-20">
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
              'MMMM' // Formato para mes y año
            );
            // Obtener el nombre del mes
            $nombreMes = $formatter->format($dateTimeObj);
            ?>
            <p class="mb-3 mt-3">Total de incidencias en el mes de <?php echo $nombreMes; ?> de <?php echo date('Y'); ?>.</p>

          </div>
          <div id="support-chart3"></div> <!-- Asegúrate de tener este div -->
          <div class="card-footer bg-primary text-white">
            <div class="row text-center">
              <div class="col">
                <h4 class="m-0 text-white font-bold"><?php echo $cantidades['pendientes_mes_actual']; ?></h4>
                <span>Abiertas</span>
              </div>
              <div class="col">
                <h4 class="m-0 text-white font-bold"><?php echo $cantidades['recepciones_mes_actual']; ?></h4>
                <span>Recepcionadas</span>
              </div>
              <div class="col">
                <h4 class="m-0 text-white font-bold"><?php echo $cantidades['cierres_mes_actual']; ?></h4>
                <span>Cerradas</span>
              </div>
            </div>
          </div>
        </div>
        <!-- Incluye las librerías necesarias -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
          // Pasar datos de PHP a JavaScript
          var incidenciasDataUser = <?php echo json_encode([
                                  (int)$cantidades['pendientes_mes_actual'],
                                  (int)$cantidades['recepciones_mes_actual'],
                                  (int)$cantidades['cierres_mes_actual']
                                ]); ?>;
        </script>
      </div>
      <!-- Fin de widget de graficas de barras -->

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

            <!-- Input del área -->
            <?php
            $area = isset($_SESSION['codigoArea']) ? $_SESSION['codigoArea'] : ''; 
            ?>

            <div class="w-full sm:w-1/6 px-2 mb-2">
              <label for="codigoArea" class="block mb-1 font-bold text-xs hidden">C&oacute;digo &Aacute;rea:</label>
              <input type="hidden" id="codigoArea" name="codigoArea" value="<?= $area; ?>" readonly>
            </div>
            <!-- Fin del input del área -->

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
            <!-- Fin de los botones alineados a la derecha -->
          </div>

          <!-- TABLA DE NUEVAS INCIDENCIAS -->
          <div id="tabla-incidencias" class="card-body p-0">
            <div class="table-responsive overflow-y-auto max-h-96">
              <table class="table table-hover mb-0 text-xs">
                <!-- Encabezado -->
                <thead>
                  <tr>
                    <th class="text-center">INCIDENCIA</th>
                    <th class="text-center">Usuario</th>
                    <th class="text-center">Fecha incidencia</th>
                    <th class="text-center">Asunto</th>
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
    </div>
    <!-- [ Main Content ] end -->
  </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>
<script src="./public/func/Inicio/func_inicio_user.js"></script>