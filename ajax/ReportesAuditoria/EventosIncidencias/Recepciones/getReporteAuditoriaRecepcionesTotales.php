<?php
require_once '../../../../config/conexion.php';

class ReporteAuditoriaRecepcionesTotales extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteAuditoriaRecepcionesTotales()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vw_eventos_recepciones
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

$reporteAuditoriaRecepcionesTotales = new ReporteAuditoriaRecepcionesTotales();
$reporte = $reporteAuditoriaRecepcionesTotales->getReporteAuditoriaRecepcionesTotales();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
