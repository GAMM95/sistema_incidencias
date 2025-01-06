<?php
session_start();

// Verificar si hay una sesión activa, si no redirigir
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php");
  exit();
}

require_once 'app/Controller/incidenciaController.php';
require_once 'app/Controller/recepcionController.php';
require_once 'app/Controller/asignacionController.php';
require_once 'app/Controller/mantenimientoController.php';
require_once 'app/Controller/cierreController.php';
$incidenciaController = new IncidenciaController();
$recepcionController = new RecepcionController();
$asignacionController = new AsignacionController();
$mantenimientoController = new MantenimientoController();
$cierreController = new CierreController();

require_once 'app/Controller/auditoriaController.php';
require_once 'app/Controller/usuarioController.php';
require_once 'app/Controller/personaController.php';
require_once 'app/Controller/areaController.php';
require_once 'app/Controller/bienController.php';
require_once 'app/Controller/categoriaController.php';
require_once 'app/Controller/solucionController.php';
$auditoriaController = new AuditoriaController();
$usuarioController = new UsuarioController();
$personaController = new PersonaController();
$areaController = new AreaController();
$bienController = new BienController();
$categoriaController = new CategoriaController();
$solucionController = new SolucionController();

$rol = $_SESSION['rol'];
$usuario = $_GET['codigoUsuario'] ?? '';  // Usuario para filtrar si es necesario
$action = $_GET['action'] ?? '';
$fechaInicio = $_GET['fechaInicio'] ?? '';
$fechaFin = $_GET['fechaFin'] ?? '';

// Función genérica para generar tablas de auditoría
function generarTabla($resultado, $itemCount, $columnas)
{
  $html = '';
  if (!empty($resultado)) {
    foreach ($resultado as $item) {
      $html .= '<tr class="hover:bg-green-100 hover:scale-[101%] transition-all border-b">';
      $html .= '<td class="px-3 py-2 text-center">' . $itemCount++ . '</td>'; // Columna de ítem

      foreach ($columnas as $columna) {
        $html .= '<td class="px-3 py-2 text-center">' . htmlspecialchars($item[$columna]) . '</td>';
      }
      $html .= '</tr>';
    }
  } else {
    $html = '<tr><td colspan="' . (count($columnas) + 1) . '" class="text-center py-3">No se encontraron registros.</td></tr>';
  }
  return $html;
}

// Función para manejar los filtros de fecha, usuario y obtener resultados
function obtenerRegistros($action, $controller, $usuario, $fechaInicio, $fechaFin)
{
  switch ($action) {
    case 'consultarEventosTotales':
      return $controller->consultarEventosTotales($usuario, $fechaInicio, $fechaFin);
    case 'consultarEventosLogin':
      return $controller->consultarEventosLogin($usuario, $fechaInicio, $fechaFin);
    case 'consultarEventosIncidencias':
      return $controller->consultarEventosIncidencias($usuario, $fechaInicio, $fechaFin);
      break;
    case 'consultarEventosRecepciones':
      return $controller->consultarEventosRecepciones($usuario, $fechaInicio, $fechaFin);
      break;
    case 'consultarEventosAsignaciones':
      return $controller->consultarEventosAsignaciones($usuario, $fechaInicio, $fechaFin);
      break;
      case 'consultarEventosMantenimiento':
        return $controller->consultarEventosMantenimiento($usuario, $fechaInicio, $fechaFin);
        break;
        case 'consultarEventosCierres':
          return $controller->consultarEventosCierres($usuario, $fechaInicio, $fechaFin);
          break;
    case 'consultarEventosUsuarios':
      return $controller->consultarEventosUsuarios($usuario, $fechaInicio, $fechaFin);
    case 'consultarEventosPersonas':
      return $controller->consultarEventosPersonas($usuario, $fechaInicio, $fechaFin);
    case 'consultarEventosAreas':
      return $controller->consultarEventosAreas($usuario, $fechaInicio, $fechaFin);
    case 'consultarEventosBienes':
      return $controller->consultarEventosBienes($usuario, $fechaInicio, $fechaFin);
    case 'consultarEventosCategorias':
      return $controller->consultarEventosCategorias($usuario, $fechaInicio, $fechaFin);
    case 'consultarEventosSoluciones':
      return $controller->consultarEventosSoluciones($usuario, $fechaInicio, $fechaFin);
    default:
      return [];
  }
}

