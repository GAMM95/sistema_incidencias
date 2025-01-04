  <?php
  require_once '../../../../config/conexion.php';

  class ReporteAuditoriaPersonasUsuarioFecha extends Conexion
  {
    public function __construct()
    {
      parent::__construct();
    }
    public function getReporteAuditoriaPersonasUsuarioFecha($usuario, $fechaInicio, $fechaFin)
    {
      $conector = parent::getConexion();
      $sql = "EXEC sp_consultar_eventos_personas :usuario, :fechaInicio, :fechaFin";
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
  $usuario = isset($_GET['usuarioEventoPersonas']) ? $_GET['usuarioEventoPersonas'] : null;
  $fechaInicio = isset($_GET['fechaInicioEventosPersonas']) ? $_GET['fechaInicioEventosPersonas'] : null;
  $fechaFin = isset($_GET['fechaFinEventosPersonas']) ? $_GET['fechaFinEventosPersonas'] : null;

  $reporteAuditoriaPersonasUsuarioFecha = new ReporteAuditoriaPersonasUsuarioFecha();
  $reporte = $reporteAuditoriaPersonasUsuarioFecha->getReporteAuditoriaPersonasUsuarioFecha($usuario, $fechaInicio, $fechaFin);

  header('Content-Type: application/json');
  echo json_encode($reporte);
  exit();
