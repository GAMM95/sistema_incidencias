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

$usuarioController = new UsuarioController();

// Listar las incidencias para la pagina actual
$resultado = $usuarioController->listarUsuarios();

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
  case 'restablecerContraseña':
    $usuarioController->restablecerContraseña();
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
  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">

  <!-- Archivos cdn -->
  <!-- Framework CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Mensajes toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Buscador de combos -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- Funcionalidades enrutadas -->
  <script src="./app/View/func/Mantenedores/func_usuario.js"></script>
  <script src="./app/View/func/Mantenedores/func_usuario_restablecerPassword.js"></script>

</body>

</html>