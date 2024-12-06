<?php
require_once '../../../config/conexion.php';

class ReporteTotalEquipos extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteTotalEquipos()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vw_reporte_incidencias_equipos
            ORDER BY INC_numero DESC";
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$reporteTotalEquipos = new ReporteTotalEquipos();
$reporte = $reporteTotalEquipos->getReporteTotalEquipos();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
