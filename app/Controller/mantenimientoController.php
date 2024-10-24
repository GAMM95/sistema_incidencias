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

  
  // // Metodo para filtrar areas por un termino
  // public function filtrarAreas()
  // {
  //   if ($_SERVER["REQUEST_METHOD"] == "GET") {
  //     $terminoBusqueda = $_GET['termino'] ?? '';

  //     try {
  //       $resultados = $this->areaModel->filtrarAreas($terminoBusqueda);
  //       if ($resultados) {
  //         echo json_encode([
  //           'success' =>  true,
  //           'message' => 'B&uacute;squeda exitosa.'
  //         ]);
  //       } else {
  //         echo json_encode([
  //           'success' =>  false,
  //           'message' => 'No se realiz&oacute; b&uacute;squeda.'
  //         ]);
  //       }
  //     } catch (Exception $e) {
  //       echo json_encode([
  //         'success' => false,
  //         'message' => 'Error: ' . $e->getMessage()
  //       ]);
  //     }
  //   } else {
  //     echo json_encode([
  //       'success' => false,
  //       'message' => 'M&eacute;todo no permitido.'
  //     ]);
  //   }
  // }

}
