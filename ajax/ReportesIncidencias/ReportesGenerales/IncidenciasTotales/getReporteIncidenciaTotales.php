<?php
require_once '../../../../config/conexion.php';

class ReporteIncidenciasTotales extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteIncidenciasTotales()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vw_reporte_incidencias_totales
            ORDER BY INC_numero_formato DESC";
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$reporteIncidenciasTotales = new ReporteIncidenciasTotales();
$reporte = $reporteIncidenciasTotales->getReporteIncidenciasTotales();

header('Content-Type: application/json');
echo json_encode($reporte);