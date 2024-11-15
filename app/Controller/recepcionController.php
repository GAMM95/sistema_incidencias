<?php
require 'app/Model/RecepcionModel.php';

class RecepcionController
{
  private $recepcionModel;

  public function __construct()
  {
    $this->recepcionModel = new RecepcionModel();
  }

  // Metodo para registrar recepcion
  public function registrarRecepcion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $fecha = $_POST['fecha_recepcion'] ?? null;
      $hora = $_POST['hora'] ?? null;
      $incidencia = $_POST['incidencia'] ?? null;
      $prioridad = $_POST['prioridad'] ?? null;
      $impacto = $_POST['impacto'] ?? null;
      $usuario = $_POST['usuario'] ?? null;

      // Validar que todos los campos requeridos estén completos
      if (empty($fecha) || empty($hora) || empty($incidencia) || empty($prioridad) || empty($impacto) || empty($usuario)) {
        echo json_encode([
          'success' => false,
          'message' => 'Todos los campos son obligatorios.'
        ]);
        exit();
      }

      try {
        // Llamar al método del modelo para insertar la recepción en la base de datos
        $insertSuccessId = $this->recepcionModel->insertarRecepcion($fecha, $hora, $incidencia, $prioridad, $impacto, $usuario);

        if ($insertSuccessId) {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar la recepci&oacute;n.',
            'REC_numero' => $insertSuccessId
          ]);
        } else {
          echo json_encode([
            'success' => true,
            'message' => 'Recepci&oacute;n registrada.',
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
  public function actualizarRecepcion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener y validar los parámetros
      $numeroRecepcion = $_POST['num_recepcion'] ?? null;
      $prioridad = $_POST['prioridad'] ?? null;
      $impacto = $_POST['impacto'] ?? null;

      if (empty($numeroRecepcion) || empty($prioridad) || empty($impacto)) {
        echo json_encode([
          'success' => false,
          'message' => 'Ingrese campos requeridos (*).'
        ]);
        exit();
      }

      try {
        // Verificar el estado de la incidencia
        $estado = $this->recepcionModel->obtenerEstadoRecepcion($numeroRecepcion);

        // Suponiendo que el estado "4" permite la actualización
        if ($estado === 4) {
          // Estado no permitido para actualización
          echo json_encode([
            'success' => false,
            'message' => 'La recepci&oacute;n no est&aacute; en un estado que permita actualizaci&oacute;n.'
          ]);
          exit();
        }

        // Llamar al modelo para actualizar la incidencia
        $updateSuccess = $this->recepcionModel->editarRecepcion($prioridad, $impacto, $numeroRecepcion);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Recepci&oacute;n actualizada.'
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

  // Metodo para eliinar la recepcion 
  public function eliminarRecepcion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener y validar los parámetros
      $numeroRecepcion = $_POST['num_recepcion'] ?? null;

      if (empty($numeroRecepcion)) {
        echo json_encode([
          'success' => false,
          'message' => 'Debe seleccionar una incidencia recepcionada'
        ]);
        exit();
      }

      try {
        // Llamar al modelo para actualizar la incidencia
        $deleteSuccess = $this->recepcionModel->eliminarRecepcion($numeroRecepcion);

        if ($deleteSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Recepci&oacute;n eliminada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se realiz&oacute; ninguna eliminaci&oacute;n.'
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

  // Metodo para listar incidencias pendientes de cierre para reporte
  public function listarIncidenciasPendientesCierre()
  {
    $resultado = $this->recepcionModel->listarIncidenciasPendientesCierre();
    return $resultado;
  }

  // Metodo para contar incidencias recepcionadas para paginacion 
  public function contarRecepcionesRegistradas()
  {
    try {
      $resultado = $this->recepcionModel->contarRecepciones();
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al contar incidencias registradas: " . $e->getMessage();
    }
  }

  // Metodo para listar incidencias recepcionadas para paginacion
  public function listarRecepcionesPaginacion($start = null, $limit = null)
  {
    try {
      $resultado = $this->recepcionModel->listarRecepciones($start, $limit);
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al listar incidencias registradas para paginacion: " . $e->getMessage();
    }
  }
}
