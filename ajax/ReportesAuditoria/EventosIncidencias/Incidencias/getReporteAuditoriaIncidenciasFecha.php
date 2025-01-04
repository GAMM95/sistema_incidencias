<?php
require_once '../../../../config/conexion.php';

class ReporteAuditoriaIncidenciasFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteAuditoriaIncidenciasFecha($fechaInicio, $fechaFin)  
  {
    $conector = parent::getConexion();
    $sql = "EXEC sp_consultar_eventos_incidencias NULL, :fechaInicio, :fechaFin";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':fechaFin', $fechaFin);
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

// Validación de los parámetros
$fechaInicio = isset($_GET['fechaInicioEventosIncidencias']) ? $_GET['fechaInicioEventosIncidencias'] : null;
$fechaFin = isset($_GET['fechaFinEventosIncidencias']) ? $_GET['fechaFinEventosIncidencias'] : null;

$reporteAuditoriaIncidenciasFecha = new ReporteAuditoriaIncidenciasFecha();
$reporte = $reporteAuditoriaIncidenciasFecha->getReporteAuditoriaIncidenciasFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
