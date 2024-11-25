<?php
require_once '../../../config/conexion.php';  

class ReporteEventosLogin extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteEventosLogin()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vw_eventos_totales
            ORDER BY AUD_fecha DESC, AUD_hora DESC";
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$reporteEventosLogin = new ReporteEventosLogin();
$reporte = $reporteEventosLogin->getReporteEventosLogin();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();