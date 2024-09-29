<?php
require_once '../config/conexion.php';

class ReporteAreaMasIncidencia extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteAreaMasIncidencia($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "SELECT TOP 10 a.ARE_nombre AS areaMasIncidencia, COUNT(*) AS cantidadIncidencias
    FROM INCIDENCIA i
    INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
    WHERE i.INC_fecha BETWEEN :fechaInicio AND :fechaFin
    GROUP BY a.ARE_nombre
    ORDER BY cantidadIncidencias DESC";

    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':fechaFin', $fechaFin);

    try {
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo json_encode(['error' => $e->getMessage()]);
      exit;
    }
    return $resultado;
  }
}

// Obtención del parámetro 'fechaInicio' y 'fechaFin' desde la solicitud
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';

$reporteAreaMasIncidencia = new ReporteAreaMasIncidencia();
$reporte = $reporteAreaMasIncidencia->getReporteAreaMasIncidencia($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
