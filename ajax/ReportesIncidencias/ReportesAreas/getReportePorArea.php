<?php
require_once '../../../config/conexion.php';

class ReportePorArea extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReportePorArea($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_filtrar_incidencias_area :area, NULL, NULL";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      echo json_encode(['error' => $e->getMessage()]);
      return null;
    }
  }
}

// Obtención del parámetro 'area' desde la solicitud
$area = isset($_GET['areaIncidencia']) ?  $_GET['areaIncidencia'] : null;

$reporteAreas = new ReportePorArea();
$reporte = $reporteAreas->getReportePorArea($area);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
