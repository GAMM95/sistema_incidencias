<?php
require_once '../../../../config/conexion.php';

class ReporteIncidenciasAsignadasTotales extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteIncidenciasAsignadasTotales()
  {
    $conector = parent::getConexion();
    $sql = "SELECT * FROM vista_incidencias_matenimiento
            ORDER BY 
            ultimaFecha DESC, --Ordenar por la última fecha
            ultimaHora DESC";  //Ordenar por la última hora
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$reporteIncidenciasAsignadasTotales = new ReporteIncidenciasAsignadasTotales();
$reporte = $reporteIncidenciasAsignadasTotales->getReporteIncidenciasAsignadasTotales();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
