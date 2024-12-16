<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class RecepcionModel extends Conexion
{
  private $auditoria;

  public function __construct()
  {
    parent::__construct();
    $conector = parent::getConexion();

    // Inicializar la instancia de AuditoriaModel
    if ($conector != null) {
      $this->auditoria = new AuditoriaModel($conector);
    } else {
      throw new Exception("Error de conexión a la base de datos");
    }
  }

  // Metodo para obtener recepcion por ID
  public function obtenerRecepcionPorId($RecNumero)
  {
    $conector = parent::getConexion();
    try {
      $sql = "SELECT * FROM  RECEPCION r
      WHERE REC_numero = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$RecNumero]);
      $registros = $stmt->fetch(PDO::FETCH_ASSOC);
      return $registros;
    } catch (PDOException $e) {
      echo "Error al obtener los registros de incidencias: " . $e->getMessage();
      return null;
    }
  }

  // Metodo para obtener el ultimo codigo de recepcion registrado
  private function obtenerUltimoCodigoRecepcion()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT MAX(REC_numero) AS ultimo_codigo FROM RECEPCION";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['ultimo_codigo'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el ultimo codigo de recepcion registrado: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para insertar Recepcion
  public function insertarRecepcion($fecha, $hora, $incidencia, $prioridad, $impacto, $usuario)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_insertar_recepcion :fecha, :hora, :incidencia, :prioridad, :impacto, :usuario";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':incidencia', $incidencia);
        $stmt->bindParam(':prioridad', $prioridad);
        $stmt->bindParam(':impacto', $impacto);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        // Obtener el ultimo codigo de recepcion registrado
        $numRecepcion = $this->obtenerUltimoCodigoRecepcion();
        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('RECEPCION', 'Recepcionar incidencia', $numRecepcion);
        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al insertar recepcion: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para eliminar recepcion
  public function eliminarRecepcion($codigoRecepcion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_eliminar_recepcion :codigoRecepcion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoRecepcion', $codigoRecepcion);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('RECEPCION', 'Eliminar incidencia recepcionada', $codigoRecepcion);
        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al eliminar la recepcion: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener el estado de la recepcion
  public function obtenerEstadoRecepcion($numeroRecepcion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT EST_codigo FROM RECEPCION WHERE REC_numero = :numeroRecepcion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':numeroRecepcion', $numeroRecepcion, PDO::PARAM_INT);
        $stmt->execute(); // Ejecutar la consulta
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener el resultado
        return $result ? $result['EST_codigo'] : null;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el estado de la incidencia: " . $e->getMessage());
    }
  }

  // Metodo para editar recepcion
  public function editarRecepcion($prioridad, $impacto, $recepcion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_actualizar_recepcion :num_recepcion, :prioridad, :impacto";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':num_recepcion', $recepcion);
        $stmt->bindParam(':prioridad', $prioridad);
        $stmt->bindParam(':impacto', $impacto);
        $stmt->execute(); // Ejecutar el procedimiento almacenado
        // Confirmar que se ha actualizado al menos una fila

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('RECEPCION', 'Actualizar incidencia recepcionada', $recepcion);
        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      echo "Error al editar recepcion para el administrador: " . $e->getMessage();
      return false;
    }
  }

  // Metodo para listar incidencias recepciondas
  public function listarRecepciones($start, $limit)
  {
    $conector = parent::getConexion();
    if ($conector != null) {
      try {
        $sql = "SELECT * FROM vista_recepciones
            ORDER BY fechaRecepcionFormateada DESC
            OFFSET :start ROWS
            FETCH NEXT :limit ROWS ONLY";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $registros;
      } catch (PDOException $e) {
        echo "Error al listar recepciones: " . $e->getMessage();
        return null;
      }
    } else {
      echo "Error de conexión a la base de datos.";
      return null;
    }
  }

  // Metodo para listar incidencias pendientes de cierre para reporte
  public function listarIncidenciasPendientesCierre()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_incidencias_pendientes
        ORDER BY 
            ultimaFecha DESC, --Ordenar por la última fecha
            ultimaHora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las incidencias pendientes de cierre: " . $e->getMessage());
    }
  }

  // Metodo para contar el total de recepciones
  public function contarRecepciones()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as total FROM vista_recepciones";
        //  r    WHERE r.EST_codigo = 4";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al contar recepciones sin cerrar: " . $e->getMessage());
    }
  }

  // Contar recepciones del ultimo mes para el administrador
  public function contarRecepcionesUltimoMesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS recepciones_mes_actual FROM INCIDENCIA I
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
        WHERE (C.CIE_numero IS NOT NULL AND EC.EST_codigo = 4)
        OR (C.CIE_numero IS NULL AND E.EST_codigo = 4)
        AND REC_FECHA >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['recepciones_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar recepciones del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // Contar recepcions del ultimo mes para el administrador
  public function contarRecepcionesUltimoMesUsuario($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS recepciones_mes_actual FROM INCIDENCIA I
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
                WHERE (C.CIE_numero IS NOT NULL AND EC.EST_codigo = 4)
                OR (C.CIE_numero IS NULL AND E.EST_codigo = 4)
                AND REC_FECHA >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
                AND a.ARE_codigo = :are_codigo";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $area, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['recepciones_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar recepciones del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // Metodo para listar los registros de recepciones en la tabla de auditoria
  public function listarEventosRecepciones()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_eventos_recepciones
                ORDER BY AUD_fecha DESC, AUD_hora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar eventos de recepciones en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }
}
