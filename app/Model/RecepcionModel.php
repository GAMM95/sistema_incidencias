<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class RecepcionModel extends Conexion
{
  public function __construct()
  {
    parent::__construct();
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

        // Registrar el evento en la auditoría
        $auditoria = new AuditoriaModel($conector);
        $auditoria->registrarEvento('RECEPCION', 'Recepcionar incidencia');
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
        $auditoria = new AuditoriaModel($conector);
        $auditoria->registrarEvento('RECEPCION', 'Eliminar incidencia recepcionada');
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
        $auditoria = new AuditoriaModel($conector);
        $auditoria->registrarEvento('RECEPCION', 'Actualizar incidencia recepcionada');
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
          ORDER BY 
            SUBSTRING(INC_numero_formato, CHARINDEX('-', INC_numero_formato) + 1, 4) DESC,
            INC_numero_formato DESC
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

  // Metodo para contar el total de recepciones
  public function contarRecepciones()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as total FROM RECEPCION r
      WHERE r.EST_codigo = 4";
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
        $sql = "SELECT COUNT(*) as recepciones_mes_actual FROM RECEPCION 
        WHERE REC_FECHA >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
        AND EST_codigo = 4";
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
        $sql = "SELECT COUNT(*) as recepciones_mes_actual FROM RECEPCION r
				        INNER JOIN INCIDENCIA i ON i.INC_numero = r.INC_numero
                INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
                WHERE REC_FECHA >= DATEADD(MONTH, -1, GETDATE()) AND r.EST_codigo = 4 AND
                a.ARE_codigo = :are_codigo";
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
}
