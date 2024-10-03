<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: inicio.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}
$action = $_GET['action'] ?? '';
$state = $_GET['state'] ?? '';
$USU_codigo = $_GET['USU_codigo'] ?? '';

require_once 'app/Controller/UsuarioController.php';
require_once 'app/Model/UsuarioModel.php';

$usuarioController = new UsuarioController();
$usuarioModel = new UsuarioModel();

// Listar las incidencias para la pagina actual
$resultado = $usuarioModel->listarUsuarios();

if ($USU_codigo != '') {
  global $usuarioRegistrado;
  $usuarioRegistrado = $usuarioModel->obtenerUsuarioPorID($USU_codigo);
} else {
  $usuarioRegistrado = null;
}

switch ($action) {
  case 'registrar':
    $usuarioController->registrarUsuario();
    break;
  case 'editar':
    $usuarioController->actualizarUsuario();
    break;
  case 'filtrar':
    $usuarioController->filtrarUsuarios();
    break;
  case 'habilitar':
    $usuarioController->habilitarUsuario();
    break;
  case 'deshabilitar':
    $usuarioController->deshabilitarUsuario();
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

<body class="">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>

  <?php include('app/View/partials/admin/navbar.php'); ?>
  <?php include('app/View/partials/admin/header.php'); ?>
  <?php include('app/View/Mantenimiento/mantenedorUsuario.php'); ?>

  <!-- Required Js -->
  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>

  <script src="./app/View/func/func_usuario.js"></script>

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