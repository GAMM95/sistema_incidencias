<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}
$action = $_GET['action'] ?? '';
require_once './app/Controller/IncidenciaController.php';
require_once './app/Model/IncidenciaModel.php';

$rol = $_SESSION['rol'];
$area = $_SESSION['codigoArea'];

$incidenciaController = new IncidenciaController();
$incidenciaModel = new IncidenciaModel();

// Capturar los datos del formulario
$area = $_GET['area'] ?? '';
$codigoPatrimonial = $_GET['codigoPatrimonial'] ?? '';
$fechaInicio = $_GET['fechaInicio'] ?? '';
$fechaFin = $_GET['fechaFin'] ?? '';
$resultadoBusqueda = NULL;

if ($action === 'consultar') {
  // Depuración: mostrar los parámetros recibidos
  error_log("Área: " . $area);
  error_log("Código patrimonial: " . $codigoPatrimonial);
  error_log("Fecha Inicio: " . $fechaInicio);
  error_log("Fecha Fin: " . $fechaFin);

  // Obtener los resultados de la búsqueda
  $resultadoBusqueda = $incidenciaController->consultarIncidenciasTotales($area, $codigoPatrimonial, $fechaInicio, $fechaFin);

  // Imprimir el resultado de la depuración
  error_log("Resultado de la consulta: " . print_r($resultadoBusqueda, true));

  // Dibujar tabla de consultas
  $html = '';
  if (!empty($resultadoBusqueda)) {
    $item = 1; // Iniciar contador para el ítem
    foreach ($resultadoBusqueda as $incidencia) {
      $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
      $html .= '<td class="px-3 py-2 text-center">' . $item++ . '</td>'; // Columna de ítem
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['INC_numero_formato']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['ARE_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['fechaIncidenciaFormateada']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['CAT_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['INC_asunto']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['INC_documento']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['INC_codigoPatrimonial']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($incidencia['PRI_nombre']) . '</td>';
      $html .= '<td class="px-3 py-2 text-center text-xs align-middle">';
      
      // Manejar el estado de la incidencia
      $estadoDescripcion = htmlspecialchars($incidencia['Estado']);
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
    $html = '<tr><td colspan="10" class="text-center py-3">No se encontraron incidencias pendientes de cierre.</td></tr>';
  }
  
  // Devolver el HTML de las filas
  echo $html;
  exit;
} else {
  // Si no hay acción, obtener la lista de las incidencias
  $resultadoBusqueda = $incidenciaModel->listarIncidenciasTotalesAdministrador();
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

  <!-- [ navigation menu ] start -->
  <?php
  if ($rol === 'Administrador') {
    include('app/View/partials/admin/navbar.php');
    include('app/View/partials/admin/header.php');
    include('app/View/Consultar/admin/consultaIncidenciaGeneral.php');
  } else if ($rol === 'Soporte') {
    include('app/View/partials/soporte/navbar.php');
    include('app/View/partials/soporte/header.php');
    include('app/View/Consultar/admin/consultaIncidenciaGeneral.php');
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

  <script src="./app/View/func/func_consulta_incidencia_total.js"></script>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Incluir CSS de Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Incluir JS de Select2 -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

</html>