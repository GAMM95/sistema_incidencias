
<?php
require_once '../../../../config/conexion.php';

class ReporteIncidenciasAsignadasUsuarioFecha extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getReporteIncidenciasAsignadasUsuarioFecha($usuario, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    $sql = "SELECT 
    I.INC_numero,
    ASI.ASI_codigo,
    I.INC_numero_formato,
    M.MAN_codigo,
    (CONVERT(VARCHAR(10), REC_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), REC_hora, 0), 7), 6, 0, ' ')) AS fechaRecepcionFormateada,
    (CONVERT(VARCHAR(10), ASI.ASI_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), ASI.ASI_hora, 0), 7), 6, 0, ' ')) AS fechaAsignacionFormateada,    
    (CONVERT(VARCHAR(10), M.MAN_fecha, 103) + ' - ' + STUFF(RIGHT('0' + CONVERT(VARCHAR(7), M.MAN_hora, 0), 7), 6, 0, ' ')) AS fechaMantenimientoFormateada,
    
    -- Cálculo del tiempo de mantenimiento en segundos
    DATEDIFF(SECOND, 
        CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
        CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
    ) AS tiempoMantenimientoSegundos,

    -- Mostrar solo días, horas, minutos y segundos según sea necesario
    CASE
      -- Si tiene días, mostrar días, horas, minutos
      WHEN DATEDIFF(SECOND, 
        CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
        CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
      ) >= 86400 
      THEN
        CAST(DATEDIFF(SECOND, 
          CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
          CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
        ) / 86400 AS VARCHAR) + ' días ' +
        CAST((DATEDIFF(SECOND, 
          CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
          CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
        ) % 86400) / 3600 AS VARCHAR) + 
        CASE 
          WHEN (DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
          ) % 86400) / 3600 = 1 THEN ' hora '
          ELSE ' horas ' 
        END +
        CAST(((DATEDIFF(SECOND, 
          CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
          CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
        ) % 86400) % 3600) / 60 AS VARCHAR) + 
        CASE 
          WHEN ((DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
          ) % 86400) % 3600) / 60 = 1 THEN ' minuto '
          ELSE ' minutos '
        END

        -- Si tiene horas pero no días, mostrar horas y minutos
        WHEN DATEDIFF(SECOND, 
          CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
          CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
        ) >= 3600 
        THEN
        CAST((DATEDIFF(SECOND, 
          CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
          CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
        ) % 86400) / 3600 AS VARCHAR) + 
        CASE 
          WHEN (DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
          ) % 86400) / 3600 = 1 THEN ' hora '
          ELSE ' horas '
        END +
        CAST(((DATEDIFF(SECOND, 
          CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
          CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
        ) % 86400) % 3600) / 60 AS VARCHAR) + 
        CASE 
          WHEN ((DATEDIFF(SECOND, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
            CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
          ) % 86400) % 3600) / 60 = 1 THEN ' minuto '
          ELSE ' minutos '
        END

       -- Si tiene menos de una hora, mostrar solo minutos
			ELSE
				CAST(DATEDIFF(MINUTE, 
					CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
					CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)
				) AS VARCHAR) + 
          CASE 
            WHEN DATEDIFF(MINUTE, CAST(CONVERT(VARCHAR(10), ASI.ASI_fecha, 120) + ' ' + CONVERT(VARCHAR(8), ASI.ASI_hora, 108) AS DATETIME), 
              CAST(CONVERT(VARCHAR(10), M.MAN_fecha, 120) + ' ' + CONVERT(VARCHAR(8), M.MAN_hora, 108) AS DATETIME)) = 1 
            THEN ' minuto '
            ELSE ' minutos '
          END
      END AS tiempoMantenimientoFormateado, 
      M.MAN_fecha,
      A.ARE_nombre,
      I.INC_asunto,
      I.INC_documento,
      I.INC_codigoPatrimonial,
      B.BIE_nombre,
      U.USU_codigo,
      P.PER_nombres + ' ' + P.PER_apellidoPaterno AS usuarioSoporte,
      pA.PER_nombres + ' ' + pA.PER_apellidoPaterno AS usuarioAsignador,
      CASE
        WHEN C.CIE_numero IS NOT NULL THEN EC.EST_descripcion
        ELSE E.EST_descripcion
      END AS Estado,
      -- Última modificación (fecha y hora más reciente)
        MAX(COALESCE(C.CIE_fecha, M.MAN_fecha, ASI.ASI_fecha, R.REC_fecha, I.INC_fecha)) AS ultimaFecha,
        MAX(COALESCE(C.CIE_hora, M.MAN_hora, ASI.ASI_hora, R.REC_hora, I.INC_hora)) AS ultimaHora
    FROM ASIGNACION ASI
      INNER JOIN ESTADO E ON E.EST_codigo = ASI.EST_codigo
      LEFT JOIN RECEPCION R ON R.REC_numero = ASI.REC_numero
      LEFT JOIN INCIDENCIA I ON I.INC_numero = R.INC_numero
      LEFT JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
      INNER JOIN AREA A ON A.ARE_codigo = I.ARE_codigo
      LEFT JOIN USUARIO uA ON uA.USU_codigo = R.USU_codigo
      LEFT JOIN PERSONA pA ON pA.PER_codigo = uA.PER_codigo
      LEFT JOIN USUARIO U ON U.USU_codigo = ASI.USU_codigo
      INNER JOIN PERSONA P ON P.PER_codigo = U.PER_codigo
      LEFT JOIN MANTENIMIENTO M ON M.ASI_codigo = ASI.ASI_codigo
      LEFT JOIN CIERRE C ON C.MAN_codigo = M.MAN_codigo
      LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
      WHERE U.USU_codigo = :usuario 
      AND ASI.ASI_fecha BETWEEN :fechaInicio AND :fechaFin
    GROUP BY 
      I.INC_numero, 
      ASI.ASI_codigo, 
      I.INC_numero_formato, 
      M.MAN_codigo, 
      REC_fecha, 
      REC_hora, 
      ASI.ASI_fecha, 
      ASI.ASI_hora, 
      M.MAN_fecha, 
      M.MAN_hora,
      A.ARE_nombre, 
      I.INC_asunto, 
      I.INC_documento, 
      I.INC_codigoPatrimonial, 
      B.BIE_nombre, 
      U.USU_codigo, 
      P.PER_nombres, 
      P.PER_apellidoPaterno, 
      pA.PER_nombres, 
      pA.PER_apellidoPaterno, 
      C.CIE_numero, 
      EC.EST_descripcion, 
      E.EST_descripcion
    ORDER BY ultimaFecha DESC, ultimaHora DESC";
    $stmt = $conector->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
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
$usuario = isset($_GET['usuarioIncidenciasAsignadas']) ? $_GET['usuarioIncidenciasAsignadas'] : null;
$fechaInicio = isset($_GET['fechaInicioIncidenciasAsignadas']) ? $_GET['fechaInicioIncidenciasAsignadas'] : null;
$fechaFin = isset($_GET['fechaFinIncidenciasAsignadas']) ? $_GET['fechaFinIncidenciasAsignadas'] : null;

if (!$fechaInicio || !$fechaFin) {
  echo json_encode(['error' => 'Las fechas de inicio y fin son requeridas']);
  exit;
}

$reporteIncidenciasAsignadasUsuarioFecha = new ReporteIncidenciasAsignadasUsuarioFecha();
$reporte = $reporteIncidenciasAsignadasUsuarioFecha->getReporteIncidenciasAsignadasUsuarioFecha($usuario, $fechaInicio, $fechaFin);

header('Content-Type: application/json');
echo json_encode($reporte);
exit();