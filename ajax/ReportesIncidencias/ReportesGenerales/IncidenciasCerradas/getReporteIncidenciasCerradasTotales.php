<?php
require_once '../../../../config/conexion.php';

class getReporteIncidenciasCerradasTotales extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteIncidenciasCerradasTotales()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vista_cierres
            ORDER BY 
            ultimaFecha DESC, --Ordenar por la última fecha
            ultimaHora DESC";  //Ordenar por la última hora
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$reporteIncidenciasCerradasTotales = new getReporteIncidenciasCerradasTotales();
$reporte = $reporteIncidenciasCerradasTotales->getReporteIncidenciasCerradasTotales();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
