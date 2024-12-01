<?php
require_once '../../../../config/conexion.php';

class ReporteAreaMasIncidenciaCategoria extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteAreaMasIncidenciaCategoria($categoria)
  {
    $conector = parent::getConexion();
    $sql = "SELECT TOP 10 C.CAT_codigo, a.ARE_nombre AS areaMasIncidencia, COUNT(*) AS cantidadIncidencias 
      FROM INCIDENCIA i
      INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
      INNER JOIN CATEGORIA C ON C.CAT_codigo = I.CAT_codigo
      WHERE C.CAT_codigo = :categoria
      GROUP BY a.ARE_nombre, C.CAT_codigo
      ORDER BY cantidadIncidencias DESC";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);

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

// Obtención del parámetro 'categoria'
$categoria = isset($_GET['categoriaSeleccionada']) ? $_GET['categoriaSeleccionada'] : '';

$reporteAreaMasIncidenciaCategoria = new ReporteAreaMasIncidenciaCategoria();
$reporte = $reporteAreaMasIncidenciaCategoria->getReporteAreaMasIncidenciaCategoria($categoria);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
