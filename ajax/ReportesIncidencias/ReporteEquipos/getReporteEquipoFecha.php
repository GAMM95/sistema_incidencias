<?php
require_once '../../../config/conexion.php';

class ReporteEquipoFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteEquipoFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "SELECT 
      I.INC_numero,
      I.INC_numero_formato,
      (CONVERT(VARCHAR(10), INC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), INC_hora, 0), 7), 6, 0, ' ')) AS fechaIncidenciaFormateada,
      I.INC_codigoPatrimonial,
      B.BIE_nombre,
      I.INC_asunto,
      I.INC_documento,
      I.INC_descripcion,
      (CONVERT(VARCHAR(10), R.REC_fecha, 103)) AS fechaRecepcionFormateada,
      PRI.PRI_nombre,
      O.CON_descripcion,
      (CONVERT(VARCHAR(10), C.CIE_fecha, 103)) AS fechaCierreFormateada,
      CAT.CAT_nombre,
      A.ARE_nombre,
      CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
        END AS Estado,
      p.PER_nombres + ' ' + p.PER_apellidoPaterno AS Usuario
    FROM INCIDENCIA I
    INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
    INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
    INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
    LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
    LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
    LEFT JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
    LEFT JOIN MANTENIMIENTO MAN ON MAN.ASI_codigo = ASI.ASI_codigo
    LEFT JOIN CIERRE C ON R.REC_numero = C.MAN_codigo
    LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
    LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
    LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
    LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
    INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
    WHERE INC_fecha BETWEEN :fechaInicio AND :fechaFin
    AND (I.EST_codigo IN (3, 4, 7) OR EC.EST_codigo IN (3, 4, 7))
    AND i.INC_codigoPatrimonial IS NOT NULL
    AND i.INC_codigoPatrimonial <> ''
    ORDER BY 
    SUBSTRING(I.INC_numero_formato, CHARINDEX('-', I.INC_numero_formato) + 1, 4) DESC,
    I.INC_numero_formato DESC";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':fechaFin', $fechaFin);

    try {
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo json_encode(['error' => 'Error al obtener los datos: ' . $e->getMessage()]);
      exit;
    }
    return $resultado;
  }
}

// Validación de las fechas
$fechaInicio = isset($_GET['fechaInicioIncidenciasEquipo']) ? $_GET['fechaInicioIncidenciasEquipo'] : null;
$fechaFin = isset($_GET['fechaFinIncidenciasEquipo']) ? $_GET['fechaFinIncidenciasEquipo'] : null;

if (!$fechaInicio || !$fechaFin) {
  echo json_encode(['error' => 'Las fechas de inicio y fin son requeridas']);
  exit;
}

$reporteEquipoFecha = new ReporteEquipoFecha();
$reporte = $reporteEquipoFecha->getReporteEquipoFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();