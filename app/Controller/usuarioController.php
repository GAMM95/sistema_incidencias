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
      $usu_nombre = $_POST['username'] ?? null;
      $usu_password = $_POST['password'] ?? null;
      $per_dni = $_POST['dni'] ?? null;
      $per_nombres = $_POST['nombres'] ?? null;
      $per_apellidoPaterno = $_POST['apellidoPaterno'] ?? null;
      $per_apellidoMaterno = $_POST['apellidoMaterno'] ?? null;
      $per_celular = $_POST['celular'] ?? null;
      $per_email = $_POST['email'] ?? null;
      $usu_codigo = $_POST['codigoUsuario'] ?? null;


      // Actualizar usuario
      $this->usuarioModel->editarPerfilUsuario($usu_codigo, $usu_nombre, $usu_password, $per_dni, $per_nombres, $per_apellidoPaterno, $per_apellidoMaterno, $per_celular, $per_email);

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
}
