<?php
require_once 'app/Model/AuditoriaModel.php';

class AuditoriaController
{
  private $auditoriaModel;

  public function __construct()
  {
    $this->auditoriaModel = new AuditoriaModel();
  }

  // Metodo para listar los registros de inicio de sesion en la tabla auditoria
  public function listarRegistrosInicioSesion()
  {
    $resultadoAuditoriaLogin = $this->auditoriaModel->listarRegistrosInicioSesion();
    return $resultadoAuditoriaLogin;
  }

  // Metodo para consultar inicios de sesion en la tabla de auditoria
  public function consultarRegistrosInicioSesion($fechaInicio = null, $fechaFin = null)
  {

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;

      $resultadoAuditoriaLogin = $this->auditoriaModel->consultarRegistrosInicioSesion($fechaInicio, $fechaFin);
      return $resultadoAuditoriaLogin;
    }
  }

  // Metodo para listar los registros de incidencias en la tabla auditoria
  public function listarRegistrosIncidencias()
  {
    $resultadoAuditoriaIncidencias = $this->auditoriaModel->listarRegistrosIncidencias();
    return $resultadoAuditoriaIncidencias;
  }

  // Metodo para consultar registros de incidencias en la tabla de auditoria
  public function consultarRegistrosIncidencias($fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;

      $resultadoAuditoriaIncidencias = $this->auditoriaModel->consultarRegistrosIncidencias($fechaInicio, $fechaFin);
      return $resultadoAuditoriaIncidencias;
    }
  }

  // Metodo para listar los registros de recepciones en la tabla de auditoria
  public function listarRegistrosRecepciones()
  {
    $resultadoAuditoriaRegistroRecepciones = $this->auditoriaModel->listarRegistrosRecepciones();
    return $resultadoAuditoriaRegistroRecepciones;
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
}
