<?php

require_once 'app/Model/CategoriaModel.php';

class CategoriaController
{
  private $categoriaModel;

  public function __construct()
  {
    $this->categoriaModel = new categoriaModel();
  }

  // Metodo para registrar la categoria
  public function registrarCategoria()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $nombreCategoria = $_POST['nombreCategoria'] ?? null;

      if ($nombreCategoria === null || trim($nombreCategoria) === '') {
        echo json_encode([
          'success' => false,
          'message' => 'Ingrese nueva categor&iacute;a.'
        ]);
        exit();
      }

      try {
        $insertSuccessId = $this->categoriaModel->insertarCategoria($nombreCategoria);
        if ($insertSuccessId) {
          echo json_encode([
            'success' => true,
            'message' => 'Categor&iacute;a registrada.',
            'CAT_codigo' => $insertSuccessId
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar la categor&iacute;a.',
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

  // MEtodo para editar categoria
  public function actualizarCategoria()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigoCategoria = $_POST['codCategoria'] ?? null;
      $nombreCategoria = $_POST['nombreCategoria'] ?? null;

      if (empty($codigoCategoria) || empty($nombreCategoria)) {
        echo json_encode([
          'success' => false,
          'message' => 'Ingrese campos requeridos (*).'
        ]);
        exit();
      }

      try {
        // Llamar al metodo para editar la categoria
        $updateSuccess = $this->categoriaModel->editarCategoria($nombreCategoria, $codigoCategoria);
        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Categor&iacute;a actualizada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se realiz&oacute; ninguna actualizaci&oacute;n.'
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

  // Metodo para listar categorias
  public function listarCategorias()
  {
    try {
      $categorias = $this->categoriaModel->listarCategorias();
      return $categorias;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }

  public function filtrarCategorias()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $termino = $_POST['terminoBusqueda'] ?? null;

      if ($termino === null || trim($termino) === '') {
        echo "Error: El término de búsqueda no puede estar vacío.";
        return;
      }

      try {
        $categorias = $this->categoriaModel->filtrarBusqueda($termino);
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    } else {
      echo "Error: Método no permitido";
    }
  }

  // Controlador para habilitar una categoria
  public function habilitarCategoria()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigoCategoria = isset($_POST['codCategoria']) ? $_POST['codCategoria'] : '';

      try {
        $resultados = $this->categoriaModel->habilitarCategoria($codigoCategoria);
        if ($resultados) {
          echo json_encode([
            'success' => true,
            'message' => 'Categor&iacute;a habilitada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se pudo habilitar la categor&iacute;a.'
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

  // Controlador para deshabilitar una categoria
  public function deshabilitarCategoria()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $codigoCategoria = isset($_POST['codCategoria']) ? $_POST['codCategoria'] : '';

      try {
        $resultados = $this->categoriaModel->deshabilitarCategoria($codigoCategoria);
        if ($resultados) {
          echo json_encode([
            'success' => true,
            'message' => 'Categor&iacute;a deshabilitada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se pudo deshabilitar la categor&iacute;a.'
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

  // Metodo para listar eventos de categorias
  public function listarEventosCategorias()
  {
    $resultadoAuditoriaEventosCategorias = $this->categoriaModel->listarEventosCategorias();
    return $resultadoAuditoriaEventosCategorias;
  }

  // Metodo para consultar todos los eventos de categorias para auditoría
  public function consultarEventosCategorias($usuario = NULL, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $usuario = isset($_GET['usuarioEventoCategoria']) ? (int) $_GET['usuarioEventoCategoria'] : null;
      $fechaInicio = isset($_GET['fechaInicioEventosCategorias']) ? $_GET['fechaInicioEventosCategorias'] : null;
      $fechaFin = isset($_GET['fechaFinEventosCategorias']) ? $_GET['fechaFinEventosCategorias'] : null;
      // Llamar al método para consultar incidencias por área, código patrimonial y fecha
      $consultaEventosTotales = $this->categoriaModel->buscarEventosCategorias($usuario, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $consultaEventosTotales;
    }
  }
}
