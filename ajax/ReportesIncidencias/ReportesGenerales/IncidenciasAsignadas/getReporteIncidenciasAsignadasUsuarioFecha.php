
<?php
require_once '../../../../config/conexion.php';

class ReporteIncidenciasAsignadasUsuarioFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteIncidenciasAsignadasUsuarioFecha($usuario, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "EXEC sp_filtrar_incidencias_asignadas :usuario, :fechaInicio, :fechaFin";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
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
$usuario = isset($_GET['usuarioIncidenciasAsignadas']) ? $_GET['usuarioIncidenciasAsignadas'] : null;
$fechaInicio = isset($_GET['fechaInicioIncidenciasAsignadas']) ? $_GET['fechaInicioIncidenciasAsignadas'] : null;
$fechaFin = isset($_GET['fechaFinIncidenciasAsignadas']) ? $_GET['fechaFinIncidenciasAsignadas'] : null;

if (!$fechaInicio || !$fechaFin) {
  echo json_encode(['error' => 'Las fechas de inicio y fin son requeridas']);
  exit;
}

$reporteIncidenciasAsignadasUsuarioFecha = new ReporteIncidenciasAsignadasUsuarioFecha();
$reporte = $reporteIncidenciasAsignadasUsuarioFecha->getReporteIncidenciasAsignadasUsuarioFecha($usuario, $fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();