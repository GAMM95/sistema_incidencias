<?php
require_once '../config/conexion.php';

class IncidenciasFechasUsuario extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function listarIncidenciasFechaUser($fechaConsulta, $area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_incidencias_fecha_user
                WHERE INC_fecha = :fechaConsulta
                AND ARE_codigo = :area
                ORDER BY INC_numero DESC";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fechaConsulta', $fechaConsulta, PDO::PARAM_STR);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar las incidencias por fecha del usuario: " . $e->getMessage());
    }
  }
}

$fechaConsulta = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
$area = isset($_GET['codigoArea']) ?  $_GET['codigoArea'] : null;


$listarIncidenciasFechaUsuario = new IncidenciasFechasUsuario();
$lista = $listarIncidenciasFechaUsuario->listarIncidenciasFechaUser($fechaConsulta, $_SESSION['codigoArea']);

header('Content-Type: application/json');
echo json_encode($lista);
exit();
