<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php");
  exit();
}

$action = $_GET['action'] ?? '';
$state = $_GET['state'] ?? '';
$ASI_codigo = $_GET['ASI_codigo'] ?? '';
$rol = $_SESSION['rol'];
$usuario = $_SESSION['codigoUsuario'] ?? '';

require_once 'app/Controller/MantenimientoController.php';
require_once 'app/Model/MantenimientoModel.php';
require_once 'app/Model/asignacionModel.php';

$mantenimientoController = new MantenimientoController();
$mantenimientoModel = new MantenimientoModel();
$asignacionModel = new AsignacionModel();

$resultadoAsignaciones = $asignacionModel->listarAsignacionesSoporte($usuario);

if ($ASI_codigo != '') {
  global $asignacionRegistrada;
  $asignacionRegistrada = $asignacionModel->obtenerAsignacionesPorId($ASI_codigo);
} else {
  $asignacionRegistrada = null;
}

switch ($action) {
  case 'habilitar':
    $mantenimientoController->resolverIncidencia();
    break;
  case 'deshabilitar':
    $mantenimientoController->encolarIncidencia();
    break;
  default:
    break;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <title>Sistema de Gesti&oacute;n de Incidencias</title>
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
  if ($rol === 'Soporte') {
    include('app/View/partials/soporte/navbar.php');
    include('app/View/partials/soporte/header.php');
    include('app/View/Registrar/soporte/registroMantenimiento.php');
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
  <!-- <script src="./app/View/func/func_asignacion.js"></script> -->
  <script src="./app/View/func/func_mantenimiento.js"></script>

  <!-- Framework CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">
  <!-- Mensajes toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Buscador de opciones en combos -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <!-- Creacion de PDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.20/jspdf.plugin.autotable.min.js"></script>
</body>

</html>