// Mapear las acciones a los correspondientes campos a mostrar en la tabla
function obtenerColumnasParaAccion($action)
{
  switch ($action) {
    case 'consultarEventosTotales':
      return ['fechaFormateada', 'AUD_operacion', 'NombreCompleto', 'ROL_nombre', 'ARE_nombre', 'AUD_ip', 'AUD_nombreEquipo'];
    case 'consultarEventosLogin':
      return ['fechaFormateada', 'ROL_nombre', 'USU_nombre', 'NombreCompleto', 'ARE_nombre', 'AUD_ip', 'AUD_nombreEquipo'];
    case 'consultarEventosIncidencias':
      return ['fechaFormateada', 'NombreCompleto', 'AUD_operacion', 'referencia', 'ARE_nombre', 'AUD_ip', 'AUD_nombreEquipo'];
    case 'consultarEventosRecepciones':
      return ['fechaFormateada', 'NombreCompleto', 'AUD_operacion', 'referencia', 'AUD_ip', 'AUD_nombreEquipo'];
    case 'consultarEventosAsignaciones':
      return ['fechaFormateada', 'NombreCompleto', 'AUD_operacion', 'referencia', 'AUD_ip', 'AUD_nombreEquipo'];
      case 'consultarEventosMantenimiento':
        return ['fechaFormateada', 'NombreCompleto', 'AUD_operacion', 'referencia', 'AUD_ip', 'AUD_nombreEquipo'];
        case 'consultarEventosCierres':
          return ['fechaFormateada', 'NombreCompleto', 'AUD_operacion', 'referencia', 'AUD_ip', 'AUD_nombreEquipo'];
    case 'consultarEventosUsuarios':
      return ['fechaFormateada', 'UsuarioEvento', 'AUD_operacion', 'UsuarioReferencia', 'AUD_ip', 'AUD_nombreEquipo'];
    case 'consultarEventosPersonas':
      return ['fechaFormateada', 'UsuarioEvento', 'AUD_operacion', 'UsuarioReferencia', 'AUD_ip', 'AUD_nombreEquipo'];
    case 'consultarEventosAreas':
      return ['fechaFormateada', 'UsuarioEvento', 'AUD_operacion', 'referencia', 'AUD_ip', 'AUD_nombreEquipo'];
    case 'consultarEventosBienes':
      return ['fechaFormateada', 'UsuarioEvento', 'AUD_operacion', 'referencia', 'AUD_ip', 'AUD_nombreEquipo'];
    case 'consultarEventosCategorias':
      return ['fechaFormateada', 'UsuarioEvento', 'AUD_operacion', 'referencia', 'AUD_ip', 'AUD_nombreEquipo'];
    case 'consultarEventosSoluciones':
      return ['fechaFormateada', 'UsuarioEvento', 'AUD_operacion', 'referencia', 'AUD_ip', 'AUD_nombreEquipo'];
    default:
      return [];
  }
}

// Ejecutar la acción solicitada y generar la tabla de resultados
if ($action) {
  error_log("Fecha Inicio: " . $fechaInicio);
  error_log("Fecha Fin: " . $fechaFin);
  // Eventos totales
  if (($action === 'consultarEventosTotales')) {
    $resultado = obtenerRegistros($action, $auditoriaController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
    // Eventos de logeo
  } else if ($action === 'consultarEventosLogin') {
    $resultado = obtenerRegistros($action, $usuarioController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
    // Eventos de incidencias
  } else if ($action === 'consultarEventosIncidencias') {
    $resultado = obtenerRegistros($action, $incidenciaController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
  } else if ($action === 'consultarEventosRecepciones') {
    $resultado = obtenerRegistros($action, $recepcionController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
  } else if ($action === 'consultarEventosAsignaciones') {
    $resultado = obtenerRegistros($action, $asignacionController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
  } else if ($action === 'consultarEventosMantenimiento') {
    $resultado = obtenerRegistros($action, $mantenimientoController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
  } else if ($action === 'consultarEventosCierres') {
    $resultado = obtenerRegistros($action, $cierreController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
    // Eventos de mantenedores
  } else if ($action === 'consultarEventosUsuarios') {
    $resultado = obtenerRegistros($action, $usuarioController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
  } else if ($action === 'consultarEventosPersonas') {
    $resultado = obtenerRegistros($action, $personaController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
  } else if ($action === 'consultarEventosAreas') {
    $resultado = obtenerRegistros($action, $areaController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
  } else if ($action === 'consultarEventosBienes') {
    $resultado = obtenerRegistros($action, $bienController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
  } else if ($action === 'consultarEventosCategorias') {
    $resultado = obtenerRegistros($action, $categoriaController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
  } else if ($action === 'consultarEventosSoluciones') {
    $resultado = obtenerRegistros($action, $solucionController, $usuario, $fechaInicio, $fechaFin);
    $columnas = obtenerColumnasParaAccion($action);
    if ($resultado) {
      echo generarTabla($resultado, 1, $columnas);
    } else {
      error_log("No se encontraron registros.");
    }
  }
  exit();
}

// Acción por defecto: mostrar todas las tablas
$resultadoEventosTotales = $auditoriaController->listarEventosTotales();
$resultadoAuditoriaLogin = $usuarioController->listarEventosLogin();

$resultadoEventosIncidencias = $incidenciaController->listarEventosIncidencias();
$resultadoEventosRecepciones = $recepcionController->listarEventosRecepciones();
$resultadoEventosAsignaciones = $asignacionController->listarEventosAsignaciones();
$resultadoEventosMantenimiento = $mantenimientoController->listarEventosMantenimiento();
$resultadoEventosCierres = $cierreController->listarEventosCierres();

$resultadoAuditoriaEventosUsuarios = $usuarioController->listarEventosUsuarios();
$resultadoAuditoriaEventosPersonas = $personaController->listarEventosPersonas();
$resultadoAuditoriaEventosAreas = $areaController->listarEventosAreas();
$resultadoAuditoriaEventosBienes = $bienController->listarEventosBienes();
$resultadoAuditoriaEventosCategorias = $categoriaController->listarEventosCategorias();
$resultadoAuditoriaEventosSoluciones = $solucionController->listarEventosSoluciones();
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
    include('app/View/Auditoria/auditoria.php');
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
  <link href="src/output.css" rel="stylesheet">
  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">
  <!-- custom-chart js -->
  <script src="dist/assets/js/pages/dashboard-main.js"></script>
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
  <!-- Creacion de PDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.20/jspdf.plugin.autotable.min.js"></script>
  <!-- Framework CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="app/View/partials/scrollbar-styles.css">

  <!-- Funcionalidades enrutadas -->
  <script src="./public/func/ReportesAuditoria/EventosTotales/func_auditoria_eventos_totales.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosLogin/func_auditoria_eventos_login.js"></script>

  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Incidencias/func_auditoria_eventos_incidencias.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Recepciones/func_auditoria_eventos_recepciones.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Asignaciones/func_auditoria_eventos_asignaciones.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Mantenimiento/func_auditoria_eventos_mantenimiento.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Cierres/func_auditoria_eventos_cierres.js"></script>

  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Usuarios/func_auditoria_eventos_usuarios.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Personas/func_auditoria_eventos_personas.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Areas/func_auditoria_eventos_areas.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Bienes/func_auditoria_eventos_bienes.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Categorias/func_auditoria_eventos_categorias.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Soluciones/func_auditoria_eventos_soluciones.js"></script>

  <!-- Reportes de eventos totales -->
  <script src="./public/func/ReportesAuditoria/EventosTotales/Reports/reporteEventosTotales.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosTotales/Reports/reporteEventosTotalesFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosTotales/Reports/reporteEventosTotalesUsuario.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosTotales/Reports/reporteEventosTotalesUsuarioFecha.js"></script>

  <!-- Reportes de inicio de sesion -->
  <script src="./public/func/ReportesAuditoria/EventosLogin/Reports/reporteEventosLogin.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosLogin/Reports/reporteEventosLoginFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosLogin/Reports/reporteEventosLoginUsuario.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosLogin/Reports/reporteEventosLoginUsuarioFecha.js"></script>

  <!-- Reportes de eventos de incidencias -->
  <!-- Incidencias -->
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Incidencias/Reports/reporteEventosIncidencia.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Incidencias/Reports/reporteEventosIncidenciasFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Incidencias/Reports/reporteEventosIncidenciasUsuario.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Incidencias/Reports/reporteEventosIncidenciasUsuarioFecha.js"></script>
  <!-- Recepciones -->
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Recepciones/Reports/reporteEventosRecepciones.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Recepciones/Reports/reporteEventosRecepcionesFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Recepciones/Reports/reporteEventosRecepcionesUsuario.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Recepciones/Reports/reporteEventosRecepcionesUsuarioFecha.js"></script>
  <!-- Asignaciones -->
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Asignaciones/Reports/reporteEventosAsignaciones.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Asignaciones/Reports/reporteEventosAsignacionesFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Asignaciones/Reports/reporteEventosAsignacionesUsuario.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Asignaciones/Reports/reporteEventosAsignacionesUsuarioFecha.js"></script>
  <!-- Mantenimiento -->
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Mantenimiento/Reports/reporteEventosMantenimiento.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Mantenimiento/Reports/reporteEventosMantenimientoFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Mantenimiento/Reports/reporteEventosMantenimientoUsuario.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Mantenimiento/Reports/reporteEventosMantenimientoUsuarioFecha.js"></script>
  <!-- Cierres -->
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Cierres/Reports/reporteEventosCierres.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Cierres/Reports/reporteEventosCierresFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Cierres/Reports/reporteEventosCierresUsuario.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosIncidencias/Cierres/Reports/reporteEventosCierresUsuarioFecha.js"></script>

  <!-- Reporte de mantenedores -->
  <!-- Usuarios -->
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Usuarios/Reports/reporteEventosUsuarios.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Usuarios/Reports/reporteEventosUsuariosUser.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Usuarios/Reports/reporteEventosUsuariosFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Usuarios/Reports/reporteEventosUsuariosUserFecha.js"></script>
  <!-- Personas -->
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Personas/Reports/reporteEventosPersonas.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Personas/Reports/reporteEventosPersonasFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Personas/Reports/reporteEventosPersonasUsuario.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Personas/Reports/reporteEventosPersonasUsuarioFecha.js"></script>
  <!-- Areas -->
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Areas/Reports/reporteEventosAreas.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Areas/Reports/reporteEventosAreasFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Areas/Reports/reporteEventosAreasUsuario.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Areas/Reports/reporteEventosAreasUsuarioFecha.js"></script>
  <!-- Bienes -->
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Bienes/Reports/reporteEventosBienes.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Bienes/Reports/reporteEventosBienesFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Bienes/Reports/reporteEventosBienesUsuario.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Bienes/Reports/reporteEventosBienesUsuarioFecha.js"></script>
  <!-- Categorias -->
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Categorias/Reports/reporteEventosCategorias.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Categorias/Reports/reporteEventosCategoriasFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Categorias/Reports/reporteEventosCategoriasUsuario.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Categorias/Reports/reporteEventosCategoriasUsuarioFecha.js"></script>
  <!-- Soluciones -->
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Soluciones/Reports/reporteEventosSoluciones.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Soluciones/Reports/reporteEventosSolucionesFecha.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Soluciones/Reports/reporteEventosSolucionesUsuario.js"></script>
  <script src="./public/func/ReportesAuditoria/EventosMantenedores/Soluciones/Reports/reporteEventosSolucionesUsuarioFecha.js"></script>

</body>

</html>