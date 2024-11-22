<?php
// Importar el modelo IncidenciaModel.php
require 'app/Model/IncidenciaModel.php';
require 'app/Model/BienModel.php';
$area = $_SESSION['codigoArea'];

class IncidenciaController
{
  private $incidenciaModel;
  private $bienModel;

  public function __construct()
  {
    $this->incidenciaModel = new IncidenciaModel();
    $this->bienModel = new BienModel();
  }

  
   // Método de controlador para registrar una incidencia - ADMINISTRADOR 
  public function registrarIncidenciaAdministrador()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $fecha = $_POST['fecha_incidencia'] ?? null;
      $hora = $_POST['hora'] ?? null;
      $asunto = $_POST['asunto'] ?? null;
      $descripcion = $_POST['descripcion'] ?? null;
      $documento = $_POST['documento'] ?? null;
      $codigoPatrimonial = $_POST['codigoPatrimonial'] ?? null;
      $categoria = $_POST['categoria'] ?? null;
      $area = $_POST['area'] ?? null;
      $usuario = $_POST['usuario'] ?? null;

      try {
        // Validar existencia del bien
        if (!$this->bienModel->validarBienExistente($codigoPatrimonial)) {
          echo json_encode([
            'success' => false,
            'message' => 'Verificar c&oacute;digo patrimonial ingresado.'
          ]);
          exit();
        }

        // Validar que el código patrimonial sea nulo o tenga 12 dígitos
        if (!empty($codigoPatrimonial) && strlen($codigoPatrimonial) !== 12) {
          echo json_encode([
            'success' => false,
            'message' => 'Debe ingresar los 12 d&iacute;gitos del c&oacute;digo patrimonial.'
          ]);
          exit();
        }

        // Llamar al método del modelo para insertar la incidencia en la base de datos
        $insertSuccessId = $this->incidenciaModel->insertarIncidencia($fecha, $hora, $asunto, $descripcion, $documento, $codigoPatrimonial, $categoria, $area, $usuario);

        if ($insertSuccessId) {
          echo json_encode([
            'success' => true,
            'message' => 'Incidencia registrada.',
            'INC_numero' => $insertSuccessId
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar la incidencia.'
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

  // Metodo para eliminar la incidencia 
  public function eliminarIncidencia()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener y validar los parámetros
      $numeroIncidencia = $_POST['numero_incidencia'] ?? null;

      if (empty($numeroIncidencia)) {
        echo json_encode([
          'success' => false,
          'message' => 'Debe seleccionar una incidencia'
        ]);
        exit();
      }

      try {
        // Llamar al modelo para actualizar la incidencia
        $updateSuccess = $this->incidenciaModel->eliminarIncidencia($numeroIncidencia);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Incidencia eliminada.'
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'No se realiz&oacute; ninguna eliminaci&oacute;n.'
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

  /**
   * Método de controlador para registrar una incidencia - USUARIO.
   * 
   * Este método se ejecuta cuando un usuario envía un formulario para registrar una nueva
   * incidencia. Recoge los datos del formulario, los valida, y luego llama al método del modelo
   * correspondiente para insertar la incidencia en la base de datos. Si la inserción es exitosa,
   * redirige al usuario a una página de confirmación con el número de incidencia registrado.
   */
  public function registrarIncidenciaUsuario()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Obtener los datos del formulario
      $fecha = $_POST['fecha_incidencia'] ?? null;
      $hora = $_POST['hora'] ?? null;
      $asunto = $_POST['asunto'] ?? null;
      $descripcion = $_POST['descripcion'] ?? null;
      $documento = $_POST['documento'] ?? null;
      $codigoPatrimonial = $_POST['codigoPatrimonial'] ?? null;
      $categoria = $_POST['categoria'] ?? null;
      $area = $_POST['codigoArea'] ?? null;
      $usuario = $_POST['codigoUsuario'] ?? null;

      try {

        // Validar existencia del bien
        if (!$this->bienModel->validarBienExistente($codigoPatrimonial)) {
          echo json_encode([
            'success' => false,
            'message' => 'Verificar c&oacute;digo patrimonial ingresado'
          ]);
          exit();
        }

        // Validar que el código patrimonial sea nulo o tenga 12 dígitos
        if (!empty($codigoPatrimonial) && strlen($codigoPatrimonial) !== 12) {
          echo json_encode([
            'success' => false,
            'message' => 'Debe ingresar los 12 d&iacute;gitos del c&oacute;digo patrimonial.'
          ]);
          exit();
        }

        // Llamar al método del modelo para insertar la incidencia en la base de datos
        $insertSuccessId = $this->incidenciaModel->insertarIncidencia($fecha, $hora, $asunto, $descripcion, $documento, $codigoPatrimonial, $categoria, $area, $usuario);

        if ($insertSuccessId) {
          echo json_encode([
            'success' => true,
            'message' => 'Incidencia registrada.',
            'INC_numero' => $insertSuccessId
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'message' => 'Error al registrar la incidencia.'
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

  // Metodo para actualizar indicencias - ADMINISTRADOR
  public function actualizarIncidenciaAdministrador()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // Obtener y validar los parámetros
      $numeroIncidencia = $_POST['numero_incidencia'] ?? null;
      $categoria = $_POST['categoria'] ?? null;
      $area = $_POST['area'] ?? null;
      $codigoPatrimonial = $_POST['codigoPatrimonial'] ?? null;
      $asunto = $_POST['asunto'] ?? null;
      $documento = $_POST['documento'] ?? null;
      $descripcion = $_POST['descripcion'] ?? null;

      header('Content-Type: application/json');
      try {

        if (is_null($asunto) || is_null($documento)) {
          // Respuesta en caso de parámetros faltantes
          echo json_encode([
            'success' => true,
            'message' => 'Faltan parámetros requeridos.'
          ]);
          exit();
        }

        // Validar que el código patrimonial sea nulo o tenga 12 dígitos
        if (!empty($codigoPatrimonial) && strlen($codigoPatrimonial) !== 12) {
          echo json_encode([
            'success' => false,
            'message' => 'Debe ingresar los 12 d&iacute;gitos del c&oacute;digo patrimonial.'
          ]);
          exit();
        }

        // Verificar el estado de la incidencia
        $estado = $this->incidenciaModel->obtenerEstadoIncidencia($numeroIncidencia);

        if ($estado === 3) {
          // Estado permitido para actualización
          echo json_encode([
            'success' => false,
            'message' => 'La incidencia no est&aacute; estado ABIERTO y no puede ser actualizada.'
          ]);
          exit();
        }

        // Llamar al modelo para actualizar la incidencia
        $updateSuccess = $this->incidenciaModel->editarIncidenciaAdmin($numeroIncidencia, $categoria, $area, $codigoPatrimonial, $asunto, $documento, $descripcion);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Incidencia actualizada.'
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
    }
  }

  // Metodo para actualizar indicencias - ADMINISTRADOR
  public function actualizarIncidenciaUsuario()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // Obtener y validar los parámetros
      $numeroIncidencia = $_POST['numero_incidencia'] ?? null;
      $categoria = $_POST['categoria'] ?? null;
      $codigoPatrimonial = $_POST['codigoPatrimonial'] ?? null;
      $asunto = $_POST['asunto'] ?? null;
      $documento = $_POST['documento'] ?? null;
      $descripcion = $_POST['descripcion'] ?? null;

      header('Content-Type: application/json');
      try {

        if (is_null($asunto) || is_null($documento)) {
          // Respuesta en caso de parámetros faltantes
          echo json_encode([
            'success' => true,
            'message' => 'Faltan par&aacute;metros requeridos.'
          ]);
          exit();
        }

        // Validar que el código patrimonial sea nulo o tenga 12 dígitos
        if (!empty($codigoPatrimonial) && strlen($codigoPatrimonial) !== 12) {
          echo json_encode([
            'success' => false,
            'message' => 'Debe ingresar los 12 d&iacute;gitos del c&oacute;digo patrimonial.'
          ]);
          exit();
        }

        // Verificar el estado de la incidencia
        $estado = $this->incidenciaModel->obtenerEstadoIncidencia($numeroIncidencia);

        if ($estado === 3) {
          // Estado permitido para actualización
          echo json_encode([
            'success' => false,
            'message' => 'La incidencia no est&aacute; estado ABIERTO y no puede ser actualizada.'
          ]);
          exit();
        }

        // Llamar al modelo para actualizar la incidencia
        $updateSuccess = $this->incidenciaModel->editarIncidenciaUser($numeroIncidencia, $categoria, $codigoPatrimonial, $asunto, $documento, $descripcion);

        if ($updateSuccess) {
          echo json_encode([
            'success' => true,
            'message' => 'Incidencia actualizada.'
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
    }
  }
  /**
   * Método de controlador para consultar incidencias filtradas - ADMINISTRADOR
   * 
   * Este método permite al administrador consultar incidencias basadas en varios filtros opcionales,
   * incluyendo área, estado, fecha de inicio y fecha de fin. Los parámetros se obtienen de la solicitud
   * GET, y luego se utiliza el modelo para realizar la consulta correspondiente en la base de datos.
   * 
   * Retorno:
   * - Array con los resultados de la consulta o `false` si no se encontraron resultados.
   */
  public function consultarIncidenciasPendientesAdministrador($area = NULL, $estado = null, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $area = isset($_GET['area']) ? (int) $_GET['area'] : null;
      $estado = isset($_GET['estado']) ? (int) $_GET['estado'] : null;
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
      error_log("Área: $area, Estado: $estado, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");
      // Llamar al método para consultar incidencias por área, estado y fecha
      $consultaIncidencia = $this->incidenciaModel->buscarIncidenciasPendientesAdministrador($area, $estado, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $consultaIncidencia;
    }
  }

  /**
   * Método de controlador para consultar las incidencias totales - ADMINISTRADOR
   * 
   * Este método permite al administrador consultar todas las incidencias basadas en varios filtros
   * opcionales, incluyendo área, código patrimonial, fecha de inicio y fecha de fin. Los parámetros
   * se obtienen de la solicitud GET, y luego se utiliza el modelo para realizar la consulta
   * correspondiente en la base de datos.
   * 
   * Retorno:
   * - Array con los resultados de la consulta o `false` si no se encontraron resultados.
   */
  public function consultarIncidenciasTotales($area = NULL, $codigoPatrimonial = null, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $area = isset($_GET['area']) ? (int) $_GET['area'] : null;
      $codigoPatrimonial = isset($_GET['codigoPatrimonial']) ? (int) $_GET['codigoPatrimonial'] : null;
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
      error_log("Área: $area, Codigo patrimonial: $codigoPatrimonial, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");
      // Llamar al método para consultar incidencias por área, código patrimonial y fecha
      $consultaIncidencia = $this->incidenciaModel->buscarIncidenciaTotales($area, $codigoPatrimonial, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $consultaIncidencia;
    }
  }

  // Metodo para filtrar incidencias totales por fecha ingresada
  public function filtrarIncidenciasTotalesFecha($fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
      error_log("Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");
      // Llamar al método para consultar incidencias por fecha
      $consultaIncidencia = $this->incidenciaModel->buscarIncidenciaTotalesFecha($fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $consultaIncidencia;
    }
  }


  /**
   * Método de controlador para consultar incidencias filtradas - USUARIO
   * 
   * Este método permite a los usuarios consultar incidencias basadas en varios filtros opcionales,
   * incluyendo área, código patrimonial, estado, fecha de inicio y fecha de fin. Los parámetros se obtienen
   * de la solicitud GET, y luego se utiliza el modelo para realizar la consulta correspondiente en la base de datos.
   * 
   * Retorno:
   * - Array con los resultados de la consulta o `false` si no se encontraron resultados.
   */
  public function consultarIncidenciaUsuario($area = NULL, $estado = null, $fechaInicio = null, $fechaFin = null)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      // Obtener los valores de los parámetros GET o asignar null si no existen
      $area = isset($_GET['codigoArea']) ? (int) $_GET['codigoArea'] : null;
      $codigoPatrimonial = isset($_GET['codigoPatrimonial']) ? $_GET['codigoPatrimonial'] : null;
      $estado = isset($_GET['estado']) ? (int) $_GET['estado'] : null;
      $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
      $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
      error_log("Área: $area, Estado: $estado, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");
      // Llamar al método para consultar incidencias por área, estado y fecha
      $consultaIncidencia = $this->incidenciaModel->buscarIncidenciaUsuario($area,  $codigoPatrimonial, $estado, $fechaInicio, $fechaFin);
      // Retornar el resultado de la consulta
      return $consultaIncidencia;
    }
  }

  // Metodo para listar incidencias totales para reporte
  public function listarIncidenciasTotales()
  {
    $resultado = $this->incidenciaModel->listarIncidenciasTotales();
    return $resultado;
  }

  // Metodo para listar el total de incidencias para consulta de administrador
  public function listarIncidenciasTotalesAdministrador()
  {
    $resultado = $this->incidenciaModel->listarIncidenciasTotalesAdministrador();
    return $resultado;
  }

  // Metodo para listar incidencias pendientes para consulta de administrador
  public function listarIncidenciasPendientesAdministrador()
  {
    $resultado = $this->incidenciaModel->listarIncidenciasPendientesAdministrador();
    return $resultado;
  }

  // Metodo para listar incidencias totales por cada area para los usuarios
  public function listarIncidenciasTotalesPorArea($area)
  {
    try {
      // Llamada al modelo para obtener las incidencias
      $resultado = $this->incidenciaModel->listarIncidenciasUsuario($area);
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al listar incidencias: " . $e->getMessage();
    }
  }

  // Metodo para contar incidencias registradas en el formulario de administrador / soporte
  public function contarIncidenciasRegistradas()
  {
    try {
      // Llamada al modelo para obtener las incidencias
      $resultado = $this->incidenciaModel->contarIncidenciasAdministrador();
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al contar incidencias registradas: " . $e->getMessage();
    }
  }

  // Metodo para listar incidencias registradas en el formulario de administrador / soporte
  public function listarIncidenciasRegistradas()
  {
    try {
      // Llamada al modelo para obtener las incidencias
      $resultado = $this->incidenciaModel->listarIncidenciasRegistroAdmin();
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al listar incidencias registradas: " . $e->getMessage();
    }
  }

  // Metodo para listar las incidencias registradas
  public function listarIncidenciasRegistradasPaginas($start = null, $limit = null)
  {
    try {
      $resultado = $this->incidenciaModel->listarIncidenciasRecepcion($start, $limit);
      return $resultado;
    } catch (Exception $e) {
      // Manejo de errores
      echo "Error al listar incidencias registradas para paginacion: " . $e->getMessage();
    }
  }
}
