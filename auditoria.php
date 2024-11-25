<?php
session_start();

// Verificar si hay una sesión activa, si no redirigir
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require_once 'app/Controller/auditoriaController.php';
$auditoriaController = new AuditoriaController();

$rol = $_SESSION['rol'];
$usuario = $_GET['codigoUsuario'] ?? '';  // Usuario para filtrar si es necesario
$action = $_GET['action'] ?? '';
$fechaInicio = $_GET['fechaInicio'] ?? '';
$fechaFin = $_GET['fechaFin'] ?? '';

// Función genérica para generar tablas de auditoría
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
function obtenerRegistros($action, $controller, $usuario, $fechaInicio, $fechaFin)
{
    switch ($action) {
        case 'consultarEventosTotales':
            return $controller->consultarEventosTotales($usuario, $fechaInicio, $fechaFin);
        case 'consultarEventosLogin':
            return $controller->consultarRegistrosInicioSesion($fechaInicio, $fechaFin);
        case 'listarRegistrosIncidencias':
            return $controller->consultarRegistrosIncidencias($fechaInicio, $fechaFin);
        case 'listarRegistrosRecepciones':
            return $controller->consultarRegistrosRecepciones($fechaInicio, $fechaFin);
        default:
            return [];
    }
}

// Mapear las acciones a los correspondientes campos a mostrar en la tabla
function obtenerColumnasParaAccion($action)
{
    switch ($action) {
        case 'consultarEventosTotales':
            return ['fechaFormateada', 'AUD_operacion', 'ROL_nombre', 'USU_nombre', 'NombreCompleto', 'ARE_nombre', 'AUD_ip', 'AUD_nombreEquipo'];
        case 'consultarEventosLogin':
            return ['fechaFormateada', 'ROL_nombre', 'USU_nombre', 'NombreCompleto', 'ARE_nombre', 'AUD_ip', 'AUD_nombreEquipo'];
        case 'listarRegistrosIncidencias':
        case 'listarRegistrosRecepciones':
            return ['fechaFormateada', 'NombreCompleto', 'INC_numero_formato', 'ARE_nombre', 'AUD_ip', 'AUD_nombreEquipo'];
        default:
            return [];
    }
}

// Ejecutar la acción solicitada y generar la tabla de resultados
if ($action) {
    error_log("Fecha Inicio: " . $fechaInicio);
    error_log("Fecha Fin: " . $fechaFin);

    // Pasar correctamente el parámetro $usuario en la función obtenerRegistros
    $resultado = obtenerRegistros($action, $auditoriaController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);

    echo generarTabla($resultado, 1, $columnas);
    exit();
}

// Acción por defecto: mostrar todas las tablas
$resultadoEventosTotales = $auditoriaController->listarEventosTotales();
$resultadoAuditoriaLogin = $auditoriaController->listarRegistrosInicioSesion();
$resultadoAuditoriaRegistroIncidencias = $auditoriaController->listarRegistrosIncidencias();
$resultadoAuditoriaRegistroRecepciones = $auditoriaController->listarRegistrosRecepciones();
$resultadoAuditoriaRegistroAsignaciones = $auditoriaController->listarRegistrosAsignaciones();
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
    include('app/View/Auditoria/auditoria.php');
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
  <script src="./app/View/func/ReportesAuditoria/EventosTotales/func_auditoria_eventos_totales.js"></script>
  <script src="./app/View/func/ReportesAuditoria/EventosLogin/func_auditoria_eventos_login.js"></script>


  <script src="./app/View/func/ReportesAuditoria/func_auditoria_registro_incidencia.js"></script>
  <script src="./app/View/func/ReportesAuditoria/func_auditoria_registro_recepcion.js"></script>

  <!-- Reportes de auditoría -->
  <script src="./app/View/func/ReportesAuditoria/EventosTotales/Reportes/reporteEventosTotales.js"></script>
  <script src="./app/View/func/ReportesAuditoria/EventosTotales/Reportes/reporteEventosTotalesFecha.js"></script>
  <script src="./app/View/func/ReportesAuditoria/EventosTotales/Reportes/reporteEventosTotalesUsuario.js"></script>
  <script src="./app/View/func/ReportesAuditoria/EventosTotales/Reportes/reporteEventosTotalesUsuarioFecha.js"></script>


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