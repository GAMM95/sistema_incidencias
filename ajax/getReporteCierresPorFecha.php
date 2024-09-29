<?php
require_once '../config/conexion.php';

class ReporteCierresPorFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteCierresPorFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "SELECT
      I.INC_numero_formato,
      (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
      A.ARE_nombre,
      CAT.CAT_nombre,
      I.INC_asunto,
      I.INC_documento,
      I.INC_codigoPatrimonial,
      PRI.PRI_nombre,
      U.USU_nombre,     
      O.CON_descripcion,
      (CONVERT(VARCHAR(10), CIE_fecha, 103)) AS fechaCierreFormateada,
      CASE
          WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
          ELSE E.EST_descripcion
      END AS ESTADO
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
      WHERE (I.EST_codigo = 5 OR C.EST_codigo = 5)
      AND CIE_fecha BETWEEN :fechaInicio AND :fechaFin
      ORDER BY I.INC_numero_formato ASC";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':fechaFin', $fechaFin);

    try {
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo json_encode(['error' => $e->getMessage()]);
      exit;
    }
    return $resultado;
  }
}
// Obtención del parámetro 'fechaInicio' y 'fechaFin' desde la solicitud
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';

$reporteCierresPorFecha = new ReporteCierresPorFecha();
$reporte = $reporteCierresPorFecha->getReporteCierresPorFecha($fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
