<?php
require_once '../config/conexion.php';

class ReporteEventosTotalesFiltro extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteEventosTotalesFiltro($usuario, $fechaInicio, $fechaFin)
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
            WHERE A.AUD_fecha BETWEEN :fechaInicio AND :fechaFin 
            AND A.AUD_usuario = :usuario
            ORDER BY A.AUD_fecha DESC, A.AUD_hora DESC";

    // $sql = "SELECT 
    //     (CONVERT(VARCHAR(10), A.AUD_fecha, 103) + ' - ' + 
    //     STUFF(RIGHT('0' + CONVERT(VARCHAR(7), A.AUD_hora, 0), 7), 6, 0, ' ')) AS fechaFormateada,
    //     A.AUD_fecha,  
    //     A.AUD_hora,   
    //     A.AUD_tabla,
    //     A.AUD_usuario,
    //     R.ROL_nombre,
    //     U.USU_nombre,
    //     P.PER_nombres + ' ' + P.PER_apellidoPaterno + ' ' + P.PER_apellidoMaterno AS NombreCompleto,
    //     A.AUD_operacion,
    //     AR.ARE_nombre,
    //     A.AUD_ip,
    //     A.AUD_nombreEquipo
    // FROM AUDITORIA A
    // INNER JOIN PERSONA P ON P.PER_codigo = A.AUD_usuario
    // INNER JOIN USUARIO U ON U.USU_codigo = A.AUD_usuario
    // INNER JOIN ROL R ON R.ROL_codigo = U.ROL_codigo
    // INNER JOIN AREA AR ON AR.ARE_codigo = U.ARE_codigo
    // WHERE 
    //     (
    //         (A.AUD_fecha BETWEEN :fechaInicio AND :fechaFin) -- Filtrar por fechas solo si ambas están presentes
    //         OR :fechaInicio IS NULL OR :fechaFin IS NULL -- Permitir fechas nulas sin filtrar por fechas
    //     )
    //     AND 
    //     (
    //         (A.AUD_usuario = :usuario) -- Filtrar por usuario solo si se proporciona
    //         OR :usuario IS NULL -- Permitir usuario nulo sin filtrar por usuario
    //     )
    // ORDER BY A.AUD_fecha DESC, A.AUD_hora DESC";


    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_INT);
    $stmt->bindParam(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
    $stmt->bindParam(':fechaFin', $fechaFin, PDO::PARAM_STR);

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
$fechaInicio = isset($_GET['fechaInicioEventosTotales']) ? $_GET['fechaInicioEventosTotales'] : null;
$fechaFin = isset($_GET['fechaFinEventosTotales']) ? $_GET['fechaFinEventosTotales'] : null;

$reporteEventosTotalesFiltro = new ReporteEventosTotalesFiltro();
$reporte = $reporteEventosTotalesFiltro->getReporteEventosTotalesFiltro($usuario, $fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();
