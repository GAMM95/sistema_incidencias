<?php
require_once '../../../config/conexion.php';

class ReportePorAreaFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReportePorAreaFecha($area, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_filtrar_incidencias_area :area, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      echo json_encode(['error' => $e->getMessage()]);
      return null;
    }
  }
}

// Obtención del parámetro 'area' desde la solicitud
$area = isset($_GET['areaIncidencia']) ?  $_GET['areaIncidencia'] : null;
$fechaInicio = isset($_GET['fechaInicioIncidenciasArea']) ? $_GET['fechaInicioIncidenciasArea'] : null;
$fechaFin = isset($_GET['fechaFinIncidenciasArea']) ? $_GET['fechaFinIncidenciasArea'] : null;

if (!$fechaInicio || !$fechaFin) {
  echo json_encode(['error' => 'Las fechas de inicio y fin son requeridas']);
  exit;
}

$reporteAreasFechas = new ReportePorAreaFecha();
$reporte = $reporteAreasFechas->getReportePorAreaFecha($area, $fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
