<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class UsuarioModel extends Conexion
{

  public function __construct()
  {
    parent::__construct();
  }

  // Método para iniciar sesión
  public function iniciarSesion($username, $password)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {

        // Obtener IP del cliente
        $auditoria = new AuditoriaModel($conector);
        $ipCliente = $auditoria->getIP();

        // Obtener el nombre del equipo usando el IP
        $nombreEquipo = gethostbyaddr($ipCliente);

        // Ejecutar el procedimiento almacenado
        $query = "EXEC sp_login :username, :password";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Obtener el resultado del procedimiento
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontraron resultados
        if ($resultado && !isset($resultado['MensajeError'])) {
          // Credenciales correctas, inicia sesión
          session_start();
          $_SESSION['nombreDePersona'] = $resultado['PER_nombres'] . ' ' . $resultado['PER_apellidoPaterno'];
          $_SESSION['area'] = $resultado['ARE_nombre'];
          $_SESSION['codigoArea'] = $resultado['ARE_codigo'];
          $informacionUsuario = $this->obtenerInformacionUsuario($username, $password);
          $codigo = $informacionUsuario['codigo'];
          $usuario = $informacionUsuario['usuario'];
          $_SESSION['codigoUsuario'] = $codigo;
          $_SESSION['usuario'] = $usuario;
          $_SESSION['rol'] = $this->obtenerRolPorId($username);

          // Log de inicio de sesión
          $this->registrarLog($username, $codigo, $ipCliente, $nombreEquipo);

          // Registrar el evento en la auditoría
          $auditoria->registrarEvento('USUARIO', 'Iniciar sesión');
          return true;
        } else {
          // Si las credenciales son incorrectas o hay un mensaje de error
          $mensajeError = isset($resultado['MensajeError']) ? $resultado['MensajeError'] : "Credenciales incorrectas.";
          header("Location: index.php?state=failed&message=" . urlencode($mensajeError));
          exit();
        }
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al iniciar sesión: " . $e->getMessage());
    }
  }

  // Metodo para registrar los logeos
  private function registrarLog($username, $codigo, $ipCliente, $nombreEquipo)
  {
    $logData = "------- START LOGIN LOGS ---------" . PHP_EOL;
    $logData .= "Fecha y Hora: " . date("Y-m-d H:i:s") . PHP_EOL;
    $logData .= "Nombre de Persona: " . $_SESSION['nombreDePersona'] . PHP_EOL;
    $logData .= "Rol: " . $_SESSION['rol'] . PHP_EOL;
    $logData .= "Codigo Area: " . $_SESSION['codigoArea'] . PHP_EOL;
    $logData .= "Área: " . $_SESSION['area'] . PHP_EOL;
    $logData .= "Código de Usuario: " . $codigo . PHP_EOL;
    $logData .= "Usuario: " . $username . PHP_EOL;
    $logData .= "IP: " . $ipCliente . PHP_EOL;
    $logData .= "Nombre de Equipo: " . $nombreEquipo . PHP_EOL;
    $logData .= "------- END LOGIN LOGS ---------" . PHP_EOL . PHP_EOL; // Separador entre logs
    file_put_contents('logs/log.txt', $logData, FILE_APPEND);
  }

  /// Método para obtener la información del usuario logueado
  private function obtenerInformacionUsuario($username, $password)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $consulta = "SELECT USU_codigo as codigo, USU_nombre as usuario 
        FROM USUARIO u 
        WHERE USU_nombre = :username AND USU_password = :password";
        $stmt = $conector->prepare($consulta);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $fila = $stmt->fetch();

        if ($fila) {
          return $fila;
        } else {
          return null;
        }
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener información del usuario: " . $e->getMessage());
      return null;
    }
  }


  // Metodo para obtener el id del usuario logueado
  public function obtenerRolPorId($username)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $consulta = "SELECT ROL_nombre as rol
          FROM USUARIO u
          INNER JOIN ROL r ON r.ROL_codigo = u.ROL_codigo 
          WHERE USU_nombre = :username";
        $stmt = $conector->prepare($consulta);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // $fila = $stmt->fetch();
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($fila) {
          return $fila['rol'];
        } else {
          return null;
        }
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el rol del usuario: " . $e->getMessage());
    }
  }

  // Método para validar la existencia de un usuario
  public function validarUsuarioExistente($username)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) FROM USUARIO WHERE USU_nombre = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();
        return $count > 0;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al validar nombre de usuario: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para validar si persona ya tiene un usuario
  public function validarPersonaConUsuario($persona)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) FROM USUARIO WHERE PER_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$persona]);
        $count = $stmt->fetchColumn();
        return $count > 0;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al validar nombre de usuario: " . $e->getMessage());
      return null;
    }
  }

  // Método para registrar un nuevo usuario
  public function guardarUsuario($username, $password, $persona, $rol, $area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Ejecutar el procedimiento almacenado para registrar el usuario
        $sql = "EXEC sp_registrar_usuario :username, :password, :persona, :rol, :area";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':persona', $persona, PDO::PARAM_INT);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $auditoria = new AuditoriaModel($conector);
        $auditoria->registrarEvento('USUARIO', 'Registro de usuario');

        return true; // Registro exitoso
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al guardar usuario: " . $e->getMessage());
    }
  }


  // Metodo para editar datos del usuario utilizando un procedimiento almacenado
  public function editarUsuario($codigoUsuario, $username, $password, $persona, $rol, $area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_editar_usuario :codigoUsuario, :username, :password, :persona, :rol, :area";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoUsuario', $codigoUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':persona', $persona, PDO::PARAM_INT);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $auditoria = new AuditoriaModel($conector);
        $auditoria->registrarEvento('USUARIO', 'Actualización de usuario');

        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexión a la base de datos");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al actualizar usuario: " . $e->getMessage());
    }
  }

  // Metodo para listar todos los usuarios registrados
  public function listarUsuarios()
  {
    try {
      $conector = parent::getConexion();
      if ($conector != null) {
        $sql = "SELECT * FROM vista_usuarios
        ORDER BY USU_codigo DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception('Error de conexion en la base de datos');
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar usuarios: " . $e->getMessage());
    }
  }

  // Contar cantidad de usuarios para la pantalla de inicio del administrador 
  public function contarUsuarios()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as usuarios_total FROM USUARIO
        WHERE EST_codigo = 1";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['usuarios_total'];
      } else {
        throw new Exception('Error de conexion en la base de datos');
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al contar usuarios: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para setear datos personales del usuario logueado
  public function setearDatosUsuario($user_id)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
      }
      $sql = "SELECT 
      USU_nombre, USU_password, PER_dni, PER_nombres, PER_apellidoPaterno, PER_apellidoMaterno,
      (PER_nombres +' '+ PER_apellidoPaterno +' '+ PER_apellidoMaterno) AS Persona,
      ROL_nombre, ARE_nombre, PER_celular, PER_email
      FROM USUARIO u
      INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
      INNER JOIN ROL r ON r.ROL_codigo = u.ROL_codigo
      INNER JOIN AREA a ON a.ARE_codigo = u.ARE_codigo
      WHERE u.USU_codigo = :user_id";
      $stmt = $conector->prepare($sql);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      return $user;
    } catch (PDOException $e) {
      echo "Error al setear datos personales del usuario: " . $e->getMessage();
      return null;
    }
  }

  // Metodo para obtener usuario por ID
  public function obtenerUsuarioPorID($codigoUsuario)
  {
    $conector = parent::getConexion();
    try {
      $sql = "SELECT * FROM USUARIO WHERE USU_codigo = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$codigoUsuario]);
      $registros = $stmt->fetch(PDO::FETCH_ASSOC);
      return $registros;
    } catch (PDOException $e) {
      throw new Exception("Error al obtener usuario: " . $e->getMessage());
    }
  }

  // Metodo para editar perfil del usuario
  public function editarPerfilUsuario($codigoUsuario, $username, $password, $dni, $nombrePersona, $apellidoPaterno, $apellidoMaterno, $celular, $email)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $query = "EXEC sp_editar_perfil :codigoUsuario, :username, :password, :dni, :nombrePersona, :apellidoPaterno, :apellidoMaterno, :celular, :email";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':codigoUsuario', $codigoUsuario);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':nombrePersona', $nombrePersona);
        $stmt->bindParam(':apellidoPaterno', $apellidoPaterno);
        $stmt->bindParam(':apellidoMaterno', $apellidoMaterno);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $auditoria = new AuditoriaModel($conector);
        $auditoria->registrarEvento('PERSONA - USUARIO', 'Actualizacion de perfil');

        return true;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al actualizar el perfil del usuario: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para filtrar usuarios por termino de busqueda
  public function filtrarUsuarios($terminoBusqueda)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_usuarios
        WHERE persona LIKE :terminoBusqueda
        OR ARE_nombre LIKE :terminoBusqueda
        OR USU_nombre LIKE :terminoBusqueda
        OR ROL_nombre LIKE :terminoBusqueda";
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
      throw new PDOException("Error al filtrar usuarios: " . $e->getMessage());
      return null;
    }
  }

  // Método para habilitar usuarios
  public function habilitarUsuario($codigoUsuario)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_habilitar_usuario :codigoUsuario";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoUsuario', $codigoUsuario, PDO::PARAM_INT);
        $stmt->execute();


        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $auditoria = new AuditoriaModel($conector);
          $auditoria->registrarEvento('USUARIO', 'Habilitar usuario');
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al habilitar usuario: " . $e->getMessage());
      return null;
    }
  }

  // METODO PARA DESHABILITAR USUARIO
  public function deshabilitarUsuario($codigoUsuario)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_deshabilitar_usuario :codigoUsuario";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoUsuario', $codigoUsuario, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {

          // Registrar el evento en la auditoría
          $auditoria = new AuditoriaModel($conector);
          $auditoria->registrarEvento('USUARIO', 'Deshabilitar usuario');
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al deshabilitar usuario: " . $e->getMessage());
    }
  }
}
