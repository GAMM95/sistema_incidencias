<?php
require_once 'app/Model/PersonaModel.php';

class PersonaController
{
  private $personaModel;

  public function __construct()
  {
    $this->personaModel = new PersonaModel();
  }

  // Metodo para registrar personas
  public function registrarPersona()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $dni = $_POST['dni'] ?? null;
      $nombres = $_POST['nombres'] ?? null;
      $apellidoPaterno = $_POST['apellidoPaterno'] ?? null;
      $apellidoMaterno = $_POST['apellidoMaterno'] ?? null;
      $celular = $_POST['celular'] ?? null;
      $email = $_POST['email'] ?? null;

      try {
        // Validar si el DNI ya está registrado
        if ($this->personaModel->validarDniExistente($dni)) {
          echo json_encode([
            'success' => false,
            'message' => 'El DNI ingresado ya esta registrado.'
          ]);
          exit();
        }

        // Registrar la persona
        $insertSuccessId = $this->personaModel->registrarPersona($dni, $nombres, $apellidoPaterno, $apellidoMaterno, $celular,  $email);

        if ($insertSuccessId) {
          echo json_encode([
            'success' => true,
            'message' => 'Persona registrada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar persona.'
          ]);
        }
      } catch (Exception $e) {
        echo json_encode([
          'success' => false,
          'message' => 'Error: ' . $e->getMessage()
        ]);
      }
      exit();
    }
  }

  // Método para editar personas
  public function editarPersona()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigoPersona = $_POST['CodPersona'] ?? null;
      $dni = $_POST['dni'] ?? null;
      $nombres = $_POST['nombres'] ?? null;
      $apellidoPaterno = $_POST['apellidoPaterno'] ?? null;
      $apellidoMaterno = $_POST['apellidoMaterno'] ?? null;
      $email = $_POST['email'] ?? null;
      $celular = $_POST['celular'] ?? null;

      try {

        // // Validar si el DNI ya está registrado
        // if ($this->personaModel->validarDniExistente($dni)) {
        //   echo json_encode([
        //     'success' => false,
        //     'message' => 'El DNI ya esta registrado.'
        //   ]);
        //   exit();
        // }

        $updateSuccess = $this->personaModel->editarPersona($dni, $nombres, $apellidoPaterno, $apellidoMaterno, $celular, $email, $codigoPersona);

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

  // Metodo para filtrar personas por un termino
  public function filtrarPersonas()
  {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      $terminoBusqueda = $_GET['termino'] ?? '';

      try {
        $resultados = $this->personaModel->filtrarPersonas($terminoBusqueda);
        echo json_encode($resultados);

        if ($resultados) {
          echo json_encode([
            'success' =>  true,
            'message' => 'B&uacute;squeda exitosa'
          ]);
        } else {
          echo json_encode([
            'success' =>  false,
            'message' => 'No se realiz&oacute; b&uacute;squeda'
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
        'message' => 'M&eacute;todo no permitido.'
      ]);
    }
  }
}
