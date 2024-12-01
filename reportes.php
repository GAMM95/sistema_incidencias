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

require_once './app/Controller/incidenciaController.php';
require_once './app/Controller/recepcionController.php';
require_once './app/Controller/cierreController.php';
require_once './app/Controller/asignacionController.php';
require_once './app/Controller/mantenimientoController.php';

$incidenciaController = new IncidenciaController();
$recepcionController = new RecepcionController();
$cierreController = new CierreController();
$asignacionController = new AsignacionController();
$mantenimientoController = new MantenimientoController();

$fechaInicio = $_GET['fechaInicio'] ?? '';
$fechaFin = $_GET['fechaFin'] ?? '';
$categoria = $_GET['codigoCategoria'] ?? '';
$usuario = $_GET['codigoUsuario'] ?? '';
$area = $_GET['codigoArea'] ?? '';
$equipo = $_GET['codigoPatrimonial'] ?? '';

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

// Funcion para generar la tabla de incidencias asignadas
function generarTablaAsignaciones($resultado, $itemCount, $columnas)
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
function generarTablaAfectados($resultado, $itemCount, $columnas)
{
  error_log(print_r($resultado, true));  // Para ver qué datos tiene $resultado

  $html = '';
  if (!empty($resultado)) {
    foreach ($resultado as $item) {
      $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
      $html .= '<td class="px-3 py-2 text-center">' . $itemCount++ . '</td>'; // Columna de ítem

      // Iterar sobre las columnas y verificar si la clave existe
      foreach ($columnas as $columna) {
        $valor = isset($item[$columna]) ? htmlspecialchars($item[$columna]) : 'N/A'; // Verificar si la clave existe
        $html .= '<td class="px-3 py-2 text-center">' . $valor . '</td>';
      }
      $html .= '</tr>';
    }
  } else {
    $html = '<tr><td colspan="' . (count($columnas) + 1) . '" class="text-center py-3">No se encontraron registros.</td></tr>';
  }
  return $html;
}


// Función genérica para generar las tablas de reportes
function generarTabla($resultado, $itemCount, $columnas)
{
  $html = '';

  if (!empty($resultado)) {
    foreach ($resultado as $item) {
      $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
      $html .= '<td class="px-3 py-2 text-center">' . $itemCount++ . '</td>';

      // Generar las columnas dinámicas
      foreach ($columnas as $columna) {
        $valor = isset($item[$columna]) ? htmlspecialchars($item[$columna]) : 'N/A'; // Verificar si la clave existe
        $html .= '<td class="px-3 py-2 text-center">' . $valor . '</td>';
      }

      // Badge para 'CON_descripcion'
      $condicionDescripcion = htmlspecialchars($item['CON_descripcion']);
      $badgeClassCondicion = '';
      switch ($condicionDescripcion) {
        case 'OPERATIVO':
        case 'SOLUCIONADO':
          $badgeClassCondicion = 'badge-light-info';
          break;
        case 'INOPERATIVO':
        case 'NO SOLUCIONADO':
          $badgeClassCondicion = 'badge-light-danger';
          break;
        default:
          $badgeClassCondicion = 'badge-light-secondary';
          break;
      }

      // Celda con el badge de condición
      $html .= '<td class="px-3 py-2 text-center"><span class="badge ' . $badgeClassCondicion . '">' . $condicionDescripcion . '</span></td>';

      // Badge para 'Estado'
      $estadoDescripcion = htmlspecialchars($item['Estado']);
      $badgeClassEstado = '';
      switch ($estadoDescripcion) {
        case 'ABIERTO':
          $badgeClassEstado = 'badge-light-danger';
          break;
        case 'RECEPCIONADO':
          $badgeClassEstado = 'badge-light-success';
          break;
        case 'CERRADO':
          $badgeClassEstado = 'badge-light-primary';
          break;
        default:
          $badgeClassEstado = 'badge-light-secondary';
          break;
      }

      // Celda con el badge de estado
      $html .= '<td class="px-3 py-2 text-center"><span class="badge ' . $badgeClassEstado . '">' . $estadoDescripcion . '</span></td>';

      // Cierre de la fila
      $html .= '</tr>';
    }
  } else {
    // Si no hay resultados, mostrar un mensaje de "No se encontraron registros."
    $html = '<tr><td colspan="' . (count($columnas) + 1) . '" class="text-center py-3">No se encontraron registros.</td></tr>';
  }

  return $html;
}


