<?php
require_once '../../../config/conexion.php';

class ReporteDetalleIncidencia extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteDetalleIncidencia()
  {
    $conector = parent::getConexion();
    $sql = "SELECT 
    I.INC_numero,
    I.INC_numero_formato,
    (CONVERT(VARCHAR(10), I.INC_fecha, 103)) AS fechaIncidenciaFormateada,
    I.INC_codigoPatrimonial,
    CASE
        WHEN I.INC_codigoPatrimonial IS NULL THEN ''
        ELSE B.BIE_nombre
    END AS nombreBien,
    I.INC_asunto,
    I.INC_documento,
    I.INC_descripcion,
    CAT.CAT_nombre,
    A.ARE_nombre,
    CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
    END AS ESTADO,
    p.PER_nombres + ' ' + p.PER_apellidoPaterno + ' ' + p.PER_apellidoMaterno AS Usuario
    FROM INCIDENCIA I
    INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
    INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
    INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
    LEFT JOIN RECEPCION R ON R.INC_numero = I.INC_numero
    LEFT JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
    LEFT JOIN MANTENIMIENTO MAN ON MAN.ASI_codigo = ASI.ASI_codigo
    LEFT JOIN CIERRE C ON C.MAN_codigo = MAN.MAN_codigo
    LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
    LEFT JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
    LEFT JOIN IMPACTO IMP ON IMP.IMP_codigo = R.IMP_codigo
    LEFT JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
    LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
    INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
    LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = LEFT(B.BIE_codigoIdentificador, 8)
    WHERE (I.EST_codigo IN (3, 4, 5) OR C.EST_codigo IN (3, 4, 5))
    ORDER BY I.INC_numero DESC";
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$reporteDetalleIncidencia = new ReporteDetalleIncidencia();
$reporte = $reporteDetalleIncidencia->getReporteDetalleIncidencia();

header('Content-Type: application/json');
echo json_encode($reporte);
exit();