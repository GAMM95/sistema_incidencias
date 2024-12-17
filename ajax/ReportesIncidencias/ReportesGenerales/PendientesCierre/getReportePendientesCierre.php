<?php
require_once '../../../../config/conexion.php';

class ReportePendientesCierre extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReportePendientesCierre()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vw_incidencias_pendientes
            ORDER BY INC_numero DESC"; //Ordenar por la última hora
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$reportePendientesCierre = new ReportePendientesCierre();
$reporte = $reportePendientesCierre->getReportePendientesCierre();

header('Content-Type: application/json');
echo json_encode($reporte);