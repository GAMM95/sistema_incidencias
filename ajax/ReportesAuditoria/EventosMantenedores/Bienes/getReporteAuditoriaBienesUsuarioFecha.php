  <?php
  require_once '../../../../config/conexion.php';

  class ReporteAuditoriaBienesUsuarioFecha extends Conexion
  {
    public function __construct()
    {
      parent::__construct();
    }
    public function getReporteAuditoriaBienesUsuarioFecha($usuario, $fechaInicio, $fechaFin)
    {
      $conector = parent::getConexion();
      $sql = "EXEC sp_consultar_eventos_bienes :usuario, :fechaInicio, :fechaFin";
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
  $usuario = isset($_GET['usuarioEventoBienes']) ? $_GET['usuarioEventoBienes'] : null;
  $fechaInicio = isset($_GET['fechaInicioEventosBienes']) ? $_GET['fechaInicioEventosBienes'] : null;
  $fechaFin = isset($_GET['fechaFinEventosBienes']) ? $_GET['fechaFinEventosBienes'] : null;

  $reporteAuditoriaBienesUsuarioFecha = new ReporteAuditoriaBienesUsuarioFecha();
  $reporte = $reporteAuditoriaBienesUsuarioFecha->getReporteAuditoriaBienesUsuarioFecha($usuario, $fechaInicio, $fechaFin);

  header('Content-Type: application/json');
  echo json_encode($reporte);
  exit();
