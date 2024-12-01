<?php
require_once '../../../../config/conexion.php';

class ReporteAreaMasIncidenciaCategoriaFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteAreaMasIncidenciaCategoriaFecha($categoria, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "SELECT TOP 10 A.ARE_nombre AS areaMasIncidencia, COUNT(*) AS cantidadIncidencias
      FROM INCIDENCIA I
      INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
      INNER JOIN CATEGORIA C ON I.CAT_codigo = C.CAT_codigo
      WHERE I.INC_fecha BETWEEN :fechaInicio AND :fechaFin
      AND C.CAT_codigo = :categoria
      GROUP BY A.ARE_nombre
      ORDER BY cantidadIncidencias DESC";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
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
$categoria = isset($_GET['categoriaSeleccionada']) ? $_GET['categoriaSeleccionada'] : '';
$fechaInicio = isset($_GET['fechaInicioAreaMasAfectada']) ? $_GET['fechaInicioAreaMasAfectada'] : '';
$fechaFin = isset($_GET['fechaFinAreaMasAfectada']) ? $_GET['fechaFinAreaMasAfectada'] : '';

$reporteAreaMasIncidenciaCategoriaFecha = new ReporteAreaMasIncidenciaCategoriaFecha();
$reporte = $reporteAreaMasIncidenciaCategoriaFecha->getReporteAreaMasIncidenciaCategoriaFecha($categoria, $fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
