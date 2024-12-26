<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class UsuarioModel extends Conexion
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

  // Método para iniciar sesión
  public function iniciarSesion($username, $password, $digitos = null)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {

        // Obtener IP del cliente
        $auditoria = new AuditoriaModel($conector);
        $ipCliente = $auditoria->getIP();

        // Obtener el nombre del equipo usando el IP
        $nombreEquipo = gethostbyaddr($ipCliente);

        // Verificar si el usuario es "ADMIN" o si no se proporcionaron los dos primeros campos
        if (strtoupper($username) === 'ADMIN' || empty($username) || empty($password)) {
          $digitos = null; // No se requiere autenticación en 2 pasos
        }

        // Ejecutar el procedimiento almacenado
        $query = "EXEC sp_login :username, :password, :digitos";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Manejar el valor de digitos
        if ($digitos !== null) {
          $stmt->bindParam(':digitos', $digitos);
        } else {
          $stmt->bindValue(':digitos', null, PDO::PARAM_NULL);
        }

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
          $this->auditoria->registrarEvento('USUARIO', 'Iniciar sesión');
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
        // Preparar la llamada al procedimiento almacenado
        $consulta = 'EXEC sp_verificar_usuario :username, :password';
        $stmt = $conector->prepare($consulta);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Validar el resultado
        if ($resultado) {
          if (isset($resultado['Resultado']) && $resultado['Resultado'] === 'Autenticación exitosa') {
            // Devolver información del usuario si la autenticación es exitosa
            return [
              'codigo' => $resultado['codigo'],
              'usuario' => $resultado['usuario']
            ];
          } else {
            // Devolver el mensaje de error
            return $resultado['Resultado'];
          }
        } else {
          return null;
        }
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener información del usuario: " . $e->getMessage());
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
  public function validarUsuarioExistente($username, $codigoUsuario = null)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) FROM USUARIO WHERE USU_nombre = :username";
        if ($codigoUsuario) {
          $sql .= " AND USU_codigo != :codigoUsuario";
        }
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':username', $username);
        if ($codigoUsuario) {
          $stmt->bindParam(':codigoUsuario', $codigoUsuario, PDO::PARAM_INT);
        }
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count == 0; // Devolver true si no existe, false si existe
      } else {
        throw new Exception("Error de conexion a la base de datos");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al validar nombre de usuario: " . $e->getMessage());
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
        $sql = "EXEC sp_registrar_usuario :username, :password, :persona, :rol, :area";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':persona', $persona, PDO::PARAM_INT);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->execute();

        // Recuperar el código del usuario recién insertado
        $usuarioId = $this->obtenerUltimoCodigoUsuario();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('USUARIO', 'Registrar usuario', $usuarioId);

        return true; // Registro exitoso
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al guardar usuario: " . $e->getMessage());
    }
  }

  // Metodo para obtener el ultimo codigo de usuario registrado
  private function obtenerUltimoCodigoUsuario()
  {
    try {
      $conector = $this->getConexion();
      if ($conector != null) {
        $sql = "SELECT MAX(USU_codigo) AS ultimoCodigo FROM USUARIO";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['ultimoCodigo'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el último código de usuario: " . $e->getMessage());
    }
  }

  // Metodo para editar datos del usuario utilizando un procedimiento almacenado
  public function editarUsuario($codigoUsuario, $username, $persona, $rol, $area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_editar_usuario :codigoUsuario, :username, :persona, :rol, :area";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoUsuario', $codigoUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':persona', $persona, PDO::PARAM_INT);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('USUARIO', 'Editar usuario', $codigoUsuario);

        // Confirmar que se ha actualizado al menos una fila
        return $stmt->rowCount() > 0 ? true : false;
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
  public function editarPerfilUsuario($codigoUsuario, $nombrePersona, $apellidoPaterno, $apellidoMaterno, $celular, $email)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $query = "EXEC sp_editar_perfil :codigoUsuario, :nombrePersona, :apellidoPaterno, :apellidoMaterno, :celular, :email";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':codigoUsuario', $codigoUsuario);
        $stmt->bindParam(':nombrePersona', $nombrePersona);
        $stmt->bindParam(':apellidoPaterno', $apellidoPaterno);
        $stmt->bindParam(':apellidoMaterno', $apellidoMaterno);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('PERSONA', 'Actualizar perfil', $codigoUsuario);

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
          $this->auditoria->registrarEvento('USUARIO', 'Habilitar usuario', $codigoUsuario);
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
          $auditoria->registrarEvento('USUARIO', 'Deshabilitar usuario', $codigoUsuario);
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

  // Metodo para verificar contraseña del usuario
  public function verificarContraseñaActual($codigoUsuario, $passwordActual)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $query = "EXEC sp_verificar_contrasena_actual :codigoUsuario, :passwordActual";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':codigoUsuario', $codigoUsuario);
        $stmt->bindParam(':passwordActual', $passwordActual);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        // Verificar el mensaje correcto devuelto desde el procedimiento
        return isset($resultado['Resultado']) && $resultado['Resultado'] === 'Contraseña correcta';
      } else {
        throw new Exception("Error de conexión a la base de datos");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al verificar contraseña actual: " . $e->getMessage());
    }
  }


  // Metodo para cambiar contraseña del usuario
  public function cambiarContraseña($codigoUsuario, $passwordActual, $passwordNuevo, $passwordConfirm)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {

        // Verificar que la contraseña actual sea correcta
        if (!$this->verificarContraseñaActual($codigoUsuario, $passwordActual)) {
          throw new Exception("La contraseña actual es incorrecta.");
        }

        // Si la contraseña actual es correcta, proceder a cambiarla
        $query = "EXEC sp_cambiar_contrasena :codigoUsuario, :passwordActual, :passwordNuevo, :passwordConfirm";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':codigoUsuario', $codigoUsuario);
        $stmt->bindParam(':passwordActual', $passwordActual);
        $stmt->bindParam(':passwordNuevo', $passwordNuevo);
        $stmt->bindParam(':passwordConfirm', $passwordConfirm);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('USUARIO', 'Restablecer contraseña', $codigoUsuario);

        // Obtener el resultado del procedimiento almacenado
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Captura el mensaje retornado
      } else {
        throw new Exception("Error de conexión a la base de datos");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al cambiar contraseña: " . $e->getMessage());
    }
  }

  // Metodo para restablecer contraseña de los usuarios en el mantenedor usuarios
  public function cambiarContraseñaUsuario($codigoUsuario, $passwordNuevo, $passwordConfirm)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $query = "EXEC sp_restablecer_contrasena :codigoUsuario, :passwordNuevo, :passwordConfirm";
        $stmt = $conector->prepare($query);
        // Verificar el tipo de los parámetros
        $stmt->bindParam(':codigoUsuario', $codigoUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':passwordNuevo', $passwordNuevo);
        $stmt->bindParam(':passwordConfirm', $passwordConfirm);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('USUARIO', 'Restablecer contraseña', $codigoUsuario);

        // Obtener el resultado del procedimiento almacenado
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Captura el mensaje retornado
        if ($stmt->rowCount() > 0) {
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexión a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al restablecer contraseña: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar los registros de inicio de sesion en la tabla auditoria
  public function listarEventosLogin()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_auditoria_login
          ORDER BY AUD_fecha DESC, AUD_hora DESC";
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

  // Metodo para consultar eventos totales - auditoria
  public function buscarEventosLogin($usuario, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_eventos_login :usuario, :fechaInicio, :fechaFin";
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
      throw new Exception("Error al consultar eventos de logeo en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para consultar eventos usuarios - auditoria
  public function buscarEventosUsuarios($usuario, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_eventos_usuarios :usuario, :fechaInicio, :fechaFin";
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
      throw new Exception("Error al consultar eventos usuarios en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar eventos de usuarios
  public function listarEventosUsuarios()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_eventos_usuarios
            ORDER BY AUD_fecha DESC, AUD_hora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar eventos de usuarios en la tabla de auditoria: " . $e->getMessage());
    }
  }
}
