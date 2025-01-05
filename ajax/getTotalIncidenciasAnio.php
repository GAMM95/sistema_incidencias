<?php
require_once '../config/conexion.php';

// class IncidenciasPorAnio extends Conexion
// {
//   public function __construct()
//   {
//     parent::__construct();
//   }

//   public function contarIncidenciasPorAnio($anioConsulta)
//   {
//     $conector = parent::getConexion();
//     try {
//       if ($conector != null) {
//         $sql = "SELECT COUNT(*) AS total_incidencias_anio
//         FROM INCIDENCIA
//         WHERE YEAR(INC_fecha) = :anio";
//         $stmt = $conector->prepare($sql);
//         $stmt->bindParam(':anio', $anioConsulta, PDO::PARAM_INT);
//         $stmt->execute();
//         $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
//         return $resultado;
//       } else {
//         throw new Exception("Error de conexión a la base de datos.");
//       }
//     } catch (PDOException $e) {
//       throw new Exception("Error al contar el total de incidencias por año: " . $e->getMessage());
//     }
//   }
// }

// // $anioConsulta = isset($_GET['anioSeleccionado']) ? $_GET['anioSeleccionado'] : '';
// $anioConsulta = isset($_GET['anioSeleccionado']) && is_numeric($_GET['anioSeleccionado'])
//   ? intval($_GET['anioSeleccionado'])
//   : '';

// if ($anioConsulta === null) {
//   echo json_encode(['error' => 'Año no válido.']);
//   exit;
// }

// $contarIncidenciasAnio = new IncidenciasPorAnio();
// $resultado = $contarIncidenciasAnio->contarIncidenciasPorAnio($anioConsulta);

// header('Content-Type: application/json');
// echo json_encode($resultado);











require_once '../config/conexion.php';

class IncidenciasPorAnio extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function contarIncidenciasPorAnio($anioConsulta)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS total_incidencias_anio
                FROM INCIDENCIA
                WHERE YEAR(INC_fecha) = :anio";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anioConsulta);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado) {
          return [
            'success' => true, 
            'total_incidencias_anio' => $resultado['total_incidencias_anio']];
        } else {
          return ['success' => false];
        }
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      return ['success' => false, 'message' => "Error: " . $e->getMessage()];
    }
  }
}

$anioConsulta = isset($_GET['anioSeleccionado']) && is_numeric($_GET['anioSeleccionado']) 
                ? intval($_GET['anioSeleccionado']) 
                : '';

if ($anioConsulta === null) {
  echo json_encode([
    'success' => false, 
    'message' => 'Año no válido.']);
  exit;
}

$contarIncidenciasAnio = new IncidenciasPorAnio();
$resultado = $contarIncidenciasAnio->contarIncidenciasPorAnio($anioConsulta);

header('Content-Type: application/json');
echo json_encode($resultado);

