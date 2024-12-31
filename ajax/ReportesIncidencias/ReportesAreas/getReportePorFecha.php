<?php
require_once '../../../config/conexion.php';

class ReportePorFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReportePorFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_filtrar_incidencias_area NULL, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
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
$fechaInicio = isset($_GET['fechaInicioIncidenciasArea']) ? $_GET['fechaInicioIncidenciasArea'] : null;
$fechaFin = isset($_GET['fechaFinIncidenciasArea']) ? $_GET['fechaFinIncidenciasArea'] : null;

if (!$fechaInicio || !$fechaFin) {
  echo json_encode(['error' => 'Las fechas de inicio y fin son requeridas']);
  exit;
}

$reporteFechas = new ReportePorFecha();
$reporte = $reporteFechas->getReportePorFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
