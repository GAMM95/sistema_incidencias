<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class BienModel extends Conexion
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

  // Metodo para obtener areas por el ID
  public function obtenerTipoBienPorID($codigoBien)
  {
    try {
      $conector = $this->getConexion();
      if ($conector != null) {
        $sql = "SELECT * FROM BIEN WHERE BIE_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoBien]);
        $registros = $stmt->fetch(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el tipo de bien: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para validar existencia de codigo de bien
  public function validarBienExistente($codigoIdentificador)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Extraer los primeros 8 dígitos del código patrimonial
        $codigoParcial = substr($codigoIdentificador, 0, 8);
        $sql = "SELECT COUNT(*) FROM BIEN WHERE BIE_codigoIdentificador = ?
        AND EST_codigo = 1";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoParcial]);
        // Obtener el conteo de coincidencias
        $count = $stmt->fetchColumn();
        return $count > 0;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al verificar el bien: " . $e->getMessage());
    }
  }

  // Metodo para obtener el ultimo codigo registrado de bien
  private function obtenerUltimoCodigoBien()
  {
    try {
      $conector = $this->getConexion();
      if ($conector != null) {
        $sql = "SELECT MAX(BIE_codigo) AS ultimoCodigo FROM BIEN";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['ultimoCodigo'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el último código de bien: " . $e->getMessage());
    }
  }

  // Metodo para insertar el tipo de bien
  public function insertarTipoBien($codigoIdentificador, $nombreBien)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // $sql = "INSERT INTO BIEN (BIE_codigoIdentificador, BIE_nombre) VALUES (?, ?)";
        $sql = "EXEC sp_registrar_bien :codigoIdentificador, :nombreBien";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoIdentificador', $codigoIdentificador);
        $stmt->bindParam(':nombreBien', $nombreBien);
        $stmt->execute();
        // Obtener el ID del bien recién insertado
        $bienId = $this->obtenerUltimoCodigoBien();

        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $this->auditoria->registrarEvento('BIEN', 'Registrar bien', $bienId);
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al insertar bien: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para editar el tipo de bien
  public function editarTipoBien($codigoIdentificador, $nombreTipoBien, $codigoBien)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_editar_bien :codigoIdentificador, :nombreTipoBien, :codigoBien";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoIdentificador', $codigoIdentificador);
        $stmt->bindParam(':nombreTipoBien', $nombreTipoBien);
        $stmt->bindParam(':codigoBien', $codigoBien, PDO::PARAM_INT);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('BIEN', 'Actualizar bien', $codigoBien);
        return $stmt->rowCount();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al editar el tipo de bien: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar los bienes
  public function listarBienes()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_bienes";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al listar bienes: " . $e->getMessage());
      return null;
    }
  }

  // Método para habilitar bien
  public function habilitarBien($codigoBien)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_habilitar_bien :codigoBien";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoBien', $codigoBien, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $this->auditoria->registrarEvento('BIEN', 'Habilitar bien', $codigoBien);
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al habilitar bien: " . $e->getMessage());
      return null;
    }
  }

  // METODO PARA DESHABILITAR Bien
  public function deshabilitarBien($codigoBien)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_deshabilitar_bien :codigoBien";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoBien', $codigoBien, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $this->auditoria->registrarEvento('BIEN', 'Deshabilitar bien', $codigoBien);
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al deshabilitar bien: " . $e->getMessage());
    }
  }

  // Metodo para listar eventos de bienes
  public function listarEventosBienes()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_eventos_bienes
          ORDER BY AUD_fecha DESC, AUD_hora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar eventos de bienes en la tabla de auditoria: " . $e->getMessage());
    }
  }

  // Metodo para consultar eventos bienes - auditoria
  public function buscarEventosBienes($usuario, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_eventos_bienes :usuario, :fechaInicio, :fechaFin";
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
      throw new Exception("Error al consultar eventos bienes en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }
}
