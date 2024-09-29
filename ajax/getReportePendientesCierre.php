<?php
require_once '../config/conexion.php';

class ReportePendientesCierre extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReportePendientesCierre()
  {
    $conector = parent::getConexion();
    $sql = "SELECT 
    I.INC_numero,
    INC_numero_formato,
    (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS ESTADO,
    p.PER_nombres + ' ' + PER_apellidoPaterno AS Usuario,
    -- Última modificación (fecha y hora más reciente)
    MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
    MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
    FROM INCIDENCIA I
    INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
    INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
    INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
    LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
    LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
    LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
    LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
    LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
    LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
    INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
    WHERE 
    I.EST_codigo IN (3, 4) -- Solo incluir incidencias con estado 3 o 4
    AND NOT EXISTS (  -- Excluir incidencias que hayan pasado al estado 5 en la tabla CIERRE
    SELECT 1 
    FROM CIERRE C2
    WHERE C2.REC_numero = R.REC_numero
    AND C2.EST_codigo = 5
    )
    GROUP BY 
    I.INC_numero,
    INC_numero_formato,
    I.INC_fecha,
    I.INC_hora,
    I.INC_codigoPatrimonial,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
    C.CIE_numero,
    EC.EST_descripcion,
    E.EST_descripcion,
    p.PER_nombres,
    p.PER_apellidoPaterno
    ORDER BY 
    -- Ordenar por la última modificación primero por fecha y luego por hora
    MAX(COALESCE(C.CIE_fecha, R.REC_fecha, I.INC_fecha)) DESC,
    MAX(COALESCE(C.CIE_hora, R.REC_hora, I.INC_hora)) DESC";
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$reportePendientesCierre = new ReportePendientesCierre();
$reporte = $reportePendientesCierre->getReportePendientesCierre();

header('Content-Type: application/json');
echo json_encode($reporte);