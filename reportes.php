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

// Funcion generia para generar las tablas de reportes
function generarTablaTotales($resultado, $itemCount, $columnas)
{
  $html = '';
  if (!empty($resultado)) {
    foreach ($resultado as $item) {
      $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
      $html .= '<td class="px-3 py-2 text-center">' . $itemCount++ . '</td>'; // Columna de ítem

      // Generar las celdas para las columnas normales
      foreach ($columnas as $columna) {
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($item[$columna]) . '</td>';
      }

      // Verificar el estado y asignar la clase de badge correspondiente
      $estadoDescripcion = htmlspecialchars($item['Estado']);
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

      // Añadir la celda con el badge en la última columna
      $html .= '<td class="px-3 py-2 text-center"><span class="badge ' . $badgeClass . '">' . $estadoDescripcion . '</span></td>';

      $html .= '</tr>';
    }
  } else {
    $html = '<tr><td colspan="' . (count($columnas) + 1) . '" class="text-center py-3">No se encontraron registros.</td></tr>';
  }
  return $html;
}

// Funcion para generar la tabla de incidencias cerradas
function generarTablaCerradas($resultado, $itemCount, $columnas)
{
  $html = '';
  if (!empty($resultado)) {
    foreach ($resultado as $item) {
      $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
      $html .= '<td class="px-3 py-2 text-center">' . $itemCount++ . '</td>'; // Columna de ítem

      // Generar las celdas para las columnas normales
      foreach ($columnas as $columna) {
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($item[$columna]) . '</td>';
      }

      // Verificar el estado y asignar la clase de badge correspondiente
      $estadoDescripcion = htmlspecialchars($item['CON_descripcion']);
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

      // Añadir la celda con el badge en la última columna
      $html .= '<td class="px-3 py-2 text-center"><span class="badge ' . $badgeClass . '">' . $estadoDescripcion . '</span></td>';

      $html .= '</tr>';
    }
  } else {
    $html = '<tr><td colspan="' . (count($columnas) + 1) . '" class="text-center py-3">No se encontraron registros.</td></tr>';
  }
  return $html;
}

// Funcion generia para generar las tablas de reportes
function generarTabla($resultado, $itemCount, $columnas)
{
  $html = '';
  if (!empty($resultado)) {
    foreach ($resultado as $item) {
      $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
      $html .= '<td class="px-3 py-2 text-center">' . $itemCount++ . '</td>'; // Columna de ítem

      foreach ($columnas as $columna) {
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($item[$columna]) . '</td>';
      }
      $html .= '</tr>';
    }
  } else {
    $html = '<tr><td colspan="' . (count($columnas) + 1) . '" class="text-center py-3">No se encontraron registros.</td></tr>';
  }
  return $html;
}

// Función para manejar los filtros de fecha, usuario y obtener resultados
function obtenerRegistros($action, $controller, $usuario = null, $fechaInicio, $fechaFin)
{
  switch ($action) {
    case 'consultarIncidenciasTotales':
      return $controller->filtrarIncidenciasTotalesFecha($fechaInicio, $fechaFin);
    case 'consultarIncidenciasCerradas':
      return $controller->filtrarIncidenciasCerradasFecha($fechaInicio, $fechaFin);
    default:
      return [];
  }
}


// Mapear las acciones a los correspondientes campos a mostrar en la tabla
function obtenerColumnasParaAccion($action)
{
  switch ($action) {
    case 'consultarIncidenciasTotales':
      return ['INC_numero_formato', 'fechaIncidenciaFormateada', 'INC_asunto', 'INC_documento', 'INC_codigoPatrimonial', 'BIE_nombre', 'ARE_nombre', 'PRI_nombre', 'CON_descripcion'];
    case 'consultarIncidenciasCerradas':
      return ['INC_numero_formato', 'fechaCierreFormateada', 'INC_asunto', 'INC_documento', 'INC_codigoPatrimonial', 'BIE_nombre', 'ARE_nombre', 'PRI_nombre', 'Usuario'];
    default:
      return [];
  }
}

// Ejecutar la acción solicitada y generar la tabla de resultados
if ($action) {
  error_log("Fecha Inicio: " . $fechaInicio);
  error_log("Fecha Fin: " . $fechaFin);

  // Verificar la acción y ejecutar la correspondiente
  if ($action == 'consultarIncidenciasTotales') {
    // Consultar y generar la tabla de incidencias totales
    $resultadoTotales = obtenerRegistros($action, $incidenciaController, null, $fechaInicio, $fechaFin);
    $columnasTotales = obtenerColumnasParaAccion($action);
    echo generarTablaTotales($resultadoTotales, 1, $columnasTotales);
  } elseif ($action == 'consultarIncidenciasCerradas') {
    // Consultar y generar la tabla de incidencias cerradas
    $resultadoCerradas = obtenerRegistros($action, $cierreController, null, $fechaInicio, $fechaFin);
    $columnasCerradas = obtenerColumnasParaAccion($action);
    echo generarTablaCerradas($resultadoCerradas, 1, $columnasCerradas);
  }

  // Terminar el script después de generar la tabla
  exit();
}

// Acción por defecto: mostrar todas las tablas
$resultadoIncidenciasTotales = $incidenciaController->listarIncidenciasTotales();
$resultadoPendientesCierre = $recepcionController->listarIncidenciasPendientesCierre();
$resultadoIncidenciasCerradas = $cierreController->listarIncidenciasCerradas();
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

  <!-- Funcionalidades -->
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasTotales/func_reportesGenerales.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasCerradas/func_reportesCerradas.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReporteDetalle/func_reporteDetalles.js"></script>

  <!-- Reportes incidencias totales -->
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasTotales/Reports/reporteTotalIncidencias.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasTotales/Reports/reporteIncidenciasTotalesFecha.js"></script>

  <!-- Rporte pendiente de cierre -->
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/PendientesCierre/reportePendientesCierre.js"></script>

  <!-- Reportes incidencias cerradas -->
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasCerradas/Reports/reporteTotalIncidenciasCerradas.js"></script>

  <!-- Reportes de detalle -->
  <script src="./app/View/func/ReportesIncidencias/ReporteDetalle/Reports/reporteDetalleIncidencia.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReporteDetalle/Reports/reporteDetalleCierre.js"></script>



  <!-- <script src="./app/View/func/Reports/reporteTotalIncidencias.js"></script> -->
  <!-- <script src="./app/View/func/Reports/reportesPorArea.js"></script>
  <script src="./app/View/func/Reports/reportePorCodigoPatrimonial.js"></script> -->
  <!-- <script src="./app/View/func/Reports/reporteIncidenciasPorFecha.js"></script> -->
  <!-- <script src="./app/View/func/Reports/reporteCierresPorFecha.js"></script>
  <script src="./app/View/func/Reports/reporteNumeroIncidencia.js"></script>
  <script src="./app/View/func/Reports/reporteDetalleCierreNumIncidencia.js"></script>
  <script src="./app/View/func/Reports/reporteAreasPorFecha.js"></script>
  <script src="./app/View/func/Reports/reporteBienesPorFecha.js"></script>
  <script src="./app/View/func/Reports/reporteDetalleCierreReporte.js"></script>
  <script src="./app/View/func/Reports/reporteDetalleIncidenciaReporte.js"></script> -->

<!-- <script src="./app/View/func/func_reportes.js"></script> -->

  <!-- Filtros o consultas por fecha para generar reportes -->
  <!-- <script src="./app/View/func/Consultas/func_consulta_totales_fecha.js"></script>
  <script src="./app/View/func/Consultas/func_consulta_cierres_fecha.js"></script> -->


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