// Función para manejar los filtros de fecha, usuario y obtener resultados
function obtenerRegistros($action, $controller, $usuario, $area, $equipo, $categoria, $fechaInicio, $fechaFin)
{
  switch ($action) {
    case 'consultarIncidenciasTotales':
      return $controller->filtrarIncidenciasTotalesFecha($fechaInicio, $fechaFin);
    case 'consultarIncidenciasCerradas':
      return $controller->filtrarIncidenciasCerradas($usuario, $fechaInicio, $fechaFin);
    case 'consultarIncidenciasAsignadas':
      return $controller->filtrarIncidenciasAsignadas($usuario, $fechaInicio, $fechaFin);
    case 'consultarIncidenciasAreas':
      return $controller->filtrarIncidenciasArea($area, $fechaInicio, $fechaFin);
    case 'consultarIncidenciasEquipos':
      return $controller->filtrarIncidenciasEquipo($equipo, $fechaInicio, $fechaFin);
    case 'consultarEquiposMasAfectados':
      return $controller->filtrarEquiposMasAfectados($equipo, $fechaInicio, $fechaFin);
    case 'consultarAreasMasAfectadas':
      return $controller->filtrarAreasMasAfectadas($categoria, $fechaInicio, $fechaFin);
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
    case 'consultarIncidenciasAsignadas':
      return ['INC_numero_formato', 'ARE_nombre', 'INC_asunto', 'INC_codigoPatrimonial', 'BIE_nombre', 'fechaAsignacionFormateada', 'fechaMantenimientoFormateada', 'usuarioSoporte', 'tiempoMantenimientoFormateado'];
    case 'consultarIncidenciasAreas':
      return ['INC_numero_formato', 'fechaIncidenciaFormateada', 'INC_asunto', 'INC_documento', 'INC_codigoPatrimonial', 'BIE_nombre', 'PRI_nombre'];
    case 'consultarEquiposMasAfectados':
      return ['codigoPatrimonial', 'nombreBien', 'nombreArea', 'cantidadIncidencias'];
    case 'consultarAreasMasAfectadas':
      return ['areaMasIncidencia', 'cantidadIncidencias'];
    default:
      return [];
  }
}

// Ejecutar la acción solicitada y generar la tabla de resultados
if ($action) {
  error_log("Fecha Inicio: " . $fechaInicio);
  error_log("Fecha Fin: " . $fechaFin);
  error_log("Acción solicitada: " . $action);

  // Verificar la acción y ejecutar la correspondiente
  if ($action == 'consultarIncidenciasTotales') {
    // Pasar los argumentos necesarios, aunque algunos pueden ser null si no se necesitan
    $resultadoTotales = obtenerRegistros($action, $incidenciaController, null, null, null, null, $fechaInicio, $fechaFin);
    $columnasTotales = obtenerColumnasParaAccion($action);
    if ($resultadoTotales) {
      echo generarTablaTotales($resultadoTotales, 1, $columnasTotales);
    } else {
      error_log("No se encontraron registros para 'consultarIncidenciasTotales'.");
    }
  } else if ($action == 'consultarIncidenciasCerradas') {
    $resultadoCerradas = obtenerRegistros($action, $cierreController, $usuario, null, null, null, $fechaInicio, $fechaFin);
    $columnasCerradas = obtenerColumnasParaAccion($action);
    if ($resultadoCerradas) {
      echo generarTablaCerradas($resultadoCerradas, 1, $columnasCerradas);
    } else {
      error_log("No se encontraron registros para 'consultarIncidenciasCerradas'.");
    }
  } else if ($action == 'consultarIncidenciasAsignadas') {
    $resultadoAsignadas = obtenerRegistros($action, $asignacionController, $usuario, null, null, null, $fechaInicio, $fechaFin);
    $columnasAsignadas = obtenerColumnasParaAccion($action);
    if ($resultadoAsignadas) {
      echo generarTablaAsignaciones($resultadoAsignadas, 1, $columnasAsignadas);
    } else {
      error_log("No se encontraron registros para 'consultarIncidenciasAsignadas'.");
    }
  } else if ($action == 'consultarIncidenciasAreas') {
    $resultadoArea = obtenerRegistros($action, $incidenciaController, null, $area, null, null, $fechaInicio, $fechaFin);
    $columnasArea = obtenerColumnasParaAccion($action);
    if ($resultadoArea) {
      echo generarTabla($resultadoArea, 1, $columnasArea, 'Incidencias por &aacute;rea');
    } else {
      error_log("No se encontraron registros para 'consultarIncidenciasAreas'.");
    }
  } else if ($action == 'consultarEquiposMasAfectados') {
    $resultadoEquiposMasAfectados = obtenerRegistros($action, $incidenciaController, null, null, $equipo, null, $fechaInicio, $fechaFin);
    $columnasEquiposMasAfectados = obtenerColumnasParaAccion($action);
    if ($resultadoEquiposMasAfectados) {
      echo generarTablaAfectados($resultadoEquiposMasAfectados, 1, $columnasEquiposMasAfectados);
    } else {
      error_log("No se encontraron registros para 'consultarEquiposMasAfectados'.");
    }
  } else if ($action == 'consultarAreasMasAfectadas') {
    $resultadoAreaMasAfectadas = obtenerRegistros($action, $incidenciaController, null, null, null, $categoria, $fechaInicio, $fechaFin);
    $columnasAreaMasAfectadas = obtenerColumnasParaAccion($action);
    if ($resultadoAreaMasAfectadas) {
      echo generarTablaAfectados($resultadoAreaMasAfectadas, 1, $columnasAreaMasAfectadas);
    } else {
      error_log("No se encontraron registros para 'consultarAreaMasAfectadas'.");
    }
  } else {
    error_log("Acción no reconocida: " . $action);
  }

  exit();
}


