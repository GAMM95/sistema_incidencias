<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class CierreModel extends Conexion
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

  // Metodo para obtener cierres por ID
  public function obtenerCierrePorID($CieNumero)
  {
    $conector = parent::getConexion();
    try {
      $sql = "SELECT * FROM CIERRE c 
      WHERE CIE_numero = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute(([$CieNumero]));
      $registros = $stmt->fetch(PDO::FETCH_ASSOC);
      return $registros;
    } catch (PDOException $e) {
      echo "Error al obtener los registros de los cierres: " . $e->getMessage();
      return null;
    }
  }

  // Metodo para obtener el ultimo codigo registrado de cierre
  private function obtenerUltimoCodigoRegistrado()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT MAX(CIE_numero) AS ultimo_codigo FROM CIERRE";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['ultimo_codigo'];
      } else {
        throw new Exception("Error de conexión a la base de datos");
      }
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }

  // Metodo para insertar Cierre
  public function insertarCierre($fecha, $hora, $diagnostico, $documento, $recomendaciones, $operatividad, $mantenimiento, $usuario, $solucion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_insertar_cierre :fecha, :hora, :diagnostico, :documento, :recomendaciones, :operatividad, :mantenimiento, :usuario, :solucion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':diagnostico', $diagnostico);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':recomendaciones', $recomendaciones);
        $stmt->bindParam(':operatividad', $operatividad);
        $stmt->bindParam(':mantenimiento', $mantenimiento);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':solucion', $solucion);
        $stmt->execute();

        // Obtener el ultimo codigo registrado de cierre
        $numCierre = $this->obtenerUltimoCodigoRegistrado();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('CIERRE', 'Cerrar incidencia', $numCierre);

        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al insertar el cierre: " . $e->getMessage());
      return null;
    }
  }

  //  Metodo para eliminar CIERRE
  public function eliminarCierre($codigoCierre)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_eliminar_cierre :codigoCierre";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoCierre', $codigoCierre);
        $stmt->execute();
        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('CIERRE', 'Eliminar cierre', $codigoCierre);
        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al eliminar el cierre: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para editar cierres
  public function editarCierre($cierre, $documento, $condicion, $solucion, $diagnostico, $recomendaciones)
  {
    $conector = parent::getConexion();
    if ($conector != null) {
      $sql = "EXEC sp_actualizar_cierre :num_cierre, :documento, :condicion, :solucion, :diagnostico, :recomendaciones";
      $stmt = $conector->prepare($sql);
      $stmt->bindParam(':num_cierre', $cierre);
      $stmt->bindParam(':documento', $documento);
      $stmt->bindParam(':condicion', $condicion);
      $stmt->bindParam(':solucion', $solucion);
      $stmt->bindParam(':diagnostico', $diagnostico);
      $stmt->bindParam(':recomendaciones', $recomendaciones);
      $stmt->execute(); // Ejecutar el procedimiento almacenado

      // Registrar el evento en la auditoría
      $this->auditoria->registrarEvento('CIERRE', 'Actualizar cierre', $cierre);
      // Confirmar que se ha actualizado al menos una fila
      return $stmt->rowCount() > 0 ? true : false;
    } else {
      throw new Exception("Error de conexion a la base de datos");
      return null;
    }
    try {
    } catch (PDOException $e) {
      throw new PDOException("Error al editar el cierre: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar cierres Administrador - FORM CONSULTAR CIERRE
  public function listarCierresConsulta()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_cierres
        ORDER BY ultimaFecha DESC, ultimaHora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      echo "Error al listar cierres registrados para el administrador: " . $e->getMessage();
      return false;
    }
  }

  // Metodo para obtener la lista de incidencias cerradas para la tabla listar cierres
  public function listarCierres()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_cierres
        ORDER BY CIE_numero DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias cerradas: " . $e->getMessage());
      return null;
    }
  }

  // Contar incidencias del ultimo mes para el administrador
  public function contarCierresUltimoMesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_cierres_mes_actual";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cierres_mes_actual'];
      } else {
        throw new PDOException ("Error de conexión con la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al contar cierres del ultimo mes para el administrador y soporte: " . $e->getMessage());
      return null;
    }
  }

  // Contar incidencias del ultimo mes para el usuario
  public function contarCierresUltimoMesUsuario($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT cierres_mes_actual FROM vw_cierres_mes_actual_area
                WHERE area = :are_codigo";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $area, PDO::PARAM_INT); // Vinculamos el parámetro
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
          return $result['cierres_mes_actual'];
      } else {
          return 0; // Si no hay resultados, devolver 0
      }
      } else {
        throw new Exception("Error de conexión con la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al contar cierres del ultimo mes para el usuario: " . $e->getMessage());
      return null;
    }
  }

  public function buscarCierres($area, $codigoPatrimonial, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();

    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_incidencias_cerradas :area, :codigoPatrimonial, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
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
      throw new Exception("Error al obtener los cierres XD: " . $e->getMessage());
    }
  }

  // Metodo para consultar incidencias cerradas para la visualción de reportes
  public function buscarIncidenciaCerradas($usuario, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_filtrar_incidencias_cerradas :usuario, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las incidencias cerradas: " . $e->getMessage());
    }
  }

  // Metodo para listar los registros de cierres en la tabla de auditoria
  public function listarEventosCierres()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_eventos_cierres
                ORDER BY AUD_fecha DESC, AUD_hora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar eventos de cierres en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para consultar eventos de cierres - auditoria
  public function buscarEventosCierres($usuario, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_eventos_cierres :usuario, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
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
      throw new Exception("Error al consultar eventos de cierres en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }
}
