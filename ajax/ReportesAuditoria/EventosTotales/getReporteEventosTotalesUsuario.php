<?php
require_once '../../../config/conexion.php';  

class getReporteEventosTotalesUsuario extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteEventosTotalesUsuario($usuario)
  {
    $conector = parent::getConexion();
    $sql = "SELECT 
                (CONVERT(VARCHAR(10), A.AUD_fecha, 103) + ' - ' + 
                STUFF(RIGHT('0' + CONVERT(VARCHAR(7), A.AUD_hora, 0), 7), 6, 0, ' ')) AS fechaFormateada,
                A.AUD_fecha,  
                A.AUD_hora,   
                A.AUD_tabla,
                A.AUD_usuario,
                R.ROL_nombre,
                U.USU_nombre,
                P.PER_nombres + ' ' + P.PER_apellidoPaterno + ' ' + P.PER_apellidoMaterno AS NombreCompleto,
                A.AUD_operacion,
                AR.ARE_nombre,
                A.AUD_ip,
                A.AUD_nombreEquipo
            FROM AUDITORIA A
            INNER JOIN PERSONA P ON P.PER_codigo = A.AUD_usuario
            INNER JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
            INNER JOIN ROL R ON R.ROL_codigo = U.ROL_codigo
            INNER JOIN AREA AR ON AR.ARE_codigo = U.ARE_codigo
            WHERE A.AUD_usuario = :usuario
            ORDER BY A.AUD_fecha DESC, A.AUD_hora DESC";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_INT);

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
$usuario = isset($_GET['personaEventosTotales']) ?  $_GET['personaEventosTotales'] : null;

$reporteEventosTotalesUsuario = new getReporteEventosTotalesUsuario();
$reporte = $reporteEventosTotalesUsuario->getReporteEventosTotalesUsuario($usuario);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
