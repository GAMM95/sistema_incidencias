<?php

require_once 'app/Model/AreaModel.php';

class AreaController
{
  private $areaModel;

  public function __construct()
  {
    $this->areaModel = new AreaModel();
  }

  // Metodo para registrar Areas
  public function registrarArea()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $nombreArea = $_POST['nombreArea'] ?? null;

      if (empty($nombreArea)) {
        echo json_encode([
          'success' => false,
          'message' => 'Ingrese nombre de la nueva &aacute;rea.'
        ]);
        exit();
      }

      try {
        // Validar si eciste una area que ya esta registrada
        if ($this->areaModel->validarAreaExistente($nombreArea)) {
          echo json_encode([
            'success' => false,
            'message' => 'El &aacute;rea ingresada ya esta registrada.'
          ]);
          exit();
        }

        // Registrar a la area
        $insertSuccessId = $this->areaModel->insertarArea($nombreArea);

        if ($insertSuccessId) {
          echo json_encode([
            'success' => true,
            'message' => '&Aacute;rea registrada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar &aacute;rea.'
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

  // Metodo para editar Areas
  public function actualizarArea()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigoArea = $_POST['codArea'] ?? null;
      $nombreArea = $_POST['nombreArea'] ?? null;

      try {
        $updateSuccess = $this->areaModel->editarArea($nombreArea, $codigoArea);
        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Datos actualizados.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se realizaron cambios.'
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

  // Metodo para filtrar areas por un termino
  public function filtrarAreas()
  {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      $terminoBusqueda = $_GET['termino'] ?? '';

      try {
        $resultados = $this->areaModel->filtrarAreas($terminoBusqueda);
        if ($resultados) {
          echo json_encode([
            'success' =>  true,
            'message' => 'B&uacute;squeda exitosa.'
          ]);
        } else {
          echo json_encode([
            'success' =>  false,
            'message' => 'No se realiz&oacute; b&uacute;squeda.'
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

  // Controlador para habilitar un área
  public function habilitarArea()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigoArea = isset($_POST['codArea']) ? $_POST['codArea'] : '';

      try {
        $resultados = $this->areaModel->habilitarArea($codigoArea);
        if ($resultados) {
          echo json_encode([
            'success' => true,
            'message' => '&Aacute;rea habilitada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se pudo habilitar el &aacute;rea.'
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
  public function deshabilitarArea()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigoArea = isset($_POST['codArea']) ? $_POST['codArea'] : '';

      try {
        $resultados = $this->areaModel->deshabilitarArea($codigoArea);
        if ($resultados) {
          echo json_encode([
            'success' => true,
            'message' => '&Aacute;rea deshabilitada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se pudo deshabilitar el &aacute;rea.'
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

  // Metodo para listar areas
  public function listarAreas()
  {
    try {
      $resultado = $this->areaModel->listarArea();

      return $resultado;
    } catch (Exception $e) {
      echo  json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
      ]);
    }
  }
}
