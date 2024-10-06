<?php
require_once 'config/conexion.php';

class CierreModel extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  // Metodo para obtener cierres por ID
  public function obtenerCierrePorID($CieNumero)
  {
    $conector = parent::getConexion();
    try {
      $sql = "SELECT * FROM CIERRE c 
      WHERE CIE_numero = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute(([$CieNumero]));
      $registros = $stmt->fetch(PDO::FETCH_ASSOC);
      return $registros;
    } catch (PDOException $e) {
      echo "Error al obtener los registros de los cierres: " . $e->getMessage();
      return null;
    }
  }

  // Metodo para insertar Cierre
  public function insertarCierre($fecha, $hora, $diagnostico, $documento, $asunto, $recomendaciones, $operatividad, $recepcion, $usuario)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_InsertarCierreActualizarRecepcion :fecha, :hora, :diagnostico, :documento, :asunto, :recomendaciones, :operatividad, :recepcion, :usuario";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':diagnostico', $diagnostico);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':asunto', $asunto);
        $stmt->bindParam(':recomendaciones', $recomendaciones);
        $stmt->bindParam(':operatividad', $operatividad);
        $stmt->bindParam(':recepcion', $recepcion);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al insertar el cierre: " . $e->getMessage());
      return null;
    }
  }

  //  Metodo para eliminar CIERRE
  public function eliminarCierre($codigoCierre)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_eliminarCierre :codigoCierre";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoCierre', $codigoCierre);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al eliminar el cierre: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para editar cierres
  public function editarCierre($cierre, $asunto, $documento, $condicion, $diagnostico, $recomendaciones)
  {
    $conector = parent::getConexion();
    if ($conector != null) {
      $sql = "EXEC sp_ActualizarCierre :num_cierre, :asunto, :documento, :condicion, :diagnostico, :recomendaciones";
      $stmt = $conector->prepare($sql);
      $stmt->bindParam(':num_cierre', $cierre);
      $stmt->bindParam(':asunto', $asunto);
      $stmt->bindParam(':documento', $documento);
      $stmt->bindParam(':condicion', $condicion);
      $stmt->bindParam(':diagnostico', $diagnostico);
      $stmt->bindParam(':recomendaciones', $recomendaciones);
      $stmt->execute(); // Ejecutar el procedimiento almacenado
      if ($stmt->rowCount() > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      throw new Exception("Error de conexion a la base de datos");
      return null;
    }
    try {
    } catch (PDOException $e) {
      throw new PDOException("Error al editar el cierre: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar cierres Administrador - FORM CONSULTAR CIERRE
  public function listarCierresConsulta()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_cierres
        ORDER BY CIE_numero DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      echo "Error al listar cierres registrados para el administrador: " . $e->getMessage();
      return false;
    }
  }

  // TODO: Metodo para contar incidencias cerradas para la tabla listar cierres
  // public function contarIncidenciasCerradas()
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {
  //       $sql = "SELECT COUNT(*) AS total
  //       FROM RECEPCION R
  //       RIGHT JOIN INCIDENCIA I ON R.INC_numero = I.INC_numero
  //       INNER JOIN  AREA A ON I.ARE_codigo = A.ARE_codigo
  //       INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
  //       INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
  //       LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
  //       LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
  //       INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
  //       INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
  //       WHERE  I.EST_codigo = 5 OR C.EST_codigo = 5";
  //       $stmt = $conector->prepare($sql);
  //       $stmt->execute();
  //       $result = $stmt->fetch(PDO::FETCH_ASSOC);
  //       return $result['total'];
  //     } else {
  //       throw new Exception("Error de conexión a la base de datos.");
  //     }
  //   } catch (PDOException $e) {
  //     echo "Error contar incidencias cerradas: " . $e->getMessage();
  //     return null;
  //   }
  // }

  // Metodo para obtener la lista de incidencias cerradas para la tabla listar cierres
  public function listarCierres()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_cierres
        ORDER BY CIE_numero DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      echo "Error obtener lista de incidencias cerradas: " . $e->getMessage();
      return null;
    }
  }
  // public function listarCierres($start, $limit)
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {
  //       $sql = "SELECT * FROM vista_cierres
  //       ORDER BY CIE_numero DESC
  //       OFFSET :start ROWS
  //       FETCH NEXT :limit ROWS ONLY";
  //       $stmt = $conector->prepare($sql);
  //       $stmt->bindParam(':start', $start, PDO::PARAM_INT);
  //       $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  //       $stmt->execute();
  //       $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //       return $result;
  //     } else {
  //       throw new Exception("Error de conexión a la base de datos.");
  //       return null;
  //     }
  //   } catch (PDOException $e) {
  //     echo "Error obtener lista de incidencias cerradas: " . $e->getMessage();
  //     return null;
  //   }
  // }

  // Contar incidencias del ultimo mes para el administrador
  public function contarCierresUltimoMesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT
        COUNT(*) AS cierres_mes_actual
        FROM RECEPCION R
        INNER JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
        RIGHT JOIN INCIDENCIA I ON R.INC_numero = I.INC_numero
        INNER JOIN  AREA A ON I.ARE_codigo = A.ARE_codigo
        INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
        INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
        INNER JOIN ASIGNACION ASI ON ASI.REC_numero = R.REC_numero
        INNER JOIN MANTENIMIENTO MAN ON MAN.ASI_codigo = ASI.ASI_codigo
        LEFT JOIN CIERRE C ON C.MAN_codigo =  MAN.MAN_codigo
        LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
        INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
        INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
        INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
        WHERE  I.EST_codigo = 5 OR C.EST_codigo = 5
        AND INC_FECHA >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
        AND INC_FECHA < DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()) + 1, 1) 
        AND CIE_FECHA >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
        AND CIE_FECHA < DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()) + 1, 1)";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cierres_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar cierres del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // Contar incidencias del ultimo mes para el usuario
  public function contarCierresUltimoMesUsuario($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT
        COUNT(*) AS cierres_mes_actual
        FROM RECEPCION R
        INNER JOIN PRIORIDAD PRI ON PRI.PRI_codigo = R.PRI_codigo
        RIGHT JOIN INCIDENCIA I ON R.INC_numero = I.INC_numero
        INNER JOIN  AREA A ON I.ARE_codigo = A.ARE_codigo
        INNER JOIN CATEGORIA CAT ON I.CAT_codigo = CAT.CAT_codigo
        INNER JOIN ESTADO E ON I.EST_codigo = E.EST_codigo
        LEFT JOIN CIERRE C ON R.REC_numero = C.REC_numero
        LEFT JOIN ESTADO EC ON C.EST_codigo = EC.EST_codigo
        INNER JOIN CONDICION O ON O.CON_codigo = C.CON_codigo
        INNER JOIN USUARIO U ON U.USU_codigo = C.USU_codigo
        INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
        WHERE  I.EST_codigo = 5 OR C.EST_codigo = 5
        AND INC_FECHA >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
        AND INC_FECHA < DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()) + 1, 1) 
        AND CIE_FECHA >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
        AND CIE_FECHA < DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()) + 1, 1) AND
				a.ARE_codigo = :are_codigo";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $area, PDO::PARAM_INT); // Vinculamos el parámetro
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cierres_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar cierres del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  public function buscarCierres($area, $codigoPatrimonial, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();

    try {
      if ($conector != null) {
        $sql = "EXEC sp_ConsultarCierres :area, :codigoPatrimonial, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute(); // Ejecuta el query
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener los cierres XD: " . $e->getMessage());
    }
  }
}
