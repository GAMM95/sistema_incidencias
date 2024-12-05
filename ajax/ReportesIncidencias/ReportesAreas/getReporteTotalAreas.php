<?php
require_once '../../../config/conexion.php';

class ReporteTotalAreas extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteTotalAreas()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vw_reporte_incidencias_area_equipo
            ORDER BY INC_numero DESC";
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$reporteTotalAreas = new ReporteTotalAreas();
$reporte = $reporteTotalAreas->getReporteTotalAreas();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
