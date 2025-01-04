  <?php
  require_once '../../../../config/conexion.php';

  class ReporteAuditoriaUsuarioUserFecha extends Conexion
  {
    public function __construct()
    {
      parent::__construct();
    }
    public function getReporteAuditoriaUsuarioUserFecha($usuario, $fechaInicio, $fechaFin)
    {
      $conector = parent::getConexion();
      $sql = "EXEC sp_consultar_eventos_usuarios :usuario, :fechaInicio, :fechaFin";
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
  $usuario = isset($_GET['usuarioEventoUsuarios']) ?  $_GET['usuarioEventoUsuarios'] : null;
  $fechaInicio = isset($_GET['fechaInicioEventosUsuarios']) ? $_GET['fechaInicioEventosUsuarios'] : null;
  $fechaFin = isset($_GET['fechaFinEventosUsuarios']) ? $_GET['fechaFinEventosUsuarios'] : null;

  $reporteAuditoriaUsuarioUserFecha = new ReporteAuditoriaUsuarioUserFecha();
  $reporte = $reporteAuditoriaUsuarioUserFecha->getReporteAuditoriaUsuarioUserFecha($usuario, $fechaInicio, $fechaFin);

  header('Content-Type: application/json');
  echo json_encode($reporte);
  exit();
