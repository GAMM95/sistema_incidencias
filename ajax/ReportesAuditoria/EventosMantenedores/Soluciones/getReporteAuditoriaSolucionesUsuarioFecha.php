  <?php
  require_once '../../../../config/conexion.php';

  class ReporteAuditoriaSolucionesUsuarioFecha extends Conexion
  {
    public function __construct()
    {
      parent::__construct();
    }
    public function getReporteAuditoriaSolucionesUsuarioFecha($usuario, $fechaInicio, $fechaFin)
    {
      $conector = parent::getConexion();
      $sql = "EXEC sp_consultar_eventos_soluciones :usuario, :fechaInicio, :fechaFin";
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
  $usuario = isset($_GET['usuarioEventoSoluciones']) ? $_GET['usuarioEventoSoluciones'] : null;
  $fechaInicio = isset($_GET['fechaInicioEventosSoluciones']) ? $_GET['fechaInicioEventosSoluciones'] : null;
  $fechaFin = isset($_GET['fechaFinEventosSoluciones']) ? $_GET['fechaFinEventosSoluciones'] : null;

  $reporteAuditoriaSolucionesUsuarioFecha = new ReporteAuditoriaSolucionesUsuarioFecha();
  $reporte = $reporteAuditoriaSolucionesUsuarioFecha->getReporteAuditoriaSolucionesUsuarioFecha($usuario, $fechaInicio, $fechaFin);

  header('Content-Type: application/json');
  echo json_encode($reporte);
  exit();
