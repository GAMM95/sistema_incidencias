<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class AsignacionModel extends Conexion
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

  // Metodo para obtener las asignaciones por ID
  public function obtenerAsignacionesPorId($numAsignacion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM ASIGNACION as WHERE ASI_numero = :numAsignacion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':numAsignacion', $numAsignacion, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexion a la base de datos");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener asignaciones por ID: " . $e->getMessage());
    }
  }

  // Metodo para registrar asignaciones
  public function insertarAsignacion($fecha, $hora, $usuario, $recepcion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_registrar_asignacion :fecha, :hora, :usuario, :recepcion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':recepcion', $recepcion);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('ASIGNACION', 'Incidencia asignada');

        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexion a la base de datos");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al insertar asignacion: " . $e->getMessage());
    }
  }

  // Metodo para contar el total de asignaciones
  public function contarAsignaciones()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS total FROM ASIGNACION a
      WHERE a.EST_codigo = 5";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al contar asignaciones: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar incidencias asignadas
  public function listarAsignaciones($start, $limit)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_asignaciones
            ORDER BY ASI_codigo
            OFFSET :start ROWS
            FETCH NEXT :limit ROWS ONLY";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexión a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al listar incidencias asignadas: " . $e->getMessage());
      return null;
    }
  }
}
