<?php
require 'app/Model/CierreModel.php';
class cierreController
{
  private $cierreModel;

  public function __construct()
  {
    $this->cierreModel = new CierreModel();
  }

  // Metodo para registrar cierre
  public function registrarCierre()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $fecha = $_POST['fecha_cierre'] ?? null;
      $hora = $_POST['hora'] ?? null;
      $diagnostico = $_POST['diagnostico'] ?? null;
      $documento = $_POST['documento'] ?? null;
      $recomendaciones = $_POST['recomendaciones'] ?? null;
      $operatividad = $_POST['operatividad'] ?? null;
      $mantenimiento = $_POST['mantenimiento'] ?? null;
      $usuario = $_POST['usuario'] ?? null;
      $solucion = $_POST['solucion'] ?? null;

      // Validar que todos los campos requeridos estén completos
      if (empty($documento) || empty($operatividad)) {
        echo json_encode([
          'success' => false,
          'message' => 'Complete los campos requeridos (*).'
        ]);
        exit();
      }

      try {
        // Llamar al método del modelo para insertar el cierre en la base de datos
        $insertSuccess = $this->cierreModel->insertarCierre($fecha, $hora, $diagnostico, $documento, $recomendaciones, $operatividad, $mantenimiento, $usuario, $solucion);

        if ($insertSuccess) {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar el cierre.',
            'CIE_numero' => $insertSuccess
          ]);
        } else {
          echo json_encode([
            'success' => true,
            'message' => 'Incidencia cerrada.',
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

  //  Metodo para editar el cierre
  public function actualizarCierre()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener y validar los parámetros
      $numeroCierre = $_POST['num_cierre'] ?? null;
      $documento = $_POST['documento'] ?? null;
      $condicion = $_POST['operatividad'] ?? null;
      $solucion = $_POST['solucion'] ?? null;
      $diagnostico = $_POST['diagnostico'] ?? null;
      $recomendaciones = $_POST['recomendaciones'] ?? null;

      if (empty($documento) || empty($condicion)) {
        echo json_encode([
          'success' => false,
          'message' => 'Campo obligatorio.'
        ]);
        exit();
      }

      try {

        // Llamar al modelo para editar el cierre de la incidencia
        $updateSuccess = $this->cierreModel->editarCierre($numeroCierre, $documento, $condicion, $solucion, $diagnostico, $recomendaciones);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Cierre de incidencia actualizado.'
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
    }
  }

  // Metodo para eliinar la recepcion 
  public function eliminarCierre()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener y validar los parámetros
      $numeroCierre = $_POST['num_cierre'] ?? null;

      if (empty($numeroCierre)) {
        echo json_encode([
          'success' => false,
          'message' => 'Debe seleccionar una incidencia cerrada.'
        ]);
        exit();
      }

      try {
        // Llamar al modelo para actualizar la incidencia
        $updateSuccess = $this->cierreModel->eliminarCierre($numeroCierre);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Cierre de incidencia eliminado.'
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
        'message' => '&eacute;todo no permitido.'
      ]);
    }
  }

  // Metodo para consultar cierres - Administrador
  public function consultarCierres($area = NULL, $codigoPatrimonial = null, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $area = isset($_GET['area']) ? (int) $_GET['area'] : null;
      $codigoPatrimonial = isset($_GET['codigoPatrimonial']) ? $_GET['codigoPatrimonial'] : null;
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
      error_log("Área: $area, CodigoPatrimonial: $codigoPatrimonial, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");

      // Llamar al método para consultar cierres 
      $resultado = $this->cierreModel->buscarCierres($area, $codigoPatrimonial, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $resultado;
    }
  }

  // Metodo para filtrar incidencias cerradas por fecha ingresada
  public function filtrarIncidenciasCerradasFecha($usuario = null, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $usuario = isset($_GET['usuarioIncidenciasCerradas']) ? (int) $_GET['usuarioIncidenciasCerradas'] : null;
      $fechaInicio = isset($_GET['fechaInicioIncidenciasCerradas']) ? $_GET['fechaInicioIncidenciasCerradas'] : null;
      $fechaFin = isset($_GET['fechaFinIncidenciasCerradas']) ? $_GET['fechaFinIncidenciasCerradas'] : null;
      error_log("Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");
      // Llamar al método para consultar incidencias por fecha
      $consultaIncidencia = $this->cierreModel->buscarIncidenciaCerradas($usuario, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $consultaIncidencia;
    }
  }

  // Metodo para listar cierres para consulta de administrador y soporte
  public function listarIncidenciasCerradas()
  {
    $resultado = $this->cierreModel->listarCierresConsulta();
    return $resultado;
  }
}
