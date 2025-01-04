
<?php
require_once '../../../../config/conexion.php';

class ReporteAuditoriaAreasUsuario extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteAuditoriaAreasUsuario($usuario)
  {
    $conector = parent::getConexion();
    $sql = "EXEC sp_consultar_eventos_areas :usuario, NULL, NULL";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_INT);
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
$usuario = isset($_GET['usuarioEventoAreas']) ?  $_GET['usuarioEventoAreas'] : null;

$reporteAuditoriaAreasUsuario = new ReporteAuditoriaAreasUsuario();
$reporte = $reporteAuditoriaAreasUsuario->getReporteAuditoriaAreasUsuario($usuario);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
