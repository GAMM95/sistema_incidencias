<?php
session_start();
// Verificar si no hay una sesión iniciada
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php"); // Redirigir a la página de inicio de sesión si no hay sesión iniciada
  exit();
}
$action = $_GET['action'] ?? '';
$state = $_GET['state'] ?? '';
$REC_numero = $_GET['REC_numero'] ?? '';

$rol = $_SESSION['rol'];
$area = $_SESSION['codigoArea'];

require_once 'app/Controller/recepcionController.php';
require_once 'app/Controller/incidenciaController.php';

$recepcionController = new RecepcionController();
$incidenciaController = new IncidenciaController();

// Paginacion de la tabla de incidencias sin recepcionar
$limit = 2; // Número de filas por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$start = ($page - 1) * $limit; // Calcula el índice de inicio
// Obtener el total de registros
$totalIncidenciasSinRecepcionar = $incidenciaController->contarIncidenciasRegistradas();
$totalPages = ceil($totalIncidenciasSinRecepcionar / $limit);
// Listar las incidencias para la pagina actual
$resultadoIncidencias = $incidenciaController->listarIncidenciasRegistradasPaginas($start, $limit);

// Paginacion para la tabla de incidencias recepcionadas
$limite = 5; // Numero de filas para la tabla de recepciones
$pageRecepciones =  isset($_GET['pageRecepciones']) ? (int)$_GET['pageRecepciones'] : 1; // pagina de la tabla actual
$inicio = ($pageRecepciones - 1) * $limite;
// Obtener el total de registros
$totalRecepciones = $recepcionController->contarRecepcionesRegistradas();
$totalPagesRecepciones = ceil($totalRecepciones / $limite);
// Listar incidencias recepcionadas
$resultadoRecepciones = $recepcionController->listarRecepcionesPaginacion($inicio, $limite);


switch ($action) {
  case 'registrar':
    $recepcionController->registrarRecepcion();
    break;
  case 'editar':
    $recepcionController->actualizarRecepcion();
    break;
  case 'eliminar':
    $recepcionController->eliminarRecepcion();
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
  if ($rol === 'Administrador') {
    include('app/View/partials/admin/navbar.php');
    include('app/View/partials/admin/header.php');
    include('app/View/Registrar/admin/registroRecepcion.php');
  } else if ($rol === 'Soporte') {
    include('app/View/partials/soporte/navbar.php');
    include('app/View/partials/soporte/header.php');
    include('app/View/Registrar/admin/registroRecepcion.php');
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

  <!-- Funcionalidades enrutadas -->
  <script src="./public/func/Registros/Recepcion/func_recepcion_admin.js"></script>

</body>

</html>