<?php
require_once '../../../config/conexion.php';

class getReporteEventosTotalesUsuarioFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteEventosTotalesUsuarioFecha($usuario, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "EXEC sp_consultar_eventos_totales :usuario, :fechaInicio, :fechaFin";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_INT);
    $stmt->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
    $stmt->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);

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
$usuario = isset($_GET['personaEventosTotales']) ?  $_GET['personaEventosTotales'] : null;
$fechaInicio = isset($_GET['fechaInicioEventosTotales']) ? $_GET['fechaInicioEventosTotales'] : null;
$fechaFin = isset($_GET['fechaFinEventosTotales']) ? $_GET['fechaFinEventosTotales'] : null;

$reporteEventosTotalesUsuarioFecha = new getReporteEventosTotalesUsuarioFecha();
$reporte = $reporteEventosTotalesUsuarioFecha->getReporteEventosTotalesUsuarioFecha($usuario, $fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
