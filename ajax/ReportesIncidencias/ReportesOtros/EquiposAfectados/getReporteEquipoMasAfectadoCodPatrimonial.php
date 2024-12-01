<?php
require_once '../../../../config/conexion.php';

class ReporteEquipoMasAfectadoCodPatrimonial extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteEquipoMasAfectadoCodPatrimonial($codigoPatrimonial)
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
    GROUP BY i.INC_codigoPatrimonial, a.ARE_nombre
    ORDER BY cantidadIncidencias DESC";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
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

// Obtención del parámetro 'equipo'
$equipo = isset($_GET['codigoEquipo']) ? $_GET['codigoEquipo'] : '';

$reporteEquipoMasAfectadoCodPatrimonial= new ReporteEquipoMasAfectadoCodPatrimonial();
$reporte = $reporteEquipoMasAfectadoCodPatrimonial->getReporteEquipoMasAfectadoCodPatrimonial($equipo);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
