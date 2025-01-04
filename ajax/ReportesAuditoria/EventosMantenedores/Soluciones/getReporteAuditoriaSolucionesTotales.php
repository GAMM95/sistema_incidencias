<?php
require_once '../../../../config/conexion.php';

class ReporteAuditoriaSolucionesTotales extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteAuditoriaSolucionesTotales()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vw_eventos_soluciones
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

$reporteAuditoriaSolucionesTotales = new ReporteAuditoriaSolucionesTotales();
$reporte = $reporteAuditoriaSolucionesTotales->getReporteAuditoriaSolucionesTotales();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
