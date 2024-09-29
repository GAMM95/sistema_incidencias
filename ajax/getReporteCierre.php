<?php
require_once '../config/conexion.php';

class ReporteCierre extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteCierre()
  {
    $conector = parent::getConexion();
    $sql = "SELECT
      I.INC_numero,
      I.INC_numero_formato,
      (CONVERT(VARCHAR(10), I.INC_fecha, 103)) AS fechaIncidenciaFormateada,
      I.INC_codigoPatrimonial,
      I.INC_asunto,
      I.INC_documento,
      I.INC_descripcion,
      CAT.CAT_nombre,
      A.ARE_nombre,
      PRI_nombre,
      (CONVERT(VARCHAR(10), C.CIE_fecha, 103)) AS fechaCierreFormateada,
      C.CIE_asunto,
      C.CIE_numero,
      C.CIE_documento,
      C.CIE_diagnostico,
      C.CIE_recomendaciones,
      O.CON_descripcion,
      I.USU_codigo,
      CASE
          WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
          ELSE E.EST_descripcion
      END AS ESTADO,
      -- Nombre del usuario que registró la incidencia
      (UP.PER_nombres + ' ' + UP.PER_apellidoPaterno) AS UsuarioRegistro,
      -- Nombre del usuario que realizó el cierre
      (UC.PER_nombres + ' ' + UC.PER_apellidoPaterno) AS UsuarioCierre
      FROM RECEPCION R
      INNER JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
      RIGHT JOIN INCIDENCIA I ON R.INC_numero = I.INC_numero
      INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
      INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
      INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
      LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
      LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
      INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
      INNER JOIN USUARIO UR ON UR.USU_codigo = I.USU_codigo
      INNER JOIN PERSONA UP ON UP.PER_codigo = UR.PER_codigo
      LEFT JOIN USUARIO UCIE ON UCIE.USU_codigo = C.USU_codigo
      LEFT JOIN PERSONA UC ON UC.PER_codigo = UCIE.PER_codigo";
    $stmt = $conector->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$reporteCierre = new ReporteCierre();
$reporte = $reporteCierre->getReporteCierre();

header('Content-Type: application/json');
echo json_encode($reporte);
