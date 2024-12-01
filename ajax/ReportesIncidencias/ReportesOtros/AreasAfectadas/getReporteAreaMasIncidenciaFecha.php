<?php
require_once '../../../../config/conexion.php';

class ReporteAreaMasIncidenciaFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteAreaMasIncidenciaFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "SELECT TOP 10 A.ARE_nombre AS areaMasIncidencia, COUNT(*) AS cantidadIncidencias
      FROM INCIDENCIA I
      INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
      WHERE I.INC_fecha BETWEEN :fechaInicio AND :fechaFin
      GROUP BY A.ARE_nombre
      ORDER BY cantidadIncidencias DESC";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':fechaFin', $fechaFin);

    try {
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo json_encode(['Error de query' => $e->getMessage()]);
      exit;
    }
    return $resultado;
  }
}

// Obtención del parámetro 'fechaInicio' y 'fechaFin' desde la solicitud
$fechaInicio = isset($_GET['fechaInicioAreaMasAfectada']) ? $_GET['fechaInicioAreaMasAfectada'] : '';
$fechaFin = isset($_GET['fechaFinAreaMasAfectada']) ? $_GET['fechaFinAreaMasAfectada'] : '';

$reporteAreaMasIncidenciaFecha = new ReporteAreaMasIncidenciaFecha();
$reporte = $reporteAreaMasIncidenciaFecha->getReporteAreaMasIncidenciaFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
