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
}
