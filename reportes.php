<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}
$action = $_GET['action'] ?? '';
$state = $_GET['state'] ?? '';

$rol = $_SESSION['rol'];
$area = $_SESSION['codigoArea'];

require_once './app/Controller/incidenciaController.php';
require_once './app/Controller/recepcionController.php';
$incidenciaController = new IncidenciaController();
$recepcionController = new RecepcionController();

$resultadoIncidenciasTotales = $incidenciaController->listarIncidenciasTotales(); // Obtener los datos de las incidencias totales
$resultadoPendientesCierre = $recepcionController->listarIncidenciasPendientesCierre(); // Obtener los datos de las incidencias pendientes de cierre

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
    include('app/View/Reporte/reportes.php');
  } else if ($rol === 'Soporte') {
    include('app/View/partials/soporte/navbar.php');
    include('app/View/partials/soporte/header.php');
    include('app/View/Reporte/reportes.php');
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
  <script src="./app/View/func/func_reportes.js"></script>

  <script src="./app/View/func/Reports/reporteTotalIncidencias.js"></script>
  <script src="./app/View/func/Reports/reportePendientesCierre.js"></script>
  <script src="./app/View/func/Reports/reportesPorArea.js"></script>
  <script src="./app/View/func/Reports/reportePorCodigoPatrimonial.js"></script>
  <script src="./app/View/func/Reports/reporteIncidenciasPorFecha.js"></script>
  <script src="./app/View/func/Reports/reporteCierresPorFecha.js"></script>
  <script src="./app/View/func/Reports/reporteNumeroIncidencia.js"></script>
  <script src="./app/View/func/Reports/reporteDetalleCierreNumIncidencia.js"></script>
  <script src="./app/View/func/Reports/reporteAreasPorFecha.js"></script>
  <script src="./app/View/func/Reports/reporteBienesPorFecha.js"></script>
  <script src="./app/View/func/Reports/reporteDetalleCierreReporte.js"></script>
  <script src="./app/View/func/Reports/reporteDetalleIncidenciaReporte.js"></script>

  <script src="./app/View/func/Mantenedores/tipoBien.js"></script>

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