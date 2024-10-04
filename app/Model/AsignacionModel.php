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
      throw new PDOException("Error al obtener asignaciones por ID: " . $e);
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
      throw new PDOException("Error al insertar asignacion: " . $e);
    }
  }


  
}
