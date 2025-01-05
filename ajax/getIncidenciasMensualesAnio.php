<?php
require_once '../config/conexion.php';

class IncidenciasPorMes extends Conexion {
  public function __construct() {
    parent::__construct();
  }

  public function obtenerIncidenciasPorMes($anio) {
    $conector = parent::getConexion();
    try {
      $sql = "SELECT 
                SUM(CASE WHEN MONTH(INC_fecha) = 1 THEN 1 ELSE 0 END) AS incidencias_enero,
                SUM(CASE WHEN MONTH(INC_fecha) = 2 THEN 1 ELSE 0 END) AS incidencias_febrero,
                SUM(CASE WHEN MONTH(INC_fecha) = 3 THEN 1 ELSE 0 END) AS incidencias_marzo,
                SUM(CASE WHEN MONTH(INC_fecha) = 4 THEN 1 ELSE 0 END) AS incidencias_abril,
                SUM(CASE WHEN MONTH(INC_fecha) = 5 THEN 1 ELSE 0 END) AS incidencias_mayo,
                SUM(CASE WHEN MONTH(INC_fecha) = 6 THEN 1 ELSE 0 END) AS incidencias_junio,
                SUM(CASE WHEN MONTH(INC_fecha) = 7 THEN 1 ELSE 0 END) AS incidencias_julio,
                SUM(CASE WHEN MONTH(INC_fecha) = 8 THEN 1 ELSE 0 END) AS incidencias_agosto,
                SUM(CASE WHEN MONTH(INC_fecha) = 9 THEN 1 ELSE 0 END) AS incidencias_setiembre,
                SUM(CASE WHEN MONTH(INC_fecha) = 10 THEN 1 ELSE 0 END) AS incidencias_octubre,
                SUM(CASE WHEN MONTH(INC_fecha) = 11 THEN 1 ELSE 0 END) AS incidencias_noviembre,
                SUM(CASE WHEN MONTH(INC_fecha) = 12 THEN 1 ELSE 0 END) AS incidencias_diciembre
              FROM INCIDENCIA
              WHERE YEAR(INC_fecha) = :anio";
      $stmt = $conector->prepare($sql);
      $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return ['success' => false, 'message' => $e->getMessage()];
    }
  }
}

$anioConsulta = isset($_GET['anioSeleccionado']) ? intval($_GET['anioSeleccionado']) : date('Y');
$incidencias = new IncidenciasPorMes();
$resultado = $incidencias->obtenerIncidenciasPorMes($anioConsulta);

header('Content-Type: application/json');
echo json_encode(['success' => true] + $resultado);
