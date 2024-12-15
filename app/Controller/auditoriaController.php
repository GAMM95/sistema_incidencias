<?php
require_once 'app/Model/AuditoriaModel.php';

class AuditoriaController
{
  private $auditoriaModel;

  public function __construct()
  {
    $this->auditoriaModel = new AuditoriaModel();
  }

  // Metodo para consultar todos los eventos para la tabla auditoria
  public function consultarEventosTotales($usuario = NULL, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $usuario = isset($_GET['personaEventosTotales']) ? (int) $_GET['personaEventosTotales'] : null;
      $fechaInicio = isset($_GET['fechaInicioEventosTotales']) ? $_GET['fechaInicioEventosTotales'] : null;
      $fechaFin = isset($_GET['fechaFinEventosTotales']) ? $_GET['fechaFinEventosTotales'] : null;
      // Llamar al método para consultar incidencias por área, código patrimonial y fecha
      $consultaEventosTotales = $this->auditoriaModel->buscarEventosTotales($usuario, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $consultaEventosTotales;
    }
  }

  // Metodo para listar los eventos totales en la tabla de auditoria
  public function listarEventosTotales()
  {
    $resultadoEventosTotales = $this->auditoriaModel->listarEventosTotales();
    return $resultadoEventosTotales;
  }



  // Metodo para consultar registros de recepciones en la tabla de auditoria
  public function consultarRegistrosRecepciones($fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $fechaInicio = isset($_GET['fechaInicio_auditoria_recepciones']) ? $_GET['fechaInicio_auditoria_recepciones'] : null;
      $fechaFin = isset($_GET['fechaFin_auditoria_recepciones']) ? $_GET['fechaFin_auditoria_recepciones'] : null;

      $resultadoAuditoriaRegistroRecepciones = $this->auditoriaModel->consultarRegistrosRecepciones($fechaInicio, $fechaFin);
      return $resultadoAuditoriaRegistroRecepciones;
    }
  }

  // Metodo para listar los registros de asignaciones en la tabla auditoria
  public function listarRegistrosAsignaciones()
  {
    $resultadoAuditoriaRegistroAsignaciones = $this->auditoriaModel->listarRegistrosAsignaciones();
    return $resultadoAuditoriaRegistroAsignaciones;
  }

  // Metodo para consultar registros de asignaciones en la tabla de auditoria
  public function consultarRegistrosAsignaciones($fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $fechaInicio = isset($_GET['fechaInicio_auditoria_asignaciones']) ? $_GET['fechaInicio_auditoria_asignaciones'] : null;
      $fechaFin = isset($_GET['fechaFin_auditoria_asignaciones']) ? $_GET['fechaFin_auditoria_asignaciones'] : null;

      $resultadoAuditoriaRegistroAsignaciones = $this->auditoriaModel->consultarRegistrosAsignaciones($fechaInicio, $fechaFin);
      return $resultadoAuditoriaRegistroAsignaciones;
    }
  }
}
