<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}

require_once 'app/Controller/auditoriaController.php';

$auditoriaController = new AuditoriaController();

$rol = $_SESSION['rol'];
$action = $_GET['action'] ?? '';
$fechaInicio = $_GET['fechaInicio'] ?? '';
$fechaFin = $_GET['fechaFin'] ?? '';

function generarTabla($resultado, $itemCount)
{
  $html = '';
  if (!empty($resultado)) {
    foreach ($resultado as $item) {
      $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
      $html .= '<td class="px-3 py-2 text-center">' . $itemCount++ . '</td>'; // Columna de ítem
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($item['fechaFormateada']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($item['ROL_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($item['USU_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($item['NombreCompleto']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($item['ARE_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($item['AUD_ip']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($item['AUD_nombreEquipo']) . '</td>';
      $html .= '</tr>';
    }
  } else {
    $html = '<tr><td colspan="8" class="text-center py-3">No se encontraron registros.</td></tr>';
  }
  return $html;
}

switch ($action) {
  case 'listarRegistrosInicioSesion':
    error_log("Fecha Inicio: " . $fechaInicio);
    error_log("Fecha Fin: " . $fechaFin);
    $resultadoAuditoriaLogin = $auditoriaController->consultarRegistrosInicioSesion($fechaInicio, $fechaFin);
    echo generarTabla($resultadoAuditoriaLogin, 1);
    exit;

  case 'listarRegistrosIncidencias':
    error_log("Fecha Inicio: " . $fechaInicio);
    error_log("Fecha Fin: " . $fechaFin);
    $resultadoAuditoriaRegistroIncidencias = $auditoriaController->consultarRegistrosIncidencias($fechaInicio, $fechaFin);
    echo generarTabla($resultadoAuditoriaRegistroIncidencias, 1);
    exit;

  case 'listarRegistrosRecepciones':
    error_log("Fecha Inicio: " . $fechaInicio);
    error_log("Fecha Fin: " . $fechaFin);
    $resultadoAuditoriaRegistroRecepciones = $auditoriaController->consultarRegistrosRecepciones($fechaInicio, $fechaFin);
    echo generarTabla($resultadoAuditoriaRegistroRecepciones, 1);
    exit;

  default:
    // Si no hay acción, obtener la lista de registros por defecto
    $resultadoAuditoriaLogin = $auditoriaController->listarRegistrosInicioSesion();
    $resultadoAuditoriaRegistroIncidencias = $auditoriaController->listarRegistrosIncidencias();
    $resultadoAuditoriaRegistroRecepciones = $auditoriaController->listarRegistrosRecepciones();
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
  <script src="./app/View/func/Auditoria/func_auditoria_login.js"></script>
  <script src="./app/View/func/Auditoria/func_auditoria_registro_incidencia.js"></script>

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