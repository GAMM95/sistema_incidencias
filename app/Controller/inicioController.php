<?php
class InicioController extends Conexion
{
  private $incidenciasModel;
  private $recepcionModel;
  private $cierreModel;
  private $usuarioModel;
  private $areaModel;
  private $categoriaModel;
  private $asignacionModel;

  public function __construct()
  {
    $this->incidenciasModel = new IncidenciaModel();
    $this->recepcionModel = new RecepcionModel();
    $this->cierreModel = new CierreModel();
    $this->usuarioModel = new UsuarioModel();
    $this->areaModel = new AreaModel();
    $this->categoriaModel = new CategoriaModel();
    $this->asignacionModel = new AsignacionModel();
  }

  public function mostrarCantidadesAdministrador()
  {
    try {
      $cantidadIncidenciasMesActualAdmin = $this->incidenciasModel->contarIncidenciasUltimoMesAdministrador();
      $cantidadPendientessMesActualAdmin = $this->incidenciasModel->contarPendientesUltimoMesAdministrador();
      $cantidadRecepcionesMesActualAdmin = $this->recepcionModel->contarRecepcionesUltimoMesAdministrador();
      $cantidadRecepcionesEnEsperaMesActualAdmin = $this->asignacionModel->contarRecepcionesEnEsperaUltimoMesAdministrador();
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
        'recepciones_en_espera_mes_actual' => $cantidadRecepcionesEnEsperaMesActualAdmin,
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

}
