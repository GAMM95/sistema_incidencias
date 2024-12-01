<?php
require_once '../../../../config/conexion.php';

class ReporteTotalAreaMasAfectada extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteTotalAreaMasAfectada()
  {
    $conector = parent::getConexion();
    $sql = "SELECT A.ARE_nombre AS areaMasIncidencia, COUNT(*) AS cantidadIncidencias
      FROM INCIDENCIA I
      INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
      GROUP BY A.ARE_nombre
      ORDER BY cantidadIncidencias DESC";
    $stmt = $conector->prepare($sql);

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

$reporteTotalAreaMasAfectada = new ReporteTotalAreaMasAfectada();
$reporte = $reporteTotalAreaMasAfectada->getReporteTotalAreaMasAfectada();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
