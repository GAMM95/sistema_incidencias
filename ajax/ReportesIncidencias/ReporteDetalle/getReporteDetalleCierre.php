<?php
require_once '../../../config/conexion.php';

class ReporteDetalleCierre extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteDetalleCierre()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vw_cierres";
    $stmt = $conector->prepare($sql);
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

$reporteDetalleCierre = new ReporteDetalleCierre();
$reporte = $reporteDetalleCierre->getReporteDetalleCierre();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
