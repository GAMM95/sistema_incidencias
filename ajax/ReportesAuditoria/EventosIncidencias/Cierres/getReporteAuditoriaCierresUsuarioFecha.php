  <?php
  require_once '../../../../config/conexion.php';

  class ReporteAuditoriaCierresUsuarioFecha extends Conexion
  {
    public function __construct()
    {
      parent::__construct();
    }
    public function getReporteAuditoriaCierresUsuarioFecha($usuario, $fechaInicio, $fechaFin)
    {
      $conector = parent::getConexion();
      $sql = "EXEC sp_consultar_eventos_cierres :usuario, :fechaInicio, :fechaFin";
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
  $usuario = isset($_GET['usuarioEventoCierres']) ? $_GET['usuarioEventoCierres'] : null;
  $fechaInicio = isset($_GET['fechaInicioEventosCierres']) ? $_GET['fechaInicioEventosCierres'] : null;
  $fechaFin = isset($_GET['fechaFinEventosCierres']) ? $_GET['fechaFinEventosCierres'] : null;

  $reporteAuditoriaCierresUsuarioFecha = new ReporteAuditoriaCierresUsuarioFecha();
  $reporte = $reporteAuditoriaCierresUsuarioFecha->getReporteAuditoriaCierresUsuarioFecha($usuario, $fechaInicio, $fechaFin);

  header('Content-Type: application/json');
  echo json_encode($reporte);
  exit();
