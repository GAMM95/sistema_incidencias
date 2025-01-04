<?php
require_once '../../../../config/conexion.php';

class ReporteAuditoriaCategoriasFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteAuditoriaCategoriasFecha($fechaInicio, $fechaFin)  
  {
    $conector = parent::getConexion();
    $sql = "EXEC sp_consultar_eventos_categorias NULL, :fechaInicio, :fechaFin";
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
$fechaInicio = isset($_GET['fechaInicioEventosCategorias']) ? $_GET['fechaInicioEventosCategorias'] : null;
$fechaFin = isset($_GET['fechaFinEventosCategorias']) ? $_GET['fechaFinEventosCategorias'] : null;

$reporteAuditoriaCategoriasFecha = new ReporteAuditoriaCategoriasFecha();
$reporte = $reporteAuditoriaCategoriasFecha->getReporteAuditoriaCategoriasFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
