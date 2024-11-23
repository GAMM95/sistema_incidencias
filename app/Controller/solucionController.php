<?php

require_once 'app/Model/SolucionModel.php';

class SolucionController
{
  private $solucionModel;

  public function __construct()
  {
    $this->solucionModel = new solucionModel();
  }

  // Metodo para registrar la categoria
  public function registrarSolucion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $descripcionSolucion = $_POST['descripcionSolucion'] ?? null;

      if ($descripcionSolucion === null || trim($descripcionSolucion) === '') {
        echo json_encode([
          'success' => false,
          'message' => 'Ingrese nueva soluci&oacute;n.'
        ]);
        exit();
      }

      try {
        $insertSuccessId = $this->solucionModel->insertarSolucion($descripcionSolucion);
        if ($insertSuccessId) {
          echo json_encode([
            'success' => true,
            'message' => 'Soluci&oacute;n registrada.',
            'CAT_codigo' => $insertSuccessId
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar la soluci&oacute;n.',
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

  // MEtodo para editar categoria
  public function actualizarSolucion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigoSolucion = $_POST['codigoSolucion'] ?? null;
      $descripcionSolucion = $_POST['descripcionSolucion'] ?? null;

      if (empty($codigoSolucion) || empty($descripcionSolucion)) {
        echo json_encode([
          'success' => false,
          'message' => 'Ingrese campos requeridos (*).'
        ]);
        exit();
      }

      try {
        // Llamar al metodo para editar la categoria
        $updateSuccess = $this->solucionModel->editarSolucion($descripcionSolucion, $codigoSolucion);
        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Soluci&oacute;n actualizada.'
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

  // Metodo para listar las soluciones
  public function listarSoluciones()
  {
    try {
      $soluciones = $this->solucionModel->listarSoluciones();
      return $soluciones;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }

  // Controlador para habilitar una solucion
  public function habilitarSolucion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigoSolucion = isset($_POST['codigoSolucion']) ? $_POST['codigoSolucion'] : '';

      try {
        $resultados = $this->solucionModel->habilitarSolucion($codigoSolucion);
        if ($resultados) {
          echo json_encode([
            'success' => true,
            'message' => 'Soluci&oacute;n habilitada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se pudo habilitar la soluci&oacute;n.'
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
        'message' => 'M&eacute;todo no permitido.'
      ]);
    }
  }

  // Controlador para deshabilitar una categoria
  public function deshabilitarSolucion()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigoSolucion = isset($_POST['codigoSolucion']) ? $_POST['codigoSolucion'] : '';

      try {
        $resultados = $this->solucionModel->deshabilitarSolucion($codigoSolucion);
        if ($resultados) {
          echo json_encode([
            'success' => true,
            'message' => 'Soluci&oacute;n deshabilitada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se pudo deshabilitar la soluci&oacute;n.'
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
        'message' => 'M&eacute;todo no permitido.'
      ]);
    }
  }
}
