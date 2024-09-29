<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}
$action = $_GET['action'] ?? '';
$CodArea = $_GET['ARE_codigo'] ?? '';

require_once 'app/Controller/AreaController.php';
require_once 'app/Model/AreaModel.php';

// Crear una instancia del controlador CategoriaController
$areaController = new AreaController();
$areaModel = new AreaModel();

if ($CodArea != '') {
  $AreaRegistrada = $areaModel->obtenerAreaPorId($CodArea);
} else {
  $AreaRegistrada = null;
}

// Metodo para listar areas
$resultado = $areaModel->listarArea();

switch ($action) {
  case 'registrar':
    $areaController->registrarArea();
    break;
  case 'editar':
    $areaController->actualizarArea();
    break;
  case 'habilitar':
    $areaController->habilitarArea();
    break;
  case 'deshabilitar':
    $areaController->deshabilitarArea();
    break;
  case 'filtrar':
    $areaController->filtrarAreas();
    break;
  default:
    break;
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
  include('app/View/partials/admin/navbar.php');
  ?>
  <!-- [ navigation menu ] end -->

  <!-- [ Header ] start -->
  <?php
  include('app/View/partials/admin/header.php');
  ?>
  <!-- [ Header ] end -->

  <!-- [ Main Content ] start -->
  <?php
  include('app/View/Mantenimiento/mantenedorArea.php');
  ?>
  <!-- [ Main Content ] end -->


  <!-- Required Js -->
  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>
  <script src="dist/assets/js/pages/dashboard-main.js"></script>
  <script src="./app/View/func/func_area.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">
</body>

</html>