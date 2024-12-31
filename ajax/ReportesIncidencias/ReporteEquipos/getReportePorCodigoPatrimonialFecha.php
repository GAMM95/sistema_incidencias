<?php
require_once '../../../config/conexion.php';

class ReporteEquipoCodigoPatrimonialFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteEquipoCodigoPatrimonialFecha($codigoPatrimonial, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql  = "EXEC sp_filtrar_incidencias_equipo :codigoPatrimonial, :fechaInicio, :fechaFin"; 
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial, PDO::PARAM_STR);
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
$equipo = isset($_GET['codigoPatrimonialEquipo']) ? $_GET['codigoPatrimonialEquipo'] : null;
$fechaInicio = isset($_GET['fechaInicioIncidenciasEquipo']) ? $_GET['fechaInicioIncidenciasEquipo'] : null;
$fechaFin = isset($_GET['fechaFinIncidenciasEquipo']) ? $_GET['fechaFinIncidenciasEquipo'] : null;

if (!$fechaInicio || !$fechaFin) {
  echo json_encode(['error' => 'Las fechas de inicio y fin son requeridas']);
  exit;
}

$reporteEquipoCodigoPatrimonialFecha = new ReporteEquipoCodigoPatrimonialFecha();
$reporte = $reporteEquipoCodigoPatrimonialFecha->getReporteEquipoCodigoPatrimonialFecha($equipo, $fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
