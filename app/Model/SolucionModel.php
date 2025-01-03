<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class SolucionModel extends Conexion
{

  private $auditoria;

  public function __construct()
  {
    parent::__construct();
    $conector = parent::getConexion();
    // Inicializar la instancia de AuditoriaModel
    if ($conector != null) {
      $this->auditoria = new AuditoriaModel($conector);
    }
  }

  // Metodo para obtener soluciones por el ID
  public function obtenerSolicionPorID($codigoSolucion)
  {
    try {
      $conector = $this->getConexion();
      if ($conector != null) {
        $sql = "SELECT * FROM SOLUCION WHERE SOL_codigo = :codigoSolucion";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoSolucion]);
        $registros = $stmt->fetch(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener la solución: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener el ultimo codigo registrado de solucion
  private function obtenerUltimoCodigoSolucion()
  {
    try {
      $conector = $this->getConexion();
      if ($conector != null) {
        $sql = "SELECT MAX(SOL_codigo) AS ultimoCodigo FROM SOLUCION";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['ultimoCodigo'];
      } else {
        throw new Exception("Error de conexión a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el último código de solución: " . $e->getMessage());
    }
  }

  // Metodo para insertar la solución
  public function insertarSolucion($descripcion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_registrar_solucion :descripcion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->execute();

        // Obtener el ultimo codigo de la solución
        $solucionID = $this->obtenerUltimoCodigoSolucion();

        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $this->auditoria->registrarEvento('SOLUCION', 'Registrar solución', $solucionID);
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexión a la base de datos");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al insertar solución: " . $e->getMessage());
    }
  }

  // Metodo para editar el tipo de bien
  public function editarSolucion($descripcion, $codigoSolucion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $query = "EXEC sp_editar_solucion :descripcion, :codigoSolucion";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':codigoSolucion', $codigoSolucion);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('SOLUCION', 'Actualizar solución', $codigoSolucion);

        return $stmt->rowCount();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al editar la soliución: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar las soluciones
  public function listarSoluciones()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_soluciones";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al listar soluciones: " . $e->getMessage());
      return null;
    }
  }

  // Método para habilitar solucion 
  public function habilitarSolucion($codigoSolucion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_habilitar_solucion :codigoSolucion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoSolucion', $codigoSolucion, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $this->auditoria->registrarEvento('SOLUCION', 'Habilitar solución', $codigoSolucion);
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al habilitar solución: " . $e->getMessage());
      return null;
    }
  }

  // METODO PARA DESHABILITAR SOLUCIONES
  public function deshabilitarSolucion($codigoSolucion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_deshabilitar_solucion :codigoSolucion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoSolucion', $codigoSolucion, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $this->auditoria->registrarEvento('SOLUCION', 'Deshabilitar solución', $codigoSolucion);
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al deshabilitar solución: " . $e->getMessage());
    }
  }

  // Metodo para listar eventos de soluciones
  public function listarEventosSoluciones()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_eventos_soluciones
            ORDER BY AUD_fecha DESC, AUD_hora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar eventos de soluciones en la tabla de auditoria: " . $e->getMessage());
    }
  }

  // Metodo para consultar eventos soluciones - auditoria
  public function buscarEventosSoluciones($usuario, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_eventos_soluciones :usuario, :fechaInicio, :fechaFin";
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
      throw new Exception("Error al consultar eventos de soluciones en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }
}
