<?php
require_once '../../../../config/conexion.php';

class ReporteIncidenciasCerradasFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteIncidenciasCerradasFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "SELECT
        I.INC_numero,
        I.INC_numero_formato,
        (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
        A.ARE_nombre,
        CAT.CAT_nombre,
        I.INC_asunto,
        I.INC_documento,
        I.INC_codigoPatrimonial,
        B.BIE_nombre,
        PRI.PRI_nombre,
        (CONVERT(VARCHAR(10), C.CIE_fecha, 103)) AS fechaCierreFormateada,
        C.CIE_numero,
        C.CIE_diagnostico, 
        C.CIE_recomendaciones,
        C.CIE_documento,
        O.CON_descripcion,
        U.USU_nombre,
        S.SOL_descripcion,
        CASE
            WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
            ELSE E.EST_descripcion
        END AS Estado,
        (P.PER_nombres + ' ' + P.PER_apellidoPaterno) AS Usuario,
        -- Última modificación (fecha y hora más reciente)
        MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
        MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
    FROM CIERRE C
    LEFT JOIN MANTENIMIENTO MAN ON MAN.MAN_codigo = C.MAN_codigo
    LEFT JOIN ASIGNACION ASI ON ASI.ASI_codigo = MAN.ASI_codigo
    LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
    INNER JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
    RIGHT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
    LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
    INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
    INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
    INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
    LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
    INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
    INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
    INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
    LEFT JOIN SOLUCION S ON S.SOL_codigo = C.SOL_codigo
    WHERE (MAN.EST_codigo = 7 OR C.EST_codigo = 7)
    AND CIE_fecha BETWEEN :fechaInicio AND :fechaFin
    GROUP BY
        I.INC_numero,
        I.INC_numero_formato,
        I.INC_fecha,
        I.INC_hora,
        A.ARE_nombre,
        CAT.CAT_nombre,
        I.INC_asunto,
        I.INC_documento,
        I.INC_codigoPatrimonial,
        B.BIE_nombre,
        PRI.PRI_nombre,
        C.CIE_fecha,
        C.CIE_hora,
        C.CIE_numero,
        C.CIE_diagnostico,
        C.CIE_recomendaciones,
        C.CIE_documento,
        O.CON_descripcion,
        U.USU_nombre,
        S.SOL_descripcion,
        P.PER_nombres,
        P.PER_apellidoPaterno,
        CASE
            WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
            ELSE E.EST_descripcion
        END
    ORDER BY I.INC_numero_formato ASC";
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
$fechaInicio = isset($_GET['fechaInicioIncidenciasCerradas']) ? $_GET['fechaInicioIncidenciasCerradas'] : null;
$fechaFin = isset($_GET['fechaFinIncidenciasCerradas']) ? $_GET['fechaFinIncidenciasCerradas'] : null;

if (!$fechaInicio || !$fechaFin) {
  echo json_encode(['error' => 'Las fechas de inicio y fin son requeridas']);
  exit;
}

$reporteIncidenciasCerradasFecha = new ReporteIncidenciasCerradasFecha();
$reporte = $reporteIncidenciasCerradasFecha->getReporteIncidenciasCerradasFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
