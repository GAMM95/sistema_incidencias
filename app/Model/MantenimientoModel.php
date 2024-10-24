<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class MantenimientoModel extends Conexion
{
  private $auditoria;

  public function __construct()
  {
    parent::__construct();
    $conector = parent::getConexion();

    // Inicializar la instancia de AuditoriaModel
    if ($conector != null) {
      $this->auditoria = new AuditoriaModel($conector);
    } else {
      throw new Exception("Error de conexión a la base de datos");
    }
  }

  // Metodo para registrar mantenimiento
  public function resolverIncidencia($asignacion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_resolver_incidencia :asignacion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':asignacion', $asignacion);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('MANTENIMIENTO', 'Finalizar mantenimiento');
        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al resolver incidencia: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para registrar mantenimiento
  public function encolarIncidencia($asignacion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_encolar_incidencia :asignacion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':asignacion', $asignacion);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('MANTENIMIENTO', 'Encolar mantenimiento');
        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al encolar incidencia: " . $e->getMessage());
      return null;
    }
  }
}
