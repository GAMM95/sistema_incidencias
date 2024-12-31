<?php
require_once '../../../../config/conexion.php';

class ReporteIncidenciasTotalesFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteIncidenciasTotalesFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "EXEC sp_filtrar_incidencias_totales_fecha  :fechaInicio, :fechaFin";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':fechaFin', $fechaFin);

    try {
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo json_encode(['error' => $e->getMessage()]);
      exit;
    }
    return $resultado;
  }
}

// Obtención del parámetro 'fechaInicio' y 'fechaFin' desde la solicitud
$fechaInicio = isset($_GET['fechaInicioIncidenciasTotales']) ? $_GET['fechaInicioIncidenciasTotales'] : '';
$fechaFin = isset($_GET['fechaFinIncidenciasTotales']) ? $_GET['fechaFinIncidenciasTotales'] : '';

$reporteIncidenciasTotalesFecha = new ReporteIncidenciasTotalesFecha();
$reporte = $reporteIncidenciasTotalesFecha->getReporteIncidenciasTotalesFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