// Acción por defecto: mostrar todas las tablas
$resultadoIncidenciasTotales = $incidenciaController->listarIncidenciasTotales();
$resultadoPendientesCierre = $recepcionController->listarIncidenciasPendientesCierre();
$resultadoIncidenciasCerradas = $cierreController->listarIncidenciasCerradas();
$resultadoIncidenciasAsignadas = $mantenimientoController->listarIncidenciasMantenimiento();
$resultadoIncidenciasAreas = $incidenciaController->listarIncidenciasAreaEquipo();
$resultadoEquiposMasAfectados = $incidenciaController->listarEquiposMasAfectados();
$resultadoAreaMasAfectadas = $incidenciaController->listarAreasMasAfectadas();
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
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasAsignadas/func_reportesAsignaciones.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReporteDetalle/func_reporteDetalles.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesAreas/func_reportesAreas.js"></script>


  <script src="./app/View/func/ReportesIncidencias/ReportesOtros/AreasAfectadas/func_reportesAreasAfectadas.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesOtros/EquiposAfectados/func_reportesEquiposAfectados.js"></script>




  <!-- Reportes incidencias totales -->
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasTotales/Reports/reporteTotalIncidencias.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasTotales/Reports/reporteIncidenciasTotalesFecha.js"></script>
  <!-- Rporte pendiente de cierre -->
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/PendientesCierre/reportePendientesCierre.js"></script>
  <!-- Reportes incidencias cerradas -->
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasCerradas/Reports/reporteTotalIncidenciasCerradas.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasCerradas/Reports/reporteIncidenciasCerradasFecha.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasCerradas/Reports/reporteIncidenciasCerradasUsuario.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasCerradas/Reports/reporteIncidenciasCerradasUsuarioFecha.js"></script>
  <!-- Reportes incidencias asignadas -->
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasAsignadas/Reports/reporteTotalIncidenciasAsignadas.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasAsignadas/Reports/reporteIncidenciasAsignadasFecha.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasAsignadas/Reports/reporteIncidenciasAsignadasUsuario.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesGenerales/IncidenciasAsignadas/Reports/reporteIncidenciasAsignadasUsuarioFecha.js"></script>

  <!-- Reportes de detalle -->
  <script src="./app/View/func/ReportesIncidencias/ReporteDetalle/Reports/reporteDetalleIncidencia.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReporteDetalle/Reports/reporteDetalleCierre.js"></script>

  <!-- Reportes por area -->
  <script src="./app/View/func/ReportesIncidencias/ReportesAreas/Reports/reporteIncidenciasPorArea.js"></script>

  <!-- Reportes otros -->
  <script src="./app/View/func/ReportesIncidencias/ReportesOtros/AreasAfectadas/Reports/reporteTotalAreasAfectadas.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesOtros/AreasAfectadas/Reports/reporteAreasPorCategoria.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesOtros/AreasAfectadas/Reports/reporteAreasPorCategoriaFecha.js"></script>
  <script src="./app/View/func/ReportesIncidencias/ReportesOtros/EquiposAfectados/Reports/reporteEquiposMasAfectados.js"></script>  


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


  <!-- <script src="./app/View/func/Mantenedores/tipoBien.js"></script> -->

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