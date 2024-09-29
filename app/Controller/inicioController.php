<?php
class InicioController extends Conexion
{
  private $incidenciasModel;
  private $recepcionModel;
  private $cierreModel;
  private $usuarioModel;
  private $areaModel;
  private $categoriaModel;

  public function __construct()
  {
    $this->incidenciasModel = new IncidenciaModel();
    $this->recepcionModel = new RecepcionModel();
    $this->cierreModel = new CierreModel();
    $this->usuarioModel = new UsuarioModel();
    $this->areaModel = new AreaModel();
    $this->categoriaModel = new CategoriaModel();
  }

  public function mostrarCantidadesAdministrador()
  {
    try {
      $cantidadIncidenciasMesActualAdmin = $this->incidenciasModel->contarIncidenciasUltimoMesAdministrador();
      $cantidadPendientessMesActualAdmin = $this->incidenciasModel->contarPendientesUltimoMesAdministrador();
      $cantidadRecepcionesMesActualAdmin = $this->recepcionModel->contarRecepcionesUltimoMesAdministrador();
      $cantidadCierresMesActualAdmin = $this->cierreModel->contarCierresUltimoMesAdministrador();
      $cantidadAreas = $this->areaModel->contarAreas();
      $cantidadUsuarios = $this->usuarioModel->contarUsuarios();
      $cantidadIncidencias = $this->incidenciasModel->contarIncidencias();
      $cantidadCategorias = $this->categoriaModel->contarCategorias();
      $areaConMasIncidencias = $this->incidenciasModel->areasConMasIncidencias();

      return [
        'incidencias_mes_actual' => $cantidadIncidenciasMesActualAdmin,
        'pendientes_mes_actual' => $cantidadPendientessMesActualAdmin,
        'recepciones_mes_actual' => $cantidadRecepcionesMesActualAdmin,
        'cierres_mes_actual' => $cantidadCierresMesActualAdmin,
        'usuarios_total' => $cantidadUsuarios,
        'cantidadAreas' => $cantidadAreas,
        'cantidadIncidencias' => $cantidadIncidencias,
        'cantidadCategorias' => $cantidadCategorias,
        'areaMasIncidencia' => $areaConMasIncidencias,
      ];
    } catch (Exception $e) {
      throw new Exception('Error al obtener las cantidades: ' . $e->getMessage());
    }
  }

  public function mostrarCantidadesUsuario($area)
  {
    try {
      $cantidadIncidenciasMesActual = $this->incidenciasModel->contarIncidenciasUltimoMesUsuario($area);
      $cantidadPendientesMesActual = $this->incidenciasModel->contarPendientesUltimoMesUsuario($area);
      $cantidadRecepcionesMesActual = $this->recepcionModel->contarRecepcionesUltimoMesUsuario($area);
      $cantidadCierresMesActual = $this->cierreModel->contarCierresUltimoMesUsuario($area);

      return [
        'incidencias_mes_actual' => $cantidadIncidenciasMesActual,
        'pendientes_mes_actual' => $cantidadPendientesMesActual,
        'recepciones_mes_actual' => $cantidadRecepcionesMesActual,
        'cierres_mes_actual' => $cantidadCierresMesActual
      ];
    } catch (Exception $e) {
      throw new Exception('Error al obtener las cantidades: ' . $e->getMessage());
    }
  }

  // public function listarIncidenciasFecha()
  // {
  //   try {
  //     $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

  //     // Obtener las incidencias para la fecha especificada
  //     $this->incidenciasModel->listarIncidenciasAdminFecha($fecha);

  //     // Renderizar la vista con las incidencias
  //     require_once './View/incidencias.php';
  //   } catch (Exception $e) {
  //     throw new Exception('Error al listar incidencias por fecha: ' . $e->getMessage());
  //   }
  // }
}
