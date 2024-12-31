<?php
require_once '../../../../config/conexion.php';

class ReporteIncidenciasAsignadasFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteIncidenciasAsignadasFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "EXEC sp_filtrar_incidencias_asignadas NULL, :fechaInicio, :fechaFin";
    $stmt = $conector->prepare($sql);
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
$fechaInicio = isset($_GET['fechaInicioIncidenciasAsignadas']) ? $_GET['fechaInicioIncidenciasAsignadas'] : null;
$fechaFin = isset($_GET['fechaFinIncidenciasAsignadas']) ? $_GET['fechaFinIncidenciasAsignadas'] : null;

if (!$fechaInicio || !$fechaFin) {
  echo json_encode(['error' => 'Las fechas de inicio y fin son requeridas']);
  exit;
}

$reporteIncidenciasAsignadasFecha = new ReporteIncidenciasAsignadasFecha();
$reporte = $reporteIncidenciasAsignadasFecha->getReporteIncidenciasAsignadasFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
