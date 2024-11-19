<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}
$action = $_GET['action'] ?? '';
$state = $_GET['state'] ?? '';

$rol = $_SESSION['rol'];
$area = $_SESSION['codigoArea'];

require_once './app/Controller/incidenciaController.php';
require_once './app/Controller/recepcionController.php';
require_once './app/Controller/cierreController.php';

$incidenciaController = new IncidenciaController();
$recepcionController = new RecepcionController();
$cierreController = new CierreController();


$fechaInicio = $_GET['fechaInicio'] ?? '';
$fechaFin = $_GET['fechaFin'] ?? '';

if ($action === 'consultarTotales') {
  // Depuración: mostrar los parámetros recibidos
  error_log("Fecha Inicio: " . $fechaInicio);
  error_log("Fecha Fin: " . $fechaFin);

  // Obtener los resultados de la búsqueda
  $resultadoIncidenciasTotales = $incidenciaController->filtrarIncidenciasTotalesFecha($fechaInicio, $fechaFin);

  // Imprimir el resultado de la depuración
  error_log("Resultado de la consulta: " . print_r($resultadoIncidenciasTotales, true));

  // Dibujar tabla de consultas
  $html = '';
  if (!empty($resultadoIncidenciasTotales)) {
    $item = 1; // Iniciar contador para el ítem
    foreach ($resultadoIncidenciasTotales as $totales) {
      $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
      $html .= '<td class="px-3 py-2 text-center">' . $item++ . '</td>'; // Columna de ítem
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($totales['INC_numero_formato']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($totales['fechaIncidenciaFormateada']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($totales['INC_asunto']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($totales['INC_documento']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($totales['INC_codigoPatrimonial']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($totales['BIE_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($totales['ARE_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($totales['PRI_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($totales['CON_descripcion']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center text-xs align-middle">';

      // Manejar el estado de la incidencia
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

      $html .= '<label class="badge ' . $badgeClass . '">' . $estadoDescripcion . '</label>';
      $html .= '</td></tr>';
    }
  } else {
    $html = '<tr><td colspan="11" class="text-center py-3">No se han registrado incidencias.</td></tr>';
  }

  // Devolver el HTML de las filas
  echo $html;
  exit;
} else {
  // Si no hay acción, obtener la lista de las incidencias
  $resultadoIncidenciasTotales = $incidenciaController->listarIncidenciasTotales();
}

$resultadoPendientesCierre = $recepcionController->listarIncidenciasPendientesCierre(); // Obtener los datos de las incidencias pendientes de cierre

if ($action === 'consultarCerradas') {
  // Depuración: mostrar los parámetros recibidos
  error_log("Fecha Inicio: " . $fechaInicio);
  error_log("Fecha Fin: " . $fechaFin);

  // Obtener los resultados de la búsqueda
  $resultadoIncidenciasCerradas = $cierreController->filtrarIncidenciasCerradasFecha($fechaInicio, $fechaFin);

  // Imprimir el resultado de la depuración
  error_log("Resultado de la consulta: " . print_r($resultadoIncidenciasCerradas, true));

  // Dibujar tabla de consultas
  $html = '';
  if (!empty($resultadoIncidenciasCerradas)) {
    $item = 1; // Iniciar contador para el ítem
    foreach ($resultadoIncidenciasCerradas as $cerradas) {
      $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
      $html .= '<td class="px-3 py-2 text-center">' . $item++ . '</td>'; // Columna de ítem
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($cerradas['INC_numero_formato']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($cerradas['fechaCierreFormateada']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($cerradas['INC_asunto']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($cerradas['INC_documento']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($cerradas['INC_codigoPatrimonial']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($cerradas['BIE_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($cerradas['ARE_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($cerradas['PRI_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center text-xs align-middle">';
      // Manejar el estado de la incidencia
      $estadoDescripcion = htmlspecialchars($cerradas['CON_descripcion']);
      $badgeClass = '';
      switch ($estadoDescripcion) {
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
      $html .= '<label class="badge ' . $badgeClass . '">' . $estadoDescripcion . '</label>';
      $html .= '</td></tr>';
    }
  } else {
    $html = '<tr><td colspan="10" class="text-center py-3">No se encontraron incidencias cerradas.</td></tr>';
  }

  // Devolver el HTML de las filas
  echo $html;
  exit;
} else {
  // Si no hay acción, obtener la lista de incidencias
  $resultadoIncidenciasCerradas = $cierreController->listarIncidenciasCerradas();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <title>Sistema de Gestión de Incidencias</title>
  <link rel="icon" href="public/assets/logo.ico">
  <!-- Meta -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="" />
  <meta name="keywords" content="">
  <meta name="author" content="GAMM95" />
  <!-- vendor css -->
  <link rel="stylesheet" href="dist/assets/css/style.css">

</head>

<body class="">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <!-- [ navigation menu ] start -->
  <?php
  if ($rol === 'Administrador') {
    include('app/View/partials/admin/navbar.php');
    include('app/View/partials/admin/header.php');
    include('app/View/Reporte/reportes.php');
  } else if ($rol === 'Soporte') {
    include('app/View/partials/soporte/navbar.php');
    include('app/View/partials/soporte/header.php');
    include('app/View/Reporte/reportes.php');
  }
  ?>
  <!-- [ Main Content ] end -->

  <!-- Required Js -->
  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>


  <!-- custom-chart js -->
  <script src="dist/assets/js/pages/dashboard-main.js"></script>
  <script src="./app/View/func/func_reportes.js"></script>

  <script src="./app/View/func/Reports/reporteTotalIncidencias.js"></script>
  <script src="./app/View/func/Reports/reportePendientesCierre.js"></script>
  <script src="./app/View/func/Reports/reportesPorArea.js"></script>
  <script src="./app/View/func/Reports/reportePorCodigoPatrimonial.js"></script>
  <script src="./app/View/func/Reports/reporteIncidenciasPorFecha.js"></script>
  <script src="./app/View/func/Reports/reporteCierresPorFecha.js"></script>
  <script src="./app/View/func/Reports/reporteNumeroIncidencia.js"></script>
  <script src="./app/View/func/Reports/reporteDetalleCierreNumIncidencia.js"></script>
  <script src="./app/View/func/Reports/reporteAreasPorFecha.js"></script>
  <script src="./app/View/func/Reports/reporteBienesPorFecha.js"></script>
  <script src="./app/View/func/Reports/reporteDetalleCierreReporte.js"></script>
  <script src="./app/View/func/Reports/reporteDetalleIncidenciaReporte.js"></script>



  <!-- Filtros o consultas por fecha para generar reportes -->
  <script src="./app/View/func/Consultas/func_consulta_totales_fecha.js"></script>
  <script src="./app/View/func/Consultas/func_consulta_cierres_fecha.js"></script>


  <script src="./app/View/func/Mantenedores/tipoBien.js"></script>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">

  <!-- Mensajes toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Generacion de PDF con js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.20/jspdf.plugin.autotable.min.js"></script>
  <!-- Buscador de combobox -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>