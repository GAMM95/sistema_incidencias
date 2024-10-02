<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class AsignacionModel extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  // Metodo para obtener las asignaciones por ID
  public function obtenerAsignacionesPorId($numAsignacion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM ASIGNACION as
        WHERE ASI_numero = :numAsignacion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':numAsignacion', $numAsignacion, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener asignaciones por ID: " . $e);
      return null;
    }
  }

  // Metodo para registrar asignaciones
  public function insertarAsignacion() {}
}
