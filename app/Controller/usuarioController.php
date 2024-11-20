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

  // Metodo para editar usuarios
  public function actualizarUsuario()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos de formulario
      $codigoUsuario = $_POST['CodUsuario'] ?? null;
      $username = $_POST['username'] ?? null;
      $password = $_POST['password'] ?? null;
      $persona = $_POST['codigoPersona'] ?? null;
      $rol = $_POST['rol'] ?? null;
      $area = $_POST['area'] ?? null;

      try {
        // Validar si el usuario ya está registrado
        if (!$this->usuarioModel->validarUsuarioExistente($username)) {
          echo json_encode([
            'success' => true,
            'message' => 'El nombre de usuario ya existe.'
          ]);
          exit();
        }

        // Actualizar usuario
        $updateSuccess = $this->usuarioModel->editarUsuario($codigoUsuario, $username, $password, $persona, $rol, $area);

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
        'message' => 'Método no permitido.'
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
    try {
      // Obtener los datos del formulario
      $usu_codigo = $_POST['codigoUsuarioModal'] ?? null;
      $passwordActual = $_POST['passwordActual'] ?? null;
      $passwordNuevo = $_POST['passwordNuevo'] ?? null;
      $passwordConfirm = $_POST['passwordConfirm'] ?? null;

      // Validar que todos los campos estén completos
      if (empty($usu_codigo) || empty($passwordActual) || empty($passwordNuevo) || empty($passwordConfirm)) {
        echo json_encode([
          'success' => false,
          'message' => 'Debe completar todos los campos para cambiar la contraseña.'
        ]);
        exit();
      }

      // Validar que la nueva contraseña y la confirmación coincidan
      if ($passwordNuevo !== $passwordConfirm) {
        echo json_encode([
          'success' => false,
          'message' => 'La nueva contraseña y la confirmación no coinciden.'
        ]);
        exit();
      }

      // Verificar que la contraseña actual sea correcta
      $verificarContraseñaActual = $this->usuarioModel->verificarContraseñaActual($usu_codigo, $passwordActual);

      if (!$verificarContraseñaActual) {
        echo json_encode([
          'success' => false,
          'message' => 'La contraseña actual es incorrecta.'
        ]);
        exit();
      }

      // Cambiar la contraseña del usuario
      $cambiarContraseña = $this->usuarioModel->cambiarContraseña($usu_codigo, $passwordActual, $passwordNuevo, $passwordConfirm);

      if ($cambiarContraseña) {
        echo json_encode([
          'success' => true,
          'message' => 'Contraseña cambiada exitosamente.'
        ]);
      } else {
        echo json_encode([
          'success' => false,
          'message' => 'No se pudo cambiar la contraseña.'
        ]);
      }
    } catch (Exception $e) {
      echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
      ]);
    }
  }

  // Metodo para restablecer la contraseña del usuario en el mantenedor
  public function restablecerContraseña()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $usu_codigo = $_POST['codigoUsuarioModal'] ?? null;
      $passwordNuevo = $_POST['passwordNuevo'] ?? null;
      $passwordConfirm = $_POST['passwordConfirm'] ?? null;

      // Validar que todos los campos estén completos
      if (empty($usu_codigo) || empty($passwordNuevo) || empty($passwordConfirm)) {
        echo json_encode([
          'success' => false,
          'message' => 'Debe completar todos los campos para restablecer la contrase&ntilde;a.'
        ]);
        exit();
      }

      // Validar que la nueva contraseña y la confirmación coincidan
      if ($passwordNuevo !== $passwordConfirm) {
        echo json_encode([
          'success' => false,
          'message' => 'La nueva contrase&ntilde;a y la confirmación no coinciden.'
        ]);
        exit();
      }

      // Cambiar la contraseña del usuario
      $restablecerContraseña = $this->usuarioModel->restablecerContraseña($usu_codigo, $passwordNuevo, $passwordConfirm);

      if ($restablecerContraseña) {
        echo json_encode([
          'success' => true,
          'message' => 'Contrase&ntilde;a restablecida exitosamente.'
        ]);
      } else {
        echo json_encode([
          'success' => false,
          'message' => 'No se pudo restablecer la contrase&ntilde;a.'
        ]);
      }
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
      ]);
    }
  } 

  // // Método para cambiar la contraseña del usuario
  // public function cambiarContraseña()
  // {
  //   try {
  //     // Obtener los datos del formulario
  //     $usu_codigo = $_POST['codigoUsuario'] ?? null;
  //     $passwordActual = $_POST['passwordActual'] ?? null;
  //     $passwordNuevo = $_POST['passwordNuevo'] ?? null;
  //     $passwordConfirm = $_POST['passwordConfirm'] ?? null;

  //     // Validar que todos los campos estén completos
  //     if (empty($usu_codigo) || empty($passwordActual) || empty($passwordNuevo) || empty($passwordConfirm)) {
  //       echo json_encode([
  //         'success' => false,
  //         'message' => 'Debe completar todos los campos para cambiar la contraseña.'
  //       ]);
  //       return;  // En lugar de exit(), se retorna el flujo
  //     }

  //     // Validar que la nueva contraseña y la confirmación coincidan
  //     if ($passwordNuevo !== $passwordConfirm) {
  //       echo json_encode([
  //         'success' => false,
  //         'message' => 'La nueva contraseña y la confirmación no coinciden.'
  //       ]);
  //       return;  // En lugar de exit(), se retorna el flujo
  //     }

  //     // Verificar que la contraseña actual sea correcta
  //     $verificarContraseñaActual = $this->usuarioModel->verificarContraseñaActual($usu_codigo, $passwordActual);

  //     if (!$verificarContraseñaActual) {
  //       echo json_encode([
  //         'success' => false,
  //         'message' => 'La contraseña actual es incorrecta.'
  //       ]);
  //       return;  // En lugar de exit(), se retorna el flujo
  //     }

  //     // Cambiar la contraseña del usuario
  //     $cambiarContraseña = $this->usuarioModel->cambiarContraseña($usu_codigo, $passwordNuevo);

  //     if ($cambiarContraseña) {
  //       echo json_encode([
  //         'success' => true,
  //         'message' => 'Contraseña cambiada exitosamente.'
  //       ]);
  //     } else {
  //       echo json_encode([
  //         'success' => false,
  //         'message' => 'No se pudo cambiar la contraseña.'
  //       ]);
  //     }
  //   } catch (Exception $e) {
  //     echo json_encode([
  //       'success' => false,
  //       'message' => 'Error: ' . $e->getMessage()
  //     ]);
  //   }
  // }
}
