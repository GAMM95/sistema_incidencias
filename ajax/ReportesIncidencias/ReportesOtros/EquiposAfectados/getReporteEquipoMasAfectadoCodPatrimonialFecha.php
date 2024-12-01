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
    i.INC_codigoPatrimonial AS codigoPatrimonial,
    -- Subconsulta para obtener el nombre del bien utilizando los primeros 8 dígitos
      (SELECT BIE_nombre 
      FROM BIEN 
      WHERE LEFT(i.INC_codigoPatrimonial, 8) = LEFT(BIE_codigoIdentificador, 8)) AS nombreBien,
	  a.ARE_nombre AS nombreArea, 
	  COUNT(*) AS cantidadIncidencias
    FROM INCIDENCIA i
    INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo 
    WHERE i.INC_codigoPatrimonial IS NOT NULL 
	  AND i.INC_codigoPatrimonial <> ''
	  AND i.INC_codigoPatrimonial = :codigoPatrimonial
    AND i.INC_fecha BETWEEN :fechaInicio AND :fechaFin
    GROUP BY i.INC_codigoPatrimonial, a.ARE_nombre
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
