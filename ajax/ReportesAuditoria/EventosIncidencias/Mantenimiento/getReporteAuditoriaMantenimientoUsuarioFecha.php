  <?php
  require_once '../../../../config/conexion.php';

  class ReporteAuditoriaMantenimientoUsuarioFecha extends Conexion
  {
    public function __construct()
    {
      parent::__construct();
    }
    public function getReporteAuditoriaMantenimientoUsuarioFecha($usuario, $fechaInicio, $fechaFin)
    {
      $conector = parent::getConexion();
      $sql = "EXEC sp_consultar_eventos_mantenimiento :usuario, :fechaInicio, :fechaFin";
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
  $usuario = isset($_GET['usuarioEventoMantenimiento']) ?  $_GET['usuarioEventoMantenimiento'] : null;
  $fechaInicio = isset($_GET['fechaInicioEventosMantenimiento']) ? $_GET['fechaInicioEventosMantenimiento'] : null;
  $fechaFin = isset($_GET['fechaFinEventosMantenimiento']) ? $_GET['fechaFinEventosMantenimiento'] : null;

  $reporteAuditoriaMantenimientoUsuarioFecha = new ReporteAuditoriaMantenimientoUsuarioFecha();
  $reporte = $reporteAuditoriaMantenimientoUsuarioFecha->getReporteAuditoriaMantenimientoUsuarioFecha($usuario, $fechaInicio, $fechaFin);

  header('Content-Type: application/json');
  echo json_encode($reporte);
  exit();
