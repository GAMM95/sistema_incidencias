<?php
require_once '../../../../config/conexion.php';

class ReporteTotalEquipoMasAfectado extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteTotalEquipoMasAfectado()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vw_equipos_mas_afectados
            ORDER BY cantidadIncidencias DESC";
    $stmt = $conector->prepare($sql);
    try {
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo json_encode(['Error de query' => $e->getMessage()]);
      exit;
    }
    return $resultado;
  }
}

$reporteTotalEquipoMasAfectado = new ReporteTotalEquipoMasAfectado();
$reporte = $reporteTotalEquipoMasAfectado->getReporteTotalEquipoMasAfectado();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
