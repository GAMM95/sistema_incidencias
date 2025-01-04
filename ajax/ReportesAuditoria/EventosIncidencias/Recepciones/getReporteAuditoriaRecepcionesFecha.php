<?php
require_once '../../../../config/conexion.php';

class ReporteAuditoriaRecepcionesFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteAuditoriaRecepcionesFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "EXEC sp_consultar_eventos_recepciones NULL, :fechaInicio, :fechaFin";
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
$fechaInicio = isset($_GET['fechaInicioEventosRecepciones']) ? $_GET['fechaInicioEventosRecepciones'] : null;
$fechaFin = isset($_GET['fechaFinEventosRecepciones']) ? $_GET['fechaFinEventosRecepciones'] : null;

$reporteAuditoriaRecepcionesFecha = new ReporteAuditoriaRecepcionesFecha();
$reporte = $reporteAuditoriaRecepcionesFecha->getReporteAuditoriaRecepcionesFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
