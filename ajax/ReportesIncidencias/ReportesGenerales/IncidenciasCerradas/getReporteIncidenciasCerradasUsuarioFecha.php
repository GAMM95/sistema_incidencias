<?php
require_once '../../../../config/conexion.php';

class ReporteIncidenciasCerradasUsuarioFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteIncidenciasCerradasUsuarioFecha($usuario, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "EXEC sp_filtrar_incidencias_cerradas :usuario, :fechaInicio, :fechaFin";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':fechaFin', $fechaFin);

    try {
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo json_encode(['error' => 'Error al obtener los datos: ' . $e->getMessage()]);
      exit;
    }
    return $resultado;
  }
}

// Validación de las fechas
$usuario = isset($_GET['usuarioIncidenciasCerradas']) ?  $_GET['usuarioIncidenciasCerradas'] : null;
$fechaInicio = isset($_GET['fechaInicioIncidenciasCerradas']) ? $_GET['fechaInicioIncidenciasCerradas'] : null;
$fechaFin = isset($_GET['fechaFinIncidenciasCerradas']) ? $_GET['fechaFinIncidenciasCerradas'] : null;

if (!$fechaInicio || !$fechaFin) {
  echo json_encode(['error' => 'Las fechas de inicio y fin son requeridas']);
  exit;
}

$reporteIncidenciasCerradasUsuarioFecha = new ReporteIncidenciasCerradasUsuarioFecha();
$reporte = $reporteIncidenciasCerradasUsuarioFecha->getReporteIncidenciasCerradasUsuarioFecha($usuario, $fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();