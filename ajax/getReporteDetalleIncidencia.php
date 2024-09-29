<?php
require_once '../config/conexion.php';

class ReporteIncidencia extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }
  public function getReporteNumeroIncidencia($numeroIncidencia)
  {
    $conector = parent::getConexion();
    $sql = "SELECT 
      I.INC_numero,
      I.INC_numero_formato,
      (CONVERT(VARCHAR(10), INC_fecha, 103)) AS fechaIncidenciaFormateada,
      I.INC_codigoPatrimonial,
      I.INC_asunto,
      I.INC_documento,
      I.INC_descripcion,
      CAT.CAT_nombre,
      A.ARE_nombre,
      E.EST_descripcion AS ESTADO,
      p.PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno AS Usuario
      FROM INCIDENCIA I
      INNER JOIN AREA A ON I.ARE_codigo = A.ARE_codigo
      INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
      INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
      LEFT JOIN ESTADO EC ON I.EST_codigo = EC.EST_codigo
      LEFT JOIN USUARIO U ON U.USU_codigo = I.USU_codigo
      INNER JOIN PERSONA p ON p.PER_codigo = U.PER_codigo
      WHERE I.EST_codigo IN (3, 4)
      AND INC_numero_formato = :numeroIncidencia
      ORDER BY i.INC_numero DESC";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':numeroIncidencia', $numeroIncidencia);
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

// Obtención del parámetro 'numeroIncidencia' desde la solicitud
$numeroIncidencia = isset($_GET['numeroIncidencia']) ? $_GET['numeroIncidencia'] : '';


$reporteIncidencia = new ReporteIncidencia();
$reporte = $reporteIncidencia->getReporteNumeroIncidencia($numeroIncidencia);

header('Content-Type: application/json');
echo json_encode($reporte);
