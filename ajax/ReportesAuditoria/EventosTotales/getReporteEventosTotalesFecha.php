<?php
require_once '../../../config/conexion.php';  

class getReporteEventosTotalesFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteEventosTotalesFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "EXEC sp_consultar_eventos_totales NULL, :fechaInicio, :fechaFin";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
    $stmt->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);

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
$fechaInicio = isset($_GET['fechaInicioEventosTotales']) ? $_GET['fechaInicioEventosTotales'] : null;
$fechaFin = isset($_GET['fechaFinEventosTotales']) ? $_GET['fechaFinEventosTotales'] : null;

$reporteEventosTotalesFecha = new getReporteEventosTotalesFecha();
$reporte = $reporteEventosTotalesFecha->getReporteEventosTotalesFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
