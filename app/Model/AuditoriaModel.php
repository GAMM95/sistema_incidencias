<?php
require_once 'config/conexion.php';

class AuditoriaModel extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getIP()
  {
    return $this->obtenerIP();
  }

  // Metodo para obtener la ip del equipo
  private function obtenerIP()
  {
    // Comprobar si hay proxies
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      // En caso de que esté detrás de un proxy
      $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    // Validar que la IP sea válida (IPv4 o IPv6)
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
      return $ip; // Si es IPv4
    } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
      return $ip; // Si es IPv6
    } else {
      // Devolver una dirección IP por defecto si no es válida
      return 'IP no válida';
    }
  }

  // Método para registrar un evento de auditoría
  public function registrarEvento($tabla, $operacion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Obtener IP del cliente y nombre del equipo
        $ipCliente = $this->obtenerIP();
        $nombreEquipo = gethostbyaddr($ipCliente);

        // Capturar el usuario logueado desde la sesión
        $usuario = isset($_SESSION['codigoUsuario']) ? $_SESSION['codigoUsuario'] : null;

        // Insertar en la tabla AUDITORIA
        $sql = "INSERT INTO AUDITORIA (AUD_fecha, AUD_hora, AUD_usuario, AUD_tabla, AUD_operacion, AUD_ip, AUD_nombreEquipo) 
                    VALUES (GETDATE(), CONVERT(TIME, GETDATE()), :usuario, :tabla, :operacion, :ipCliente, :nombreEquipo)";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':tabla', $tabla);
        $stmt->bindParam(':operacion', $operacion);
        $stmt->bindParam(':ipCliente', $ipCliente);
        $stmt->bindParam(':nombreEquipo', $nombreEquipo);
        $stmt->execute();
        return true;
      } else {
        throw new Exception("Error de conexión a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al registrar en la auditoría: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar los eventos totales en la tabla de auditoria
  public function listarEventosTotales()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_eventos_totales
        ORDER BY AUD_fecha DESC, AUD_hora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar eventos totales en la tabla de auditoria: " . $e->getMessage());
    }
  }

  // Metodo para listar los registros de inicio de sesion en la tabla auditoria
  public function listarRegistrosInicioSesion()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_auditoria_login
        ORDER BY fechaFormateada DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar registros de inicio de sesion en la tabla auditoria: " . $e->getMessage());
    }
  }

  // Metodo para consultar inicios de sesion en la tabla de auditoria
  public function consultarRegistrosInicioSesion($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_auditoria_login :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al consultar registros de inicio de sesion en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar los registros de incidencias en la tabla auditoria
  public function listarRegistrosIncidencias()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT fechaFormateada, NombreCompleto, INC_numero_formato, ARE_nombre, AUD_ip, AUD_nombreEquipo FROM vw_auditoria_registrar_incidencia
       ORDER BY fechaFormateada DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al consultar registros de incidencias en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para consultar registros de incidencias en la tabla de auditoria
  public function consultarRegistrosIncidencias($fechaInicio = null, $fechaFin = null)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_auditoria_registro_incidencia :fechaInicio, :fechaFin";
        // ORDER BY fechaFormateada DESC";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al consultar registros de incidencias en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar los registros de recepciones en la tabla de auditoria
  public function listarRegistrosRecepciones()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT fechaFormateada, NombreCompleto, INC_numero_formato, ARE_nombre, AUD_ip, AUD_nombreEquipo FROM vw_auditoria_registrar_recepcion
       ORDER BY fechaFormateada DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al consultar registros de recepciones en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para consultar registros de recepciones en la tabla de auditoria
  public function consultarRegistrosRecepciones($fechaInicio = null, $fechaFin = null)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_auditoria_recepcion_incidencia :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al consultar registros de recepciones en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar registros de asignaciones en la tabla de auditoria
  public function listarRegistrosAsignaciones()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // $sql = "EXEC sp_consultar_auditoria_asignacion_incidencia";
        $sql = "SELECT * FROM vista_incidencias_totales_administrador";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar registros de asignaciones en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para consultar registros de asignaciones en la tabla de auditoria
  public function consultarRegistrosAsignaciones($fechaInicio = null, $fechaFin = null)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_auditoria_asignacion_incidencia :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al consultar registros de asignaciones en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }
}
