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

  // Metodo para obtener el ultimo codigo registrado de mantenimiento
  private function obtenerUltimoCodigoRegistrado()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT TOP 1 MAN_codigo FROM MANTENIMIENTO ORDER BY MAN_codigo DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        return $resultado[0]['codigo'];
      } else {
        throw new Exception("Error de conexión a la base de datos");
      }
    } catch (Exception $e) {
      echo $e->getMessage();
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

        // Obtener el ultimo codigo registrado de mantenimiento
        $numMantenimiento = $this->obtenerUltimoCodigoRegistrado();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('MANTENIMIENTO', 'Finalizar mantenimiento', $numMantenimiento);
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
        $this->auditoria->registrarEvento('MANTENIMIENTO', 'Encolar mantenimiento', $asignacion);
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
        $sql = "SELECT COUNT(*) as total FROM vw_incidencias_finalizadas";
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
        $sql = "SELECT * FROM vw_incidencias_finalizadas
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

  // Metodo para listar asignaciones segun el usuario 
  public function notificarIncidenciasMantenimiento($usuario)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS total FROM ASIGNACION ASI
                    INNER JOIN ESTADO E ON E.EST_codigo = ASI.EST_codigo
                    LEFT JOIN USUARIO U ON U.USU_codigo = ASI.USU_codigo
                    WHERE U.USU_codigo = :usuarioAsignado
                    AND ASI.EST_codigo = 5";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':usuarioAsignado', $usuario, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Cambié fetchAll a fetch para obtener solo un registro
        return (int)$result['total']; // Asegúrate de retornar un número entero
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al listar asignaciones por usuario: " . $e->getMessage());
    }
  }

  // Metodo para contar la cantidad de recepciones (asignaciones + mantenimiento) al mes
  public function totalRecepcionesAlMes()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT 
            SUM(CASE WHEN A.EST_codigo = 5 THEN 1 ELSE 0 END + CASE WHEN M.EST_codigo = 6 THEN 1 ELSE 0 END) AS total_recepciones_mes_actual
        FROM ASIGNACION A
        LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = A.ASI_codigo
        WHERE A.ASI_fecha >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_recepciones_mes_actual'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al contar recepciones al mes: " . $e->getMessage());
    }
  }

  // Metodo para listar asignaciones para el administrador
  public function listarAsignacionesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_mantenimiento
        ORDER BY INC_numero_formato DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al listar incidencias en mantenimiento para el administrador: " . $e->getMessage());
    }
  }

  // Metodo para listar incidencias con tiempo de mantenimiento
  public function listarIncidenciasMantenimiento()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_incidencias_mantenimiento 
                ORDER BY ultimaFecha DESC, ultimaHora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al listar incidencias con el tiempo de mantenimiento: " . $e->getMessage());
    }
  }

  // Metodo para listar asignaciones segun el usuario 
  public function listarAsignacionesSoporte($usuario)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_incidencias_mantenimiento
          WHERE USU_codigo = :usuarioAsignado
          ORDER BY ultimaFecha DESC, ultimaHora DESC";
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

  // Metodo para consultar incidencias asignadas
  public function buscarAsignacionesSoporte($usuario, $codigoPatrimonial, $fechaInicio, $fechaFin)
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

  // Metodo para listar eventos de mantenimiento
  public function listarEventosMantenimiento()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_eventos_mantenimiento
                  ORDER BY AUD_fecha DESC, AUD_hora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al listar eventos de mantimiento: " . $e->getMessage());
    }
  }
}
