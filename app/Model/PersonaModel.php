<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class PersonaModel extends Conexion
{

  public function __construct()
  {
    parent::__construct();
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

        // Si el DNI no existe, procedemos a registrar la nueva persona
        $sql = "EXEC sp_registrar_persona :dni, :nombres, :apellidoPaterno, :apellidoMaterno, :celular, :email";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apellidoPaterno', $apellidoPaterno);
        $stmt->bindParam(':apellidoMaterno', $apellidoMaterno);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $auditoria = new AuditoriaModel($conector); // Crear la instancia de la clase Auditoria
        $auditoria->registrarEvento('PERSONA', 'Registro de persona');

        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
      }
    } catch (Exception $e) {
      throw new Exception("Error al registrar nueva persona: " . $e->getMessage());
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
        $sql = "UPDATE PERSONA SET PER_dni = ?, PER_nombres = ?, PER_apellidoPaterno = ?, 
        PER_apellidoMaterno = ?, PER_celular = ?, PER_email = ? WHERE PER_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([
          $dni,
          $nombres,
          $apellidoPaterno,
          $apellidoMaterno,
          $celular,
          $email,
          $codigoPersona
        ]);
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
  public function listarTrabajadores()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT PER_codigo, PER_dni,
                (PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno) AS persona,
                PER_celular, PER_email
                FROM PERSONA
                ORDER BY PER_codigo DESC";
        // -- OFFSET ? ROWS
        // -- FETCH NEXT ? ROWS ONLY";
        $stmt = $conector->prepare($sql);
        // $stmt->bindParam(1, $start, PDO::PARAM_INT);
        // $stmt->bindParam(2, $limit, PDO::PARAM_INT);
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
}
