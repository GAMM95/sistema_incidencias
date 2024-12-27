<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class PersonaModel extends Conexion
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

  // Método para validar la existencia de un DNI
  public function validarDniExistente($dni)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) FROM PERSONA WHERE PER_dni = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$dni]);
        $count = $stmt->fetchColumn();
        return $count > 0;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al verificar el DNI: " . $e->getMessage());
      return null;
    }
  }

  // Método para registrar nueva persona
  public function registrarPersona($dni, $nombres, $apellidoPaterno, $apellidoMaterno, $celular, $email)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Primero validamos la existencia del DNI
        if ($this->validarDniExistente($dni)) {
          throw new Exception("El DNI ya está registrado.");
        }

        // Insertar la nueva persona directamente en la base de datos
        $sql = "EXEC sp_registrar_persona :dni, :nombres, :apellidoPaterno, :apellidoMaterno, :celular, :email";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apellidoPaterno', $apellidoPaterno);
        $stmt->bindParam(':apellidoMaterno', $apellidoMaterno);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Obtener el ID de la persona recién insertada
        $personaId = $this->obtenerUltimoCodigoPersona();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('PERSONA', 'Registrar persona', $personaId);

        return true;
      } else {
        throw new Exception("Error de conexión a la base de datos");
      }
    } catch (Exception $e) {
      throw new Exception("Error al registrar nueva persona: " . $e->getMessage());
    }
  }

  // Metodo para obtener el ultimo codigo registrado de persona
  private function obtenerUltimoCodigoPersona()
  {
    try {
      $conector = $this->getConexion();
      if ($conector != null) {
        $sql = "SELECT MAX(PER_codigo) AS ultimoCodigo FROM PERSONA";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['ultimoCodigo'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el último código de persona: " . $e->getMessage());
    }
  }

  // Método para obtener una persona por ID
  public function obtenerPersonaPorId($codigoPersona)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM PERSONA WHERE PER_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoPersona]);
        $registros = $stmt->fetch(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener la persona: " . $e->getMessage());
      return null;
    }
  }

  // Método para actualizar datos de la persona
  public function editarPersona($dni, $nombres, $apellidoPaterno, $apellidoMaterno, $celular, $email, $codigoPersona)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // $sql = "UPDATE PERSONA SET PER_dni = ?, PER_nombres = ?, PER_apellidoPaterno = ?, 
        // PER_apellidoMaterno = ?, PER_celular = ?, PER_email = ? WHERE PER_codigo = ?";
        $sql = "EXEC sp_editar_persona :codigoPersona, :dni, :nombres, :apellidoPaterno, :apellidoMaterno, :celular, :email";
        $stmt = $conector->prepare($sql);
        $stmt -> bindParam(':dni', $dni);
        $stmt -> bindParam(':nombres', $nombres);
        $stmt -> bindParam(':apellidoPaterno', $apellidoPaterno);
        $stmt -> bindParam(':apellidoMaterno', $apellidoMaterno);
        $stmt -> bindParam(':celular', $celular);
        $stmt -> bindParam(':email', $email);
        $stmt -> bindParam(':codigoPersona', $codigoPersona, PDO::PARAM_INT);
        $stmt -> execute();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('PERSONA', 'Actualizar persona', $codigoPersona);
        return $stmt->rowCount();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al actualizar la persona: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la lista de personas
  public function listarPersonas()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_personas
                ORDER BY PER_codigo DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las personas: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para contar personas para filtrar tabla
  public function contarTrabajadores()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as total FROM PERSONA";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
      } else {
        throw new Exception("Error de conexion con la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al contar trabajadores: " . $e->getMessage());
      return null;
    }
  }

  // Método para filtrar personas por término de búsqueda
  public function filtrarPersonas($terminoBusqueda)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT PER_codigo, PER_dni,
                (PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno) AS persona,
                PER_celular, PER_email
                FROM PERSONA
                WHERE persona LIKE :terminoBusqueda
                OR PER_dni LIKE :terminoBusqueda
                OR PER_celular LIKE :terminoBusqueda
                OR PER_email LIKE :terminoBusqueda";
        $stmt = $conector->prepare($sql);
        $terminoBusqueda = "%$terminoBusqueda%";
        $stmt->bindParam(':terminoBusqueda', $terminoBusqueda, PDO::PARAM_STR);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al filtrar personas: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar eventos de personas
  public function listarEventosPersonas()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_eventos_personas
          ORDER BY AUD_fecha DESC, AUD_hora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar eventos de personas en la tabla de auditoria: " . $e->getMessage());
    }
  }

  // Metodo para consultar eventos personas - auditoria
  public function buscarEventosPersonas($usuario, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_eventos_personas :usuario, :fechaInicio, :fechaFin";
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
      throw new Exception("Error al consultar eventos personas en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }
}
