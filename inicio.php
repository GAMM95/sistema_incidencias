<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: inicio.php");
  exit();
}

require_once 'config/conexion.php';
require_once 'app/Model/IncidenciaModel.php';
require_once 'app/Model/RecepcionModel.php';
require_once 'app/Model/AsignacionModel.php';
require_once 'app/Model/CierreModel.php';
require_once 'app/Model/UsuarioModel.php';
require_once 'app/Model/AreaModel.php';
require_once 'app/Model/CategoriaModel.php';
require_once 'app/Controller/InicioController.php';

$conexion = new Conexion();
$conector = $conexion->getConexion();

$rol = $_SESSION['rol'];
$area = $_SESSION['codigoArea'];
// Creacion de instancias de los modelos
$incidenciasModel =  new IncidenciaModel();
$recepcionesModel = new RecepcionModel();
$asignacionModel = new AsignacionModel();
$cierresModel = new CierreModel();
$usuarioModel = new UsuarioModel();
$areaModel = new AreaModel();
$categoriaModel = new CategoriaModel();
$controller = new InicioController();


if ($rol === 'Administrador' || $rol === 'Soporte') {
  $cantidades = $controller->mostrarCantidadesAdministrador();
} else {
  $cantidades = $controller->mostrarCantidadesUsuario($area);
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
    include('app/View/Home/admin/inicio.php');
  } else  if ($rol === 'Soporte') {
    include('app/View/partials/soporte/navbar.php');
    include('app/View/partials/admin/header.php');
    include('app/View/Home/admin/inicio.php');
  } else if ($rol === 'Usuario') {
    include('app/View/partials/user/navbar.php');
    include('app/View/partials/user/header.php');
    include('app/View/Home/user/inicio.php');
  }
  ?>

  <!-- Required Js -->
  <script src="dist/assets/js/vendor-all.min.js"></script>
  <script src="dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="dist/assets/js/pcoded.min.js"></script>

  <!-- Apex Chart -->
  <script src="dist/assets/js/plugins/apexcharts.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">

  <!-- custom-chart js -->
  <script src="dist/assets/js/pages/dashboard-main.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">
  <script>
    // Evita que el usuario regrese a la página anterior al presionar "Atrás"
    history.pushState(null, null, location.href);
    window.onpopstate = function() {
      history.go(1); // Esto forzará que el navegador avance si intenta ir atrás
    };
  </script>

</body>

</html>