<?php
require_once '../../../config/conexion.php';

class ReporteDetalleIncidencia extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteDetalleIncidencia()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vw_incidencias";
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$reporteDetalleIncidencia = new ReporteDetalleIncidencia();
$reporte = $reporteDetalleIncidencia->getReporteDetalleIncidencia();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();