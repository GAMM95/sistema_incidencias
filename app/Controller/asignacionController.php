<?php
require 'app/Model/AsignacionModel.php';

class AsignacionController
{
  private $asignacionModel;

  public function __construct()
  {
    $this->asignacionModel = new AsignacionModel();
  }

  // Metodo para registrar recepcion
  public function registrarAsignacion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $fecha = $_POST['fecha'] ?? null;
      $hora = $_POST['hora'] ?? null;
      $usuario = $_POST['codigoUsuario'] ?? null;
      $recepcion = $_POST['num_recepcion'] ?? null;

      // Validar que todos los campos requeridos estén completos
      if (empty($usuario) || empty($recepcion)) {
        echo json_encode([
          'success' => false,
          'message' => 'Todos los campos son obligatorios.'
        ]);
        exit();
      }

      try {
        // Llamar al método del modelo para insertar la recepción en la base de datos
        $insertSuccessId = $this->asignacionModel->insertarAsignacion($fecha, $hora, $usuario, $recepcion);

        if ($insertSuccessId) {
          echo json_encode([
            'success' => false,
            'message' => 'Error al asignar incidencia.',
            'REC_numero' => $insertSuccessId
          ]);
        } else {
          echo json_encode([
            'success' => true,
            'message' => 'Incidencia asignada.',
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'M&eacute;todo no permitido.'
      ]);
    }
  }


  // Metodo para editar la recepcion 
  public function actualizarAsignacion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener y validar los parámetros
      $numeroAsignacion = $_POST['num_asignacion'] ?? null;
      $usuario = $_POST['codigoUsuario'] ?? null;

      if (empty($numeroAsignacion) || empty($usuario)) {
        echo json_encode([
          'success' => false,
          'message' => 'Ingrese campos requeridos (*).'
        ]);
        exit();
      }

      try {
        $estado = $this->asignacionModel->obtenerAsignacionesPorId($numeroAsignacion);

        // Suponiendo que el estado "4" permite la actualización
        if ($estado === 5) {
          // Estado no permitido para actualización
          echo json_encode([
            'success' => false,
            'message' => 'La asignaci&oacute;n no est&aacute; en un estado que permita actualizaci&oacute;n.'
          ]);
          exit();
        }

        // Llamar al modelo para actualizar la incidencia
        $updateSuccess = $this->asignacionModel->editarAsignacion($usuario, $numeroAsignacion);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Asignaci&oacute;n actualizada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se realiz&oacute; ninguna actualizaci&oacute;n.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'M&eacute;todo no permitido.'
      ]);
    }
  }

  // Metodo para contar asignaciones registradas
  public function contarAsignacionesRegistradas()
  {
    try {
      $resultado = $this->asignacionModel->contarAsignaciones();
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al contar incidencias registradas: " . $e->getMessage();
    }
  }

  // Metodo para listar asignaciones registradas
  public function listarAsignaciones($start = null, $limit = null)
  {
    try {
      $resultado = $this->asignacionModel->listarAsignaciones($start, $limit);
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al listar asignaciones registradas para paginacion: " . $e->getMessage();
    }
  }

  // Metodo para listar asignaciones por usuario
  public function listarAsignacionesSoporte($usuario = null)
  {
    try {
      $resultado = $this->asignacionModel->listarAsignacionesSoporte($usuario);
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al listar incidencias asignadas al usuario: " . $e->getMessage();
    }
  }

  // Metodo para consultar cierres - Administrador
  public function consultarAsignaciones($usuario = NULL, $codigoPatrimonial = null, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $usuario = isset($_GET['usuarioAsignado']) ? (int) $_GET['usuarioAsignado'] : null;
      $codigoPatrimonial = isset($_GET['codigoPatrimonial']) ? $_GET['codigoPatrimonial'] : null;
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
      error_log("Usuario asignado: $usuario, CodigoPatrimonial: $codigoPatrimonial, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");

      // Llamar al método para consultar cierres 
      $resultado = $this->asignacionModel->buscarAsignaciones($usuario, $codigoPatrimonial, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $resultado;
    }
  }

  public function filtrarIncidenciasAsignadas($usuario = NULL, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $usuario = isset($_GET['usuarioIncidenciasAsignadas']) ? (int) $_GET['usuarioIncidenciasAsignadas'] : null;
      $fechaInicio = isset($_GET['fechaInicioIncidenciasAsignadas']) ? $_GET['fechaInicioIncidenciasAsignadas'] : null;
      $fechaFin = isset($_GET['fechaFinIncidenciasAsignadas']) ? $_GET['fechaFinIncidenciasAsignadas'] : null;
      error_log("Usuario asignado: $usuario, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");

      // Llamar al método para consultar cierres 
      $resultado = $this->asignacionModel->filtrarAsignaciones($usuario, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $resultado;
    }
  }

  // Metodo para listar los registros de asignaciones en la tabla auditoria
  public function listarEventosAsignaciones()
  {
    $resultadoAuditoriaRegistroAsignaciones = $this->asignacionModel->listarEventosAsignaciones();
    return $resultadoAuditoriaRegistroAsignaciones;
  }
}
