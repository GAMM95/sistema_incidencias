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
            codigoPatrimonial,
            nombreBien,
            nombreArea,
            cantidadIncidencias
        FROM vw_equipos_mas_afectados
        WHERE codigoPatrimonial = :codigoPatrimonial
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
