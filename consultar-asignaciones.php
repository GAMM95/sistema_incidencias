<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}
$action = $_GET['action'] ?? '';
require_once 'app/Controller/asignacionController.php';
require_once 'app/Controller/mantenimientoController.php';

$rol = $_SESSION['rol'];
// $area = $_SESSION['codigoArea'];
$asignacionController = new asignacionController();
$mantenimientoController = new mantenimientoController();

// Capturar los datos del fomrulario

$rol = $_SESSION['rol'];
$usuario = $_SESSION['codigoUsuario'];
$usuarioAsignado = $_GET['usuarioAsignado'] ?? '';
$codigoPatrimonial = $_GET['codigoPatrimonial'] ?? '';
$fechaInicio = $_GET['fechaInicio'] ?? '';
$fechaFin = $_GET['fechaFin'] ?? '';
$resultadoBusqueda = NULL;

if ($rol === 'Administrador') {
  if ($action === 'consultar') {
    // Depuración: mostrar los parámetros recibidos
    error_log("Usuario asignado: " . $usuarioAsignado);
    error_log("Codigo Patrimonial: " . $codigoPatrimonial);
    error_log("Fecha Inicio: " . $fechaInicio);
    error_log("Fecha Fin: " . $fechaFin);
    // Obtener los resultados de la búsqueda
    $resultadoBusqueda = $asignacionController->consultarAsignaciones($usuarioAsignado, $codigoPatrimonial, $fechaInicio, $fechaFin);

    error_log("Resultado de la consulta: " . print_r($resultadoBusqueda, true));

    // Dibujar tabla de consultas
    $html = '';
    if (!empty($resultadoBusqueda)) {
      $item = 1; // Iniciar contador para el ítem
      foreach ($resultadoBusqueda as $asignaciones) {
        $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
        $html .= '<td class="px-3 py-2 text-center">' . $item++ . '</td>'; // Columna de ítem
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['INC_numero_formato']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['ARE_nombre']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['INC_asunto']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['INC_codigoPatrimonial']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['BIE_nombre']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['fechaAsignacionFormateada']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['fechaMantenimientoFormateada']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['usuarioSoporte']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['tiempoMantenimientoFormateado']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center text-xs align-middle">';
        // Manejar el estado de la incidencia
        $estadoDescripcion = htmlspecialchars($asignaciones['Estado']);
        $badgeClass = '';
        switch ($estadoDescripcion) {
          case 'EN PROCESO':
            $badgeClass = 'badge-light-danger';
            break;
          case 'RESUELTO':
            $badgeClass = 'badge-light-primary';
            break;
          case 'CERRADO':
            $badgeClass = 'badge-light-secondary';
            break;
          default:
            break;
        }

        $html .= '<label class="badge ' . $badgeClass . '">' . $estadoDescripcion . '</label>';
        $html .= '</td></tr>';
      }
    } else {
      $html = '<tr><td colspan="11" class="text-center py-3">A&uacute;n no se ha realizado mantenimiento a las incidencias asignadas.</td></tr>';
    }
    echo $html;
    exit;
  } else if ($action === 'listarAdmin') {
    $resultadoBusqueda = $mantenimientoController->listarIncidenciasMantenimiento();
  } else {
    $resultadoBusqueda = $mantenimientoController->listarIncidenciasMantenimiento();
  }
} else if ($rol === 'Soporte') {
  if ($action === 'consultar') {
    // Depuración: mostrar los parámetros recibidos
    error_log("Usuario asignado: " . $usuario);
    error_log("Codigo Patrimonial: " . $codigoPatrimonial);
    error_log("Fecha Inicio: " . $fechaInicio);
    error_log("Fecha Fin: " . $fechaFin);
    // Obtener los resultados de la búsqueda
    $resultadoBusqueda = $mantenimientoController->consultarIncidenciasMantenimientoSoporte($usuario, $codigoPatrimonial, $fechaInicio, $fechaFin);

    error_log("Resultado de la consulta: " . print_r($resultadoBusqueda, true));

    // Dibujar tabla de consultas
    $html = '';
    if (!empty($resultadoBusqueda)) {
      $item = 1; // Iniciar contador para el ítem
      foreach ($resultadoBusqueda as $asignaciones) {
        $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
        $html .= '<td class="px-3 py-2 text-center">' . $item++ . '</td>'; // Columna de ítem
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['INC_numero_formato']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['ARE_nombre']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['INC_asunto']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['INC_codigoPatrimonial']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['BIE_nombre']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['fechaAsignacionFormateada']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['fechaMantenimientoFormateada']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($asignaciones['tiempoMantenimientoFormateado']) . '</td>';
        $html .= '<td class="px-3 py-2 text-center text-xs align-middle">';
        // Manejar el estado de la incidencia
        $estadoDescripcion = htmlspecialchars($asignaciones['Estado']);
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
            break;
        }

        $html .= '<label class="badge ' . $badgeClass . '">' . $estadoDescripcion . '</label>';
        $html .= '</td></tr>';
      }
    } else {
      $html = '<tr><td colspan="10" class="text-center py-3">A&uacute;n no se ha realizado mantenimiento a las incidencias asignadas.</td></tr>';
    }
    echo $html;
    exit;
  } else if ($action === 'listarSoporte') {
    $resultadoBusqueda = $mantenimientoController->listarIncidenciasMantenimientoSoporte($usuario);
  } else {
    $resultadoBusqueda = $mantenimientoController->listarIncidenciasMantenimientoSoporte($usuario);
  }
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
    include('app/View/Consultar/admin/consultaAsignaciones.php');
  } else if ($rol === 'Soporte') {
    include('app/View/partials/soporte/navbar.php');
    include('app/View/partials/soporte/header.php');
    include('app/View/Consultar/soporte/consultaAsignaciones.php');
  }

  ?>
  <!-- [ Main Content ] end -->

  <!-- Required Js -->
  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>
  <!-- Iconos de Feather -->
  <script src="dist/assets/js/plugins/feather.min.js"></script>
  <!-- Select2 -->
  <link href="dist/assets/css/plugins/select2.min.css" rel="stylesheet">
  <script src="dist/assets/js/plugins/select2.min.js"></script>
  <!-- Mensajes toastr -->
  <script src="dist/assets/js/plugins/toastr.min.js"></script>
  <link rel="stylesheet" href="dist/assets/css/plugins/toastr.min.css">
  <!-- Framework CSS -->
  <link href="dist/assets/css/plugins/tailwind.min.css" rel="stylesheet">
  <link href="src/output.css" rel="stylesheet">
  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">
  <!-- Generacion de pdf -->
  <script src="dist/assets/js/plugins/jspdf.umd.min.js"></script>
  <script src="dist/assets/js/plugins/jspdf.plugin.autotable.min.js"></script>

  <!-- Archivos cdn -->
  <!-- Mensajes toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Buscador de combos -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <!-- Creacion de PDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.20/jspdf.plugin.autotable.min.js"></script>

  <!-- funcionalidades enrutadas -->
  <script src="./public/func/Consultas/func_consulta_asignaciones.js"></script>

</body>

</html>