<?php
require_once 'config/conexion.php';

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
        $ipCliente = $this->obtenerIP();

        // Obtener el nombre del equipo usando el IP
        $nombreEquipo = gethostbyaddr($ipCliente);

        // Ejecutar el procedimiento almacenado
        $query = "EXEC SP_Usuario_login :username, :password";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Obtener el resultado del procedimiento
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC); // Usar fetch asociativo

        if ($resultado) {
          if (isset($resultado['ErrorMessage'])) {
            // Manejar el mensaje de error
            if ($resultado['ErrorMessage'] === 'Usuario inactivo') {
              // Redirigir si el usuario está inactivo
              header("Location: index.php?state=inactive");
            } else {
              // Redirigir si las credenciales son incorrectas
              header("Location: index.php?state=failed&message=" . urlencode($resultado['ErrorMessage']));
            }
            exit();
          } else {
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


            // $_SESSION['codigoUsuario'] = $resultado['USU_codigo'];
            // $_SESSION['usuario'] = $username;
            // $_SESSION['rol'] = $resultado['ROL_nombre'];
            // Log de inicio de sesión
            $this->registrarLog($username, $codigo, $ipCliente, $nombreEquipo);
            // $this->registrarLog($username, $_SESSION['codigoUsuario'], $ipCliente, $nombreEquipo);


            return true;
          }
        } else {
          // Redirigir si las credenciales son incorrectas
          header("Location: index.php?state=failed&message=" . urlencode("Credenciales incorrectas."));
          exit();
        }
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al iniciar sesión: " . $e->getMessage());
    }
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

    // Validar que la IP sea una dirección IPv4
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
      return $ip;
    } else {
      // Devolver una dirección IP por defecto si no es válida
      return '0.0.0.0'; // O maneja este caso como desees
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
  // private function obtenerInformacionUsuario($username, $password)
  // {
  //   $conector = parent::getConexion();  // Obtiene la conexión de la clase padre
  //   try {
  //     if ($conector != null) {
  //       // Consulta para obtener el código y nombre del usuario
  //       $consulta = "SELECT USU_codigo as codigo, USU_nombre as usuario 
  //                        FROM USUARIO 
  //                        WHERE USU_nombre = :username AND USU_password = :password";

  //       $stmt = $conector->prepare($consulta);
  //       $stmt->bindParam(':username', $username);
  //       $stmt->bindParam(':password', $password);

  //       // Ejecuta la consulta
  //       $stmt->execute();

  //       // Obtiene el resultado como un array asociativo
  //       $fila = $stmt->fetch(PDO::FETCH_ASSOC);

  //       // Verifica si se encontró el usuario
  //       if ($fila) {
  //         return $fila;  // Devuelve la información del usuario
  //       } else {
  //         // Si no se encontró, podrías devolver un mensaje o manejarlo en otra parte
  //         file_put_contents('logs/debug_log.txt', "No se encontró el usuario: $username" . PHP_EOL, FILE_APPEND);
  //         return null;
  //       }
  //     } else {
  //       throw new Exception("Error de conexión a la base de datos.");
  //     }
  //   } catch (PDOException $e) {
  //     // En caso de excepción, registrar el error
  //     file_put_contents('logs/error_log.txt', "Error de PDO: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
  //     throw new PDOException("Error al obtener información del usuario: " . $e->getMessage());
  //   }
  // }

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
        $sql = "EXEC SP_Registrar_Usuario :username, :password, :persona, :rol, :area";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':persona', $persona, PDO::PARAM_INT);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->execute();
        return true; // Registro exitoso
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al guardar usuario: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para editar datos del usuario utilizando un procedimiento almacenado
  public function editarUsuario($codigoUsuario, $username, $password, $persona, $rol, $area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_editarUsuario :codigoUsuario, :username, :password, :persona, :rol, :area";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoUsuario', $codigoUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':persona', $persona, PDO::PARAM_INT);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->execute();

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
        $query = "EXEC EditarPersonaYUsuario :codigoUsuario, :username, :password, :dni, :nombrePersona, :apellidoPaterno, :apellidoMaterno, :celular, :email";
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
        $sql = "EXEC sp_habilitarUsuario :codigoUsuario";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoUsuario', $codigoUsuario, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
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
        $sql = "EXEC sp_deshabilitarUsuario :codigoUsuario";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoUsuario', $codigoUsuario, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
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
