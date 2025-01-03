<?php
require_once '../config/conexion.php';

class IncidenciasFechasAdmin extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function listarIncidenciasFechaAdmin($fechaConsulta)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_incidencias_totales
                WHERE CONVERT(DATE, ultimaFecha) = :fechaConsulta
                ORDER BY INC_numero DESC";
        $stmt = $conector->prepare($sql); 
        $stmt->bindParam(':fechaConsulta', $fechaConsulta, PDO::PARAM_STR); 
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar las incidencias por fecha: " . $e->getMessage());
    }
  }
}

$fechaConsulta = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

$listarIncidenciasFechaAdmin = new IncidenciasFechasAdmin();
$lista = $listarIncidenciasFechaAdmin->listarIncidenciasFechaAdmin($fechaConsulta);

header('Content-Type: application/json');
echo json_encode($lista);
