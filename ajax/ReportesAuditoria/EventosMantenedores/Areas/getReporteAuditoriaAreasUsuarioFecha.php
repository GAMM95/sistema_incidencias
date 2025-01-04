  <?php
  require_once '../../../../config/conexion.php';

  class ReporteAuditoriaAreasUsuarioFecha extends Conexion
  {
    public function __construct()
    {
      parent::__construct();
    }
    public function getReporteAuditoriaAreasUsuarioFecha($usuario, $fechaInicio, $fechaFin)
    {
      $conector = parent::getConexion();
      $sql = "EXEC sp_consultar_eventos_areas :usuario, :fechaInicio, :fechaFin";
      $stmt = $conector->prepare($sql);
      $stmt->bindParam(':usuario', $usuario);
      $stmt->bindParam(':fechaInicio', $fechaInicio);
      $stmt->bindParam(':fechaFin', $fechaFin);
      try {
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
        echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
        exit;
      }
      return $resultado;
    }
  }

  // Validación de los parámetros
  $usuario = isset($_GET['usuarioEventoAreas']) ? $_GET['usuarioEventoAreas'] : null;
  $fechaInicio = isset($_GET['fechaInicioEventosAreas']) ? $_GET['fechaInicioEventosAreas'] : null;
  $fechaFin = isset($_GET['fechaFinEventosAreas']) ? $_GET['fechaFinEventosAreas'] : null;

  $reporteAuditoriaAreasUsuarioFecha = new ReporteAuditoriaAreasUsuarioFecha();
  $reporte = $reporteAuditoriaAreasUsuarioFecha->getReporteAuditoriaAreasUsuarioFecha($usuario, $fechaInicio, $fechaFin);

  header('Content-Type: application/json');
  echo json_encode($reporte);
  exit();
