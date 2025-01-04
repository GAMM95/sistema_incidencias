  <?php
  require_once '../../../../config/conexion.php';

  class ReporteAuditoriaIncidenciasUsuarioFecha extends Conexion
  {
    public function __construct()
    {
      parent::__construct();
    }
    public function getReporteAuditoriaIncidenciasUsuarioFecha($usuario, $fechaInicio, $fechaFin)
    {
      $conector = parent::getConexion();
      $sql = "EXEC sp_consultar_eventos_incidencias :usuario, :fechaInicio, :fechaFin";
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
  $usuario = isset($_GET['usuarioEventoIncidencias']) ?  $_GET['usuarioEventoIncidencias'] : null;
  $fechaInicio = isset($_GET['fechaInicioEventosIncidencias']) ? $_GET['fechaInicioEventosIncidencias'] : null;
  $fechaFin = isset($_GET['fechaFinEventosIncidencias']) ? $_GET['fechaFinEventosIncidencias'] : null;

  $reporteAuditoriaIncidenciasUsuarioFecha = new ReporteAuditoriaIncidenciasUsuarioFecha();
  $reporte = $reporteAuditoriaIncidenciasUsuarioFecha->getReporteAuditoriaIncidenciasUsuarioFecha($usuario, $fechaInicio, $fechaFin);

  header('Content-Type: application/json');
  echo json_encode($reporte);
  exit();
