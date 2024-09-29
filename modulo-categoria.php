<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}
$action = $_GET['action'] ?? '';
$CodCategoria = $_GET['CAT_codigo'] ?? '';

require_once 'app/Controller/CategoriaController.php';
require_once 'app/Model/CategoriaModel.php';

$categoriaController = new CategoriaController();
$categoriaModel = new CategoriaModel();

// Listar tabla de categorias registradas
$resultado = $categoriaModel->listarCategorias();

if ($CodCategoria != '') {
  $CategoriaRegistrada = $categoriaModel->obtenerCategoriaPorId($CodCategoria);
} else {
  $CategoriaRegistrada = null;
}

switch ($action) {
  case 'registrar':
    $categoriaController->registrarCategoria();
    break;
  case 'editar':
    $categoriaController->actualizarCategoria();
    break;
  case 'eliminar':
    $categoriaController->eliminarCategoria();
    break;
  case 'habilitar':
    $categoriaController->habilitarCategoria();
    break;
  case 'deshabilitar':
    $categoriaController->deshabilitarCategoria();
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

  <?php include('app/View/partials/admin/navbar.php'); ?>
  <?php include('app/View/partials/admin/header.php'); ?>
  <?php include('app/View/Mantenimiento/mantenedorCategoria.php'); ?>

  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>
  <script src="dist/assets/js/pages/dashboard-main.js"></script>
  <script src="./app/View/func/func_categoria.js"></script>
  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>