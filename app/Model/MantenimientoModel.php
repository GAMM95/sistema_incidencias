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

  // Metodo para contar incidencias finalizadas
  public function contarIncidenciasFinalizadas()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as total FROM MANTENIMIENTO m
      WHERE m.EST_codigo = 6";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al contar incidencias finalizadas sin cerrar: " . $e->getMessage());
    }
  }

  // Metodo para listar incidencias finalizadas en mantenimiento
  public function listarIncidenciasFinalizadas($start, $limit)
  {
    $conector = parent::getConexion();
    if ($conector != null) {
      try {
        $sql = "SELECT * FROM vista_matenimiento
            ORDER BY 
              SUBSTRING(INC_numero_formato, CHARINDEX('-', INC_numero_formato) + 1, 4) DESC,
              INC_numero_formato DESC
              OFFSET :start ROWS
              FETCH NEXT :limit ROWS ONLY";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $registros;
      } catch (PDOException $e) {
        echo "Error al listar incidencias finalizadas: " . $e->getMessage();
        return null;
      }
    } else {
      echo "Error de conexión a la base de datos.";
      return null;
    }
  }
}
