<?php
require 'app/Model/RecepcionModel.php';

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
  // public function actualizarRecepcion()
  // {
  //   if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //     // Obtener y validar los parámetros
  //     $numeroRecepcion = $_POST['num_recepcion'] ?? null;
  //     $prioridad = $_POST['prioridad'] ?? null;
  //     $impacto = $_POST['impacto'] ?? null;

  //     if (empty($numeroRecepcion) || empty($prioridad) || empty($impacto)) {
  //       echo json_encode([
  //         'success' => false,
  //         'message' => 'Ingrese campos requeridos (*).'
  //       ]);
  //       exit();
  //     }

  //     try {
  //       // Verificar el estado de la incidencia
  //       $estado = $this->recepcionModel->obtenerEstadoRecepcion($numeroRecepcion);

  //       // Suponiendo que el estado "4" permite la actualización
  //       if ($estado === 4) {
  //         // Estado no permitido para actualización
  //         echo json_encode([
  //           'success' => false,
  //           'message' => 'La recepci&oacute;n no est&aacute; en un estado que permita actualizaci&oacute;n.'
  //         ]);
  //         exit();
  //       }

  //       // Llamar al modelo para actualizar la incidencia
  //       $updateSuccess = $this->recepcionModel->editarRecepcion($prioridad, $impacto, $numeroRecepcion);

  //       if ($updateSuccess) {
  //         echo json_encode([
  //           'success' => true,
  //           'message' => 'Recepci&oacute;n actualizada.'
  //         ]);
  //       } else {
  //         echo json_encode([
  //           'success' => false,
  //           'message' => 'No se realiz&oacute; ninguna actualizaci&oacute;n.'
  //         ]);
  //       }
  //     } catch (Exception $e) {
  //       echo json_encode([
  //         'success' => false,
  //         'message' => 'Error: ' . $e->getMessage()
  //       ]);
  //     }
  //     exit();
  //   } else {
  //     echo json_encode([
  //       'success' => false,
  //       'message' => 'M&eacute;todo no permitido.'
  //     ]);
  //   }
  // }

  // Metodo para eliinar la recepcion 
  // public function eliminarRecepcion()
  // {
  //   if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //     // Obtener y validar los parámetros
  //     $numeroRecepcion = $_POST['num_recepcion'] ?? null;

  //     if (empty($numeroRecepcion)) {
  //       echo json_encode([
  //         'success' => false,
  //         'message' => 'Debe seleccionar una incidencia recepcionada'
  //       ]);
  //       exit();
  //     }

  //     try {
  //       // Llamar al modelo para actualizar la incidencia
  //       $deleteSuccess = $this->recepcionModel->eliminarRecepcion($numeroRecepcion);

  //       if ($deleteSuccess) {
  //         echo json_encode([
  //           'success' => true,
  //           'message' => 'Recepci&oacute;n eliminada.'
  //         ]);
  //       } else {
  //         echo json_encode([
  //           'success' => false,
  //           'message' => 'No se realiz&oacute; ninguna eliminaci&oacute;n.'
  //         ]);
  //       }
  //     } catch (Exception $e) {
  //       echo json_encode([
  //         'success' => false,
  //         'message' => 'Error: ' . $e->getMessage()
  //       ]);
  //     }
  //     exit();
  //   } else {
  //     echo json_encode([
  //       'success' => false,
  //       'message' => 'M&eacute;todo no permitido.'
  //     ]);
  //   }
  // }
}
