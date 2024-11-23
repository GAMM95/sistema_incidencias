<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: inicio.php");
  exit();
}
$rol = $_SESSION['rol'];
$action = $_GET['action'] ?? '';

require_once 'app/Controller/solucionController.php';

$solucionController = new solucionController();

// Listar tabla de soluciones registradas
$resultado = $solucionController->listarSoluciones();


switch ($action) {
  case 'registrar':
    $solucionController->registrarSolucion();
    break;
  case 'editar':
    $solucionController->actualizarSolucion();
    break;
  case 'habilitar':
    $solucionController->habilitarSolucion();
    break;
  case 'deshabilitar':
    $solucionController->deshabilitarSolucion();
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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="" />
  <meta name="keywords" content="">
  <meta name="author" content="GAMM95" />
  <link rel="stylesheet" href="dist/assets/css/style.css">
</head>

<body>
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
    include('app/View/Mantenimiento/mantenedorSoluciones.php');
  } else if ($rol === 'Soporte') {
    include('app/View/partials/soporte/navbar.php');
    include('app/View/partials/soporte/header.php');
    include('app/View/Mantenimiento/mantenedorSoluciones.php');
  }
  ?>
  <!-- [ Main Content ] end -->


  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>
  <script src="dist/assets/js/pages/dashboard-main.js"></script>
  <script src="./app/View/func/Mantenedores/func_solucion.js"></script>

  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>