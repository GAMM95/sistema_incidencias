<?php
require_once '../../../../config/conexion.php';

class ReporteEquipoMasAfectadoCodPatrimonialFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteEquipoMasAfectadoCodPatrimonialFecha($codigoPatrimonial, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "SELECT TOP 10 
        codigoPatrimonial,
        nombreBien,
        nombreArea,
        cantidadIncidencias
      FROM vw_equipos_mas_afectados
      WHERE codigoPatrimonial = :codigoPatrimonial
      AND EXISTS (
          SELECT 1 
          FROM INCIDENCIA i 
          WHERE i.INC_codigoPatrimonial = vw_equipos_mas_afectados.codigoPatrimonial
          AND i.INC_fecha BETWEEN :fechaInicio AND :fechaFin
      )
      ORDER BY cantidadIncidencias DESC";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
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
$equipo = isset($_GET['codigoEquipo']) ? $_GET['codigoEquipo'] : '';
$fechaInicio = isset($_GET['fechaInicioIncidenciasEquipos']) ? $_GET['fechaInicioIncidenciasEquipos'] : '';
$fechaFin = isset($_GET['fechaFinIncidenciasEquipos']) ? $_GET['fechaFinIncidenciasEquipos'] : '';

$reporteEquipoMasAfectadoCodPatrimonialFecha = new ReporteEquipoMasAfectadoCodPatrimonialFecha();
$reporte = $reporteEquipoMasAfectadoCodPatrimonialFecha->getReporteEquipoMasAfectadoCodPatrimonialFecha($equipo, $fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
