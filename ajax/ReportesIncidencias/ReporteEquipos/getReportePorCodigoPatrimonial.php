<?php
require_once '../../../config/conexion.php';

class ReporteEquipoCodigoPatrimonial extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteEquipoCodigoPatrimonial($codigoPatrimonial)
  {
    $conector = parent::getConexion();
    $sql  = "EXEC sp_filtrar_incidencias_equipo :codigoPatrimonial, NULL, NULL";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial, PDO::PARAM_STR);

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

$reporteEquipoCodigoPatrimonial = new ReporteEquipoCodigoPatrimonial();
$reporte = $reporteEquipoCodigoPatrimonial->getReporteEquipoCodigoPatrimonial($equipo);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();