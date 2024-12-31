<?php
require_once '../../../../config/conexion.php';

class ReporteIncidenciasCerradasUsuario extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteIncidenciasCerradasUsuario($usuario)
  {
    $conector = parent::getConexion();
    $sql = "EXEC sp_filtrar_incidencias_cerradas :usuario, NULL, NULL";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);

    try {
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo json_encode(['error' => 'Error al obtener los datos: ' . $e->getMessage()]);
      exit;
    }
    return $resultado;
  }
}

// Validación de las fechas
$usuario = isset($_GET['usuarioIncidenciasCerradas']) ?  $_GET['usuarioIncidenciasCerradas'] : null;

$reporteIncidenciasCerradasUsuario = new ReporteIncidenciasCerradasUsuario();
$reporte = $reporteIncidenciasCerradasUsuario->getReporteIncidenciasCerradasUsuario($usuario);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
