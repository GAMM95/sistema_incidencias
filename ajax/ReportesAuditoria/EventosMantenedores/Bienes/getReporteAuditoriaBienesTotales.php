<?php
require_once '../../../../config/conexion.php';

class ReporteAuditoriaBienesTotales extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteAuditoriaBienesTotales()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vw_eventos_bienes
            ORDER BY AUD_fecha DESC, AUD_hora DESC";
    $stmt = $conector->prepare($sql);
    try {
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
      exit;
    }
    return $resultado;
  }
}

$reporteAuditoriaBienesTotales = new ReporteAuditoriaBienesTotales();
$reporte = $reporteAuditoriaBienesTotales->getReporteAuditoriaBienesTotales();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
