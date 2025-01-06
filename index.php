<?php
session_start(); // Debes iniciar la sesión antes de utilizar $_SESSION

// Obtener el valor de la variable "action" de la URL o asignar una cadena vacía si no está presente
$action = $_GET['action'] ?? '';
$state = $_GET['state'] ?? '';

// Importar el controlador necesario
require_once 'app/Controller/loginController.php';

// Crear una instancia del controlador de inicio de sesión
$controller = new LoginController();

// Realizar una selección basada en el valor de "action"
switch ($action) {
    // Si el valor de "action" es "login"
  case 'login':
    // Procesar el inicio de sesión
    $controller->procesarLogin();
    break;
    // Si el valor de "action" es cualquier otro o está vacío
  default:
    // Mostrar el formulario de inicio de sesión
    $controller->mostrarFormLogin();
    break;
}
?>

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
  <script src="dist/assets/js/plugins/jspdf.min.js"></script>
  <script src="dist/assets/js/plugins/jspdf.plugin.autotable.min.js"></script>

  <!-- Archivos cdn -->
  <!-- Mensajes toastr -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> -->
  <!-- Buscador de combos -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
  <!-- Creacion de PDF -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.20/jspdf.plugin.autotable.min.js"></script> -->
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->

</head>

<body class="">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <!-- Funcionalidades enrutadas -->
  <script src="./public/func/Login/func_login.js"></script>
</body>

</html>