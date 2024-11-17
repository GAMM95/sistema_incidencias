<?php

require_once 'app/Model/MantenimientoModel.php';

class MantenimientoController
{
  private $mantenimientoModel;

  public function __construct()
  {
    $this->mantenimientoModel = new MantenimientoModel();
  }

  // Controlador para habilitar un área
  public function resolverIncidencia()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $asignacion = isset($_POST['numeroAsignacion']) ? $_POST['numeroAsignacion'] : '';

      try {
        $resultados = $this->mantenimientoModel->resolverIncidencia($asignacion);
        if ($resultados) {
          echo json_encode([
            'success' => true,
            'message' => 'Mantenimiento finalizado.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se pudo cambiar estado del mantenimiento.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }

  // Controlador para deshabilitar un área
  public function encolarIncidencia()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $asignacion = isset($_POST['numeroAsignacion']) ? $_POST['numeroAsignacion'] : '';

      try {
        $resultados = $this->mantenimientoModel->encolarIncidencia($asignacion);
        if ($resultados) {
          echo json_encode([
            'success' => true,
            'message' => 'Mantenimiento en espera.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se pudo cambiar el estado del mantenimiento.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }


  // Metodo para listar asignaciones para el administrador
  public function listarAsignacionesAdministrador()
  {
    try {
      $resultado = $this->mantenimientoModel->listarAsignacionesAdministrador();
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al listar incidencias asignadas al administrador: " . $e->getMessage();
    }
  }

  // Metodo para listar incidencias con tiempo de mantenimiento
  public function listarIncidenciasMantenimiento()
  {
    try {
      $resultado = $this->mantenimientoModel->listarIncidenciasMantenimiento();
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al listar incidencias con el tiempo de mantenimiento: " . $e->getMessage();
    }
  }

  // Metodo para listar incidencias con tiempo de mantenimiento para el usuario de soporte
  public function listarIncidenciasMantenimientoSoporte($usuario = null)
  {
    try {
      $resultado = $this->mantenimientoModel->listarAsignacionesSoporte($usuario);
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al listar incidencias con el tiempo de mantenimiento: " . $e->getMessage();
    }
  }

  // Metodo para consultar incidencias asignadas en mantenimiento para el usuario de soporte
  public function consultarIncidenciasMantenimientoSoporte($usuario = NULL, $codigoPatrimonial = null, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $usuario = isset($_GET['codigoUsuario']) ? (int) $_GET['codigoUsuario'] : null;
      $codigoPatrimonial = isset($_GET['codigoPatrimonial']) ? $_GET['codigoPatrimonial'] : null;
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
      error_log("Usuario asignado: $usuario, CodigoPatrimonial: $codigoPatrimonial, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");

      // Llamar al método para consultar cierres 
      $resultado = $this->mantenimientoModel->buscarAsignacionesSoporte($usuario, $codigoPatrimonial, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $resultado;
    }
  }
}
