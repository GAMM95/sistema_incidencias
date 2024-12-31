<?php
require_once '../../../../config/conexion.php';

class ReporteIncidenciasCerradasFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteIncidenciasCerradasFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "EXEC sp_filtrar_incidencias_cerradas NULL, :fechaInicio, :fechaFin";
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
$fechaInicio = isset($_GET['fechaInicioIncidenciasCerradas']) ? $_GET['fechaInicioIncidenciasCerradas'] : null;
$fechaFin = isset($_GET['fechaFinIncidenciasCerradas']) ? $_GET['fechaFinIncidenciasCerradas'] : null;

if (!$fechaInicio || !$fechaFin) {
  echo json_encode(['error' => 'Las fechas de inicio y fin son requeridas']);
  exit;
}

$reporteIncidenciasCerradasFecha = new ReporteIncidenciasCerradasFecha();
$reporte = $reporteIncidenciasCerradasFecha->getReporteIncidenciasCerradasFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
