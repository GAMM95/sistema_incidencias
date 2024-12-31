<?php
require_once '../../../config/conexion.php';

class ReporteEquipoFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteEquipoFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql  = "EXEC sp_filtrar_incidencias_equipo NULL, :fechaInicio, :fechaFin"; 
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':fechaFin', $fechaFin);

    try {
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo json_encode(['error' => 'Error al obtener los datos: ' . $e->getMessage()]);
      exit;
    }
    return $resultado;
  }
}

// Validación de las fechas
$fechaInicio = isset($_GET['fechaInicioIncidenciasEquipo']) ? $_GET['fechaInicioIncidenciasEquipo'] : null;
$fechaFin = isset($_GET['fechaFinIncidenciasEquipo']) ? $_GET['fechaFinIncidenciasEquipo'] : null;

if (!$fechaInicio || !$fechaFin) {
  echo json_encode(['error' => 'Las fechas de inicio y fin son requeridas']);
  exit;
}

$reporteEquipoFecha = new ReporteEquipoFecha();
$reporte = $reporteEquipoFecha->getReporteEquipoFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();