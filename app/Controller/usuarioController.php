<?php
require_once 'app/Model/UsuarioModel.php';

class UsuarioController
{
  private $usuarioModel;

  public function __construct()
  {
    $this->usuarioModel = new UsuarioModel();
  }

  // Metodo para registrar usuarios
  public function registrarUsuario()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $username = $_POST['username'] ?? null;
      $password = $_POST['password'] ?? null;
      $persona = $_POST['persona'] ?? null;
      $rol = $_POST['rol'] ?? null;
      $area = $_POST['area'] ?? null;

      try {
        // Validar si la persona tiene un usuario
        if ($this->usuarioModel->validarPersonaConUsuario($persona)) {
          echo json_encode([
            'success' => false,
            'message' => 'La persona ya tiene un usuario registrado.'
          ]);
          exit();
        }

        // Validar si el nombre de usuario ya existe
        if ($this->usuarioModel->validarUsuarioExistente($username)) {
          echo json_encode([
            'success' => false,
            'message' => 'El nombre de usuario ya existe.'
          ]);
          exit();
        }

        // Insertar el usuario
        $insertSuccess = $this->usuarioModel->guardarUsuario($username, $password, $persona, $rol, $area);

        if ($insertSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Usuario registrado.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar usuario.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }

  // Metodo para listar los registros de los usuarios
  public function listarUsuarios()
  {
    try {
      $resultado = $this->usuarioModel->listarUsuarios();
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al listar usuarios: " . $e->getMessage();
    }
  }

  // Metodo para editar usuarios
  public function actualizarUsuario()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos de formulario
      $codigoUsuario = $_POST['CodUsuario'] ?? null;
      $username = $_POST['username'] ?? null;
      $persona = $_POST['codigoPersona'] ?? null;
      $rol = $_POST['rol'] ?? null;
      $area = $_POST['area'] ?? null;

      try {
        // Validar si el usuario ya está registrado, excluyendo el usuario actual
        if (!$this->usuarioModel->validarUsuarioExistente($username, $codigoUsuario)) {
          echo json_encode([
            'success' => false,
            'message' => 'El nombre de usuario ya existe.'
          ]);
          exit();
        }

        // Actualizar usuario
        $updateSuccess = $this->usuarioModel->editarUsuario($codigoUsuario, $username, $persona, $rol, $area);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Datos actualizados.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se realizaron cambios.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'M&eacute;todo no permitido.'
      ]);
    }
  }

  //  Metodo para editar perfil de usuario
  public function editarPerfil()
  {
    try {
      // Obtener los datos del formulario
      $per_nombres = $_POST['nombres'] ?? null;
      $per_apellidoPaterno = $_POST['apellidoPaterno'] ?? null;
      $per_apellidoMaterno = $_POST['apellidoMaterno'] ?? null;
      $per_celular = $_POST['celular'] ?? null;
      $per_email = $_POST['email'] ?? null;
      $usu_codigo = $_POST['codigoUsuario'] ?? null;


      // Actualizar usuario
      $this->usuarioModel->editarPerfilUsuario($usu_codigo, $per_nombres, $per_apellidoPaterno, $per_apellidoMaterno, $per_celular, $per_email);

      echo json_encode([
        'success' => true,
        'message' => 'Perfil actualizado.'
      ]);
    } catch (Exception $e) {
      echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
      ]);
    }
  }

  // Metodo para filtrar usuarios por un termino
  public function filtrarUsuarios()
  {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      $terminoBusqueda = $_GET['termino'] ?? '';

      try {
        $resultados = $this->usuarioModel->filtrarUsuarios($terminoBusqueda);
        echo json_encode($resultados);

        if ($resultados) {
          echo json_encode([
            'success' =>  true,
            'message' => 'B&uacute;squeda exitosa.'
          ]);
        } else {
          echo json_encode([
            'success' =>  false,
            'message' => 'No se realiz&oacute; b&uacute;squeda.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }

  // Controlador para habilitar usuario
  public function habilitarUsuario()
  {
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
      $codigoUsuario = isset($_POST['codigoUsuario']) ? $_POST['codigoUsuario'] : '';

      try {
        $resultados = $this->usuarioModel->habilitarUsuario($codigoUsuario);
        if ($resultados) {
          echo json_encode([
            'success' => true,
            'message' => 'Usuario habilitado.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se pudo habilitar usuario.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }

  // Controlador para deshabilitar usuario
  public function deshabilitarUsuario()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigoUsuario = isset($_POST['codigoUsuario']) ? $_POST['codigoUsuario'] : '';

      try {
        $resultados = $this->usuarioModel->deshabilitarUsuario($codigoUsuario);
        if ($resultados) {
          echo json_encode([
            'success' => true,
            'message' => '&Usuario deshabilitado.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se pudo deshabilitar usuario.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  }

  // Método para cambiar la contraseña del usuario en el formulario perfil
  public function cambiarContraseña()
  {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      // Obtener los datos del formulario
      $codigoUsuario = $_POST['codigoUsuarioModal'] ?? null;
      $passwordActual = $_POST['passwordActual'] ?? null;
      $passwordNuevo = $_POST['passwordNuevo'] ?? null;
      $passwordConfirm = $_POST['passwordConfirm'] ?? null;

      try {
        // Verificar que la contraseña actual sea correcta
        $verificarContraseñaActual = $this->usuarioModel->verificarContraseñaActual($codigoUsuario, $passwordActual);

        if (!$verificarContraseñaActual) {
          echo json_encode([
            'success' => false,
            'message' => 'La contrase&ntilde;a actual es incorrecta.'
          ]);
          exit();
        }

        if ($passwordNuevo !== $passwordConfirm) {
          echo json_encode([
            'success' => false,
            'message' => 'Las contrase&ntilde;as no coinciden.'
          ]);
          exit();
        }

        if ($passwordNuevo === $passwordActual) {
          echo json_encode([
            'success' => false,
            'message' => 'Las contrase&ntilde;as no coinciden.'
          ]);
          exit();
        }

        // Cambiar la contraseña del usuario
        $cambiarContraseña = $this->usuarioModel->cambiarContraseña($codigoUsuario, $passwordActual, $passwordNuevo, $passwordConfirm);

        if ($cambiarContraseña) {
          echo json_encode([
            'success' => true,
            'message' => 'Contrase&ntilde;a cambiada exitosamente.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se pudo cambiar la contrase&ntilde;a.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'M&eacute;todo no permitido.'
      ]);
    }
  }

  // Metodo para restablecer contraseña del usuario
  public function restablecerContraseña()
  {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      // Obtener los datos del formulario
      $codigoUsuario = $_POST['codigoUsuarioModal'] ?? null;
      $passwordNuevo = $_POST['passwordNuevo'] ?? null;
      $passwordConfirm = $_POST['passwordConfirm'] ?? null;

      // Para ver lo que se está recibiendo
      error_log('Datos recibidos:');
      error_log('codigoUsuario: ' . $codigoUsuario);
      error_log('passwordNuevo: ' . $passwordNuevo);
      error_log('passwordConfirm: ' . $passwordConfirm);

      try {
        // Lógica para cambiar la contraseña del usuario en el modelo
        $restablecido = $this->usuarioModel->cambiarContraseñaUsuario($codigoUsuario, $passwordNuevo, $passwordConfirm);

        // Evaluar resultado del modelo y retornar la respuesta
        // if ($restablecido && isset($restablecido['Resultado'])) {

        if ($restablecido) {
          echo json_encode([
            'success' => true,
            'message' => 'Contrase&ntilde;a cambiada exitosamente.'
            // 'message' => $restablecido['Resultado']
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se pudo restablecer la contrase&ntilde;a.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'M&eacute;todo no permitido.'
      ]);
    }
  }

  // Metodo para listar los registros de inicio de sesion en la tabla auditoria
  public function listarEventosLogin()
  {
    $resultadoAuditoriaLogin = $this->usuarioModel->listarEventosLogin();
    return $resultadoAuditoriaLogin;
  }

  // Metodo para consultar los eventos de logeo de los usuarios
  public function consultarEventosLogin($usuario = NULL, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $usuario = isset($_GET['usuarioEventosLogin']) ? (int) $_GET['usuarioEventosLogin'] : null;
      $fechaInicio = isset($_GET['fechaInicioEventosLogin']) ? $_GET['fechaInicioEventosLogin'] : null;
      $fechaFin = isset($_GET['fechaFinEventosLogin']) ? $_GET['fechaFinEventosLogin'] : null;
      // Llamar al método para consultar incidencias por área, código patrimonial y fecha
      $consultaEventosLogin = $this->usuarioModel->buscarEventosLogin($usuario, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $consultaEventosLogin;
    }
  }


  // Metodo para consultar todos los eventos de usuarios para auditoría
  public function consultarEventosUsuarios($usuario = NULL, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $usuario = isset($_GET['usuarioEvento']) ? (int) $_GET['usuarioEvento'] : null;
      $fechaInicio = isset($_GET['fechaInicioEventosUsuarios']) ? $_GET['fechaInicioEventosUsuarios'] : null;
      $fechaFin = isset($_GET['fechaFinEventosUsuarios']) ? $_GET['fechaFinEventosUsuarios'] : null;
      // Llamar al método para consultar incidencias por área, código patrimonial y fecha
      $consultaEventosTotales = $this->usuarioModel->buscarEventosUsuarios($usuario, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $consultaEventosTotales;
    }
  }

  // Metodo para listar eventos de usuarios
  public function listarEventosUsuarios()
  {
    $resultadoAuditoriaEventosUsuarios = $this->usuarioModel->listarEventosUsuarios();
    return $resultadoAuditoriaEventosUsuarios;
  }
}
