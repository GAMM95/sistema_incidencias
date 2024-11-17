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
        $sql = "SELECT * FROM ASIGNACION WHERE ASI_codigo = :numAsignacion";
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
        $this->auditoria->registrarEvento('ASIGNACION', 'Asignar incidencia');

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
        $sql = "SELECT * FROM vista_mantenimiento
            WHERE EST_descripcion IN ('EN ESPERA','RESUELTO')
            ORDER BY ASI_codigo DESC
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

  // Metodo para listar asignaciones segun el usuario 
  public function listarAsignacionesSoporte($usuario)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_mantenimiento
        WHERE USU_codigo = :usuarioAsignado
        AND EST_descripcion IN ('EN ESPERA', 'RESUELTO')
        ORDER BY INC_numero_formato DESC";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':usuarioAsignado', $usuario, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexion a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al listar asignaciones por usuario: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para editar asignacion
  public function editarAsignacion($usuario, $asignacion)
  {
    $conector = parent::getConexion();
    if ($conector != null) {
      $sql = "EXEC sp_actualizar_asignacion :num_asignacion, :usuario";
      $stmt = $conector->prepare($sql);
      $stmt->bindParam(':num_asignacion', $asignacion);
      $stmt->bindParam(':usuario', $usuario);
      $stmt->execute();

      $this->auditoria->registrarEvento('ASIGNACION', 'Actualiazr asignacion');
      return $stmt->rowCount() > 0 ? true : false;
    } else {
      throw new Exception("Error de conexion a la base de datos");
      return null;
    }
    try {
    } catch (PDOException $e) {
      throw new PDOException("Error al editar asignacion: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener el estado de la asignacion
  public function obtenerEstadoAsignacion($numeroAsignacion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT EST_codigo FROM ASIGNACION WHERE ASI_codigo = :numeroAsignacion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':numeroAsignacion', $numeroAsignacion, PDO::PARAM_INT);
        $stmt->execute(); // Ejecutar la consulta
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener el resultado
        return $result ? $result['EST_codigo'] : null;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener estado de la asignacion: " . $e->getMessage());
      return null;
    }
  }

  // Contar recepciones del ultimo mes para el administrador
  public function contarRecepcionesEnEsperaUltimoMesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as recepciones_en_espera_mes_actual FROM ASIGNACION 
          WHERE ASI_fecha >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
          AND EST_codigo = 5";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['recepciones_en_espera_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar recepciones en espera del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // Contar recepciones finalizadas en el mes actual
  public function contarRecepcionesFinalizadasUltimoMesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as recepciones_finalizadas_mes_actual FROM ASIGNACION A
          LEFT JOIN MANTENIMIENTO	M ON M.ASI_codigo = A.ASI_codigo
          WHERE ASI_fecha >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
          AND M.EST_codigo = 6";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['recepciones_finalizadas_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar recepciones en espera del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // Metodo para consultar incidencias asignadas
  public function buscarAsignaciones($usuario, $codigoPatrimonial, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_incidencias_asignadas :usuario, :codigoPatrimonial, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_INT);
        $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute(); // Ejecuta el query
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener los asignaciones: " . $e->getMessage());
    }
  }
}
