<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class IncidenciaModel extends Conexion
{

  public function __construct()
  {
    parent::__construct();
  }

  // Metodo para obtener incidencias por ID
  public function obtenerIncidenciaPorId($IncNumero)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM  INCIDENCIA i
        INNER JOIN CATEGORIA c ON i.CAT_codigo = c.CAT_codigo 
        WHERE INC_numero = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$IncNumero]);
        $registros = $stmt->fetch(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      echo "Error al obtener los registros de incidencias: " . $e->getMessage();
      return null;
    }
  }

  // Metodo para obtener los estados de la incidencia
  public function obtenerEstadoIncidencia($numeroIncidencia)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT EST_codigo FROM INCIDENCIA WHERE INC_numero = :numeroIncidencia";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':numeroIncidencia', $numeroIncidencia, PDO::PARAM_INT);
        $stmt->execute(); // ejecutar la consulta
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener el resultado
        return $result ? $result['EST_codigo'] : null;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el estado de la incidencia: " . $e->getMessage());
    }
  }

  // Método para insertar una nueva incidencia en la base de datos - ADMINISTRADOR / USUARIO.
  public function insertarIncidencia($INC_fecha, $INC_hora, $INC_asunto, $INC_descripcion, $INC_documento, $INC_codigoPatrimonial, $CAT_codigo, $ARE_codigo, $USU_codigo)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_registrar_incidencia :fecha, :hora, :asunto, :descripcion, :documento, :codigoPatrimonial, :categoria, :area, :usuario";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fecha', $INC_fecha);
        $stmt->bindParam(':hora', $INC_hora);
        $stmt->bindParam(':asunto', $INC_asunto);
        $stmt->bindParam(':descripcion', $INC_descripcion);
        $stmt->bindParam(':documento', $INC_documento);
        $stmt->bindParam(':codigoPatrimonial', $INC_codigoPatrimonial);
        $stmt->bindParam(':categoria', $CAT_codigo);
        $stmt->bindParam(':area', $ARE_codigo);
        $stmt->bindParam(':usuario', $USU_codigo);
        $success = $stmt->execute(); // Ejecutar la consulta

        // Registrar el evento en la auditoría
        $auditoria = new AuditoriaModel($conector);
        $auditoria->registrarEvento('INCIDENCIA', 'Registrar incidencia');
        return $success;
      }
      return false;
    } catch (PDOException $e) {
      echo "Error al insertar la incidencia para el administrador y usuario: " . $e->getMessage();
      return false;
    }
  }

  // Metodo para actualizar incidencia - Administrador
  public function editarIncidenciaAdmin($num_incidencia, $categoria, $area, $codigoPatrimonial, $asunto, $documento, $descripcion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_actualizar_incidencia :num_incidencia, :categoria, :area, :codigoPatrimonial, :asunto, :documento, :descripcion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':num_incidencia', $num_incidencia, PDO::PARAM_INT);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
        $stmt->bindParam(':asunto', $asunto);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->execute();
        // Registrar el evento en la auditoría
        $auditoria = new AuditoriaModel($conector);
        $auditoria->registrarEvento('INCIDENCIA', 'Actualizar incidencia');
        // Confirmar que se ha actualizado al menos una fila
        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      echo "Error al editar incidencia para el administrador: " . $e->getMessage();
      return false;
    }
  }

  // Metodo para actualizar incidencia - Administrador
  public function editarIncidenciaUser($num_incidencia, $categoria, $codigoPatrimonial, $asunto, $documento, $descripcion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_actualizar_incidencia_usuario :num_incidencia, :categoria, :codigoPatrimonial, :asunto, :documento, :descripcion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':num_incidencia', $num_incidencia, PDO::PARAM_INT);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
        $stmt->bindParam(':asunto', $asunto);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->execute(); // Ejecutar el procedimiento almacenado
        // Registrar el evento en la auditoría
        $auditoria = new AuditoriaModel($conector);
        $auditoria->registrarEvento('INCIDENCIA', 'Actualizar incidencia');
        // Confirmar que se ha actualizado al menos una fila
        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      echo "Error al editar incidencia para el usuario: " . $e->getMessage();
      return false;
    }
  }

  // Metodo para eliminar incidencia
  public function eliminarIncidencia($codigoIncidencia)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_eliminar_incidencia :codigoIncidencia";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoIncidencia', $codigoIncidencia);
        $stmt->execute();
        // Registrar el evento en la auditoría
        $auditoria = new AuditoriaModel($conector);
        $auditoria->registrarEvento('INCIDENCIA', 'Eliminar incidencia');
        return $stmt->rowCount() > 0 ? true : false;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al eliminar la incidencia: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar incidencias totales para reporte
  public function listarIncidenciasTotales()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_reporte_incidencias_totales
                ORDER BY INC_numero DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las incidencias totales: " . $e->getMessage());
    }
  }

  // Metodo para listar incidencias totales para reporte
  public function listarIncidenciasAreaEquipo()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_reporte_incidencias_area_equipo
                  ORDER BY INC_numero DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las incidencias totales: " . $e->getMessage());
    }
  }

  // Metodo para listar incidencias equipos
  public function listarIncidenciasEquipos()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_reporte_incidencias_equipos
                ORDER BY INC_numero DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las incidencias equipos: " . $e->getMessage());
    }
  }

  // Metodo listar las incidencias totales - ADMINISTRADOR 
  public function listarIncidenciasTotalesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * 
                FROM vista_incidencias_totales_administrador
                ORDER BY ultimaFecha DESC, ultimaHora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar las incidencias para el usuario: " . $e->getMessage());
    }
  }

  // Metodo listar incidencias pendientes - ADMINISTRADOR
  public function listarIncidenciasPendientesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_incidencias_pendientes
              ORDER BY 
              ultimaFecha DESC,  
              ultimaHora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar las incidencias para el administrador: " . $e->getMessage());
    }
  }

  // Metodo listar incidencias totales - USUARIO
  public function listarIncidenciasUsuario($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_incidencias_totales_usuario
        WHERE ARE_codigo = :are_codigo
        ORDER BY INC_numero_formato DESC";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $area, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar las incidencias totales para el usuario: " . $e->getMessage());
    }
  }

  // Método para listar incidencias registradas - ADMINISTRADOR
  public function listarIncidenciasRecepcion($start, $limit)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_incidencias_administrador
          ORDER BY 
          -- Extraer el año de INC_numero_formato y ordenar por año de forma descendente
          SUBSTRING(INC_numero_formato, CHARINDEX('-', INC_numero_formato) + 1, 4) DESC,
          INC_numero_formato DESC
          OFFSET :start ROWS
          FETCH NEXT :limit ROWS ONLY";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar incidencias registradas por el administrador: " . $e->getMessage());
    }
  }

  // Metodo para listar incidencias registradas por el administrador
  public function listarIncidenciasRegistroAdmin()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_incidencias_administrador
          ORDER BY 
          -- Extraer el año de INC_numero_formato y ordenar por año de forma descendente
          SUBSTRING(INC_numero_formato, CHARINDEX('-', INC_numero_formato) + 1, 4) DESC,
          INC_numero_formato DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar incidencias registradas por el administrador: " . $e->getMessage());
    }
  }

  // //  Metodo para listar incidencias por fecha para el administrador
  // public function listarIncidenciasFechaAdmin($fechaConsulta)
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {
  //       $sql = "SELECT * FROM vista_incidencias_fecha_admin
  //               WHERE INC_fecha = :fechaConsulta
  //               ORDER BY INC_numero DESC";
  //       $stmt = $conector->prepare($sql); // Ejecutar la consulta
  //       $stmt->bindParam(':fechaConsulta', $fechaConsulta, PDO::PARAM_STR); // Vinculamos el parámetro
  //       $stmt->execute();
  //       $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //       return $result;
  //     } else {
  //       throw new Exception("Error de conexión a la base de datos.");
  //     }
  //   } catch (PDOException $e) {
  //     throw new Exception("Error al listar las incidencias por fecha: " . $e->getMessage());
  //   }
  // }

  //  Metodo para listar incidencias por fecha para el usuario
  public function listarIncidenciasUserFecha($area, $fecha)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Prepara la consulta SQL con un marcador de posición para la fecha
        $sql = "SELECT * FROM vista_incidencias_fecha_user
                WHERE ARE_codigo = :area
                AND CAST(INC_fecha AS DATE) = CAST(:fechaConsulta AS DATE)
                ORDER BY INC_numero DESC";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area); // Asigna el parámetro area
        $stmt->bindParam(':fechaConsulta', $fecha, PDO::PARAM_STR); // Asigna el parámetro de fecha
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar incidencias registradas por el administrador: " . $e->getMessage());
    }
  }

  //  Contar el total de incidencias para empaginar tabla - ADMINISTRADOR
  public function contarIncidenciasAdministrador()
  {
    $conector = $this->getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as total FROM vista_incidencias_administrador";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al contar incidencias: " . $e->getMessage());
    }
  }

  //  Contar el total de incidencias para empaginar tabla - USUARIO
  public function contarIncidenciasUsuario($ARE_codigo)
  {
    $conector = $this->getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as total FROM INCIDENCIA 
              WHERE ARE_codigo = :are_codigo";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $ARE_codigo, PDO::PARAM_INT);
        $stmt->execute(); // ejecutar la consulta
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar las incidencias para el usuario: " . $e->getMessage());
    }
  }

  // // Metodo para listar incidencias registradas por el usuario de un area especifica
  // public function listarIncidenciasRegistroUsuario($ARE_codigo, $start, $limit)
  // {
  //   $conector = parent::getConexion();
  //   try {
  //     if ($conector != null) {
  //       $sql = "SELECT * FROM vista_incidencias_usuario
  //             WHERE ARE_codigo = :are_codigo
  //             ORDER BY INC_numero DESC
  //             OFFSET :start ROWS
  //             FETCH NEXT :limit ROWS ONLY";
  //       $stmt = $conector->prepare($sql);
  //       $stmt->bindParam(':are_codigo', $ARE_codigo, PDO::PARAM_INT);
  //       $stmt->bindParam(':start', $start, PDO::PARAM_INT);
  //       $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  //       $stmt->execute();
  //       $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //       return $result;
  //     } else {
  //       throw new Exception("Error de conexión a la base de datos.");
  //     }
  //   } catch (PDOException $e) {
  //     throw new Exception("Error al listar incidencias registradas por el usuario: " . $e->getMessage());
  //   }
  // }

  // Metodo para listar incidencias registradas por el usuario de un area especifica
  public function listarIncidenciasRegistroUsuario($ARE_codigo)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_incidencias_usuario
                WHERE ARE_codigo = :are_codigo
                ORDER BY INC_numero DESC";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $ARE_codigo, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar incidencias registradas por el usuario: " . $e->getMessage());
    }
  }

  // METODOS PARA EL PANEL INICIO
  // Contar incidencias del ultimo mes para el administrador
  public function contarIncidenciasUltimoMesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as incidencias_mes_actual
        FROM INCIDENCIA 
        WHERE INC_FECHA >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['incidencias_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar incidencias del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // METODO PARA CONTAR LOS PENDIENTES EN EL MES ACTUAL PARA EL ADMINISTRADOR
  public function contarPendientesUltimoMesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as pendientes_mes_actual FROM INCIDENCIA 
              WHERE INC_FECHA >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
              AND EST_codigo = 3";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pendientes_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar incidencias del ultimo mes para el administrador: " . $e->getMessage();
      return null;
    }
  }

  // Contar incidencias del ultimo mes para el usuario
  public function contarIncidenciasUltimoMesUsuario($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as incidencias_mes_actual FROM INCIDENCIA i
        INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
        WHERE INC_FECHA >= DATEADD(MONTH, -1, GETDATE()) AND 
        a.ARE_codigo = :are_codigo";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $area, PDO::PARAM_INT); // Vinculamos el parámetro
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['incidencias_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar incidencias del ultimo mes para el usuario: " . $e->getMessage();
      return null;
    }
  }

  // METODO PARA CONTAR LOS PENDIENTES EN EL MES ACTUAL PARA EL USUARIO
  public function contarPendientesUltimoMesUsuario($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) as pendientes_mes_actual FROM INCIDENCIA i
                INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
                WHERE INC_FECHA >= DATEADD(MONTH, -1, GETDATE())
                AND I.EST_codigo = 3 AND
                a.ARE_codigo = :are_codigo";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $area, PDO::PARAM_INT); // Vinculamos el parámetro
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pendientes_mes_actual'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar pendientes del ultimo mes para el usuario: " . $e->getMessage();
      return null;
    }
  }

  // METODOS PARA CONSULTAS
  //  Metodo para consultar incidencias pendientes - ADMINISTRADOR
  public function buscarIncidenciasPendientesAdministrador($area, $estado, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_incidencias_pendientes :area, :estado, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute(); // Ejecuta el procedimiento almacenado
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los resultados
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las incidencias DX: " . $e->getMessage());
    }
  }

  //  Metodo para consultar incidencias totales - ADMINISTRADOR
  public function buscarIncidenciaTotales($area, $codigoPatrimonial, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();

    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_incidencias_totales :area, :codigoPatrimonial, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute(); // Ejecuta el procedimiento almacenado
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los resultados
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al buscar las incidencias totales para el administrador: " . $e->getMessage());
    }
  }

  // Metodo para consultar incidencias totales para la visualción de reportes
  public function buscarIncidenciaTotalesFecha($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_filtrar_incidencias_totales_fecha  :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las incidencias totales: " . $e->getMessage());
    }
  }

  // Metodo para consultar incidencias por area - USUARIO
  public function buscarIncidenciaUsuario($area, $codigoPatrimonial, $estado, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_incidencias_usuario :area, :codigoPatrimonial, :estado, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute(); // Ejecutar el procedimiento almacenado
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los resultados
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las incidencias por usuario: " . $e->getMessage());
    }
  }

  // Metodo para filtrar incidencias por area y rango de fechas
  public function buscarIncidenciasArea($area, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_filtrar_incidencias_area :area, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute(); // Ejecuta el procedimiento almacenado
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los resultados
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las incidencias por area: " . $e->getMessage());
    }
  }

  // Metodo para filtrar incidencias por equipo y rango de fechas
  public function buscarIncidenciasEquipo($equipo, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_filtrar_incidencias_equipo :equipo, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':equipo', $equipo);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute(); // Ejecuta el procedimiento almacenado
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los resultados
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las incidencias por equipo: " . $e->getMessage());
    }
  }


  // Metodo para filtrar areas mas afectadas por incidencias
  public function buscarEquiposMasAfectados($codigoPatrimonial, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_filtrar_equipos_afectados :codigoPatrimonial, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute(); // Ejecuta el procedimiento almacenado
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los resultados
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener los equipos mas afectados: " . $e->getMessage());
    }
  }


  // Metodo para filtrar areas mas afectadas por incidencias
  public function buscarAreasMasAfectadas($categoria, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_filtrar_areas_afectadas :categoria, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute(); // Ejecuta el procedimiento almacenado
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los resultados
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las areas mas afectadas: " . $e->getMessage());
    }
  }


  // METODO PARA CONTAR LA CANTIDAD DE AREAS
  public function contarIncidencias()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS cantidadIncidencias FROM INCIDENCIA";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cantidadIncidencias'];
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar inciencias: " . $e->getMessage();
      return null;
    }
  }


  // METODO PARA CONTAR LA CANTIDAD DE AREAS
  public function areasConMasIncidencias()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT TOP 1 a.ARE_nombre AS areaMasIncidencia, COUNT(*) AS Incidencias
                FROM INCIDENCIA i
                INNER JOIN AREA a ON a.ARE_codigo = i.ARE_codigo
                WHERE i.INC_fecha >= DATEADD(MONTH, -1, GETDATE()) 
                GROUP BY a.ARE_nombre
                ORDER BY Incidencias DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
          return $result['areaMasIncidencia'];
        } else {
          return "No hay &aacute;reas con incidencias en el último mes.";
        }
      } else {
        echo "Error de conexión con la base de datos.";
        return null;
      }
    } catch (PDOException $e) {
      echo "Error al contar incidencias: " . $e->getMessage();
      return null;
    }
  }


  // Metodo para listar las equipos con mas incidencias
  public function listarEquiposMasAfectados()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_equipos_mas_afectados
                ORDER BY cantidadIncidencias DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar las equipos con mas incidencias: " . $e->getMessage());
    }
  }

  // METODO PARA LISTAR LAS AREAS CON MAS INCIDENCIAS - Reporte
  public function listarAreasMasAfectadas()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_area_mas_afectada
                ORDER BY cantidadIncidencias DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al listar areas con mas incidencias: " . $e->getMessage());
      return null;
    }
  }

  // Notificaiones para el administrador
  public function notificacionesAdmin()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_notificaciones_administrador
                ORDER BY tiempoDesdeIncidencia ASC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar notificaciones: " . $e->getMessage());
    }
  }

  // Notificaciones por usuario que resuelve la incidencia
  public function notificacionesSoporte($codigoUsuario)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_notificaciones_administrador
        ORDER BY tiempoDesdeIncidencia ASC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error al conectar a la base de datos");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar notificaciones para el soporte: " . $e->getMessage());
    }
  }

  // Notificaiones para el usuario
  public function notificacionesUser($area)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_notificaciones_usuario
                WHERE ARE_codigo = :area
                ORDER BY tiempoDesdeCierre ASC";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar notificaciones: " . $e->getMessage());
    }
  }

  // Reporte de cantidad de incidencias por mes
  public function listarIncidenciasMes($fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_reporte_incidencias_mes :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute(); // Ejecuta el procedimiento almacenado
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los resultados
        return $result;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener las incidencias por mes: " . $e->getMessage());
    }
  }




  // Método para obtener incidencias por mes para un año dado
  public function getIncidenciasPorMes($año)
  {
    $conector = parent::getConexion();
    $incidenciasPorMes = [];

    // Bucle a través de los meses del año (del 1 al 12)
    for ($mes = 1; $mes <= 12; $mes++) {
      // Preparar la consulta SQL corregida
      $sql = "SELECT COUNT(*) AS incidencias_mes_año
              FROM INCIDENCIA
              WHERE INC_FECHA >= :inicio_mes
              AND INC_FECHA < :inicio_mes_siguiente";

      // Definir las fechas de inicio y fin del mes
      $inicioMes = "$año-$mes-01";
      $inicioMesSiguiente = ($mes == 12) ? ($año + 1) . "-01-01" : "$año-" . ($mes + 1) . "-01";

      // Preparar la consulta y ejecutar
      $stmt = $conector->prepare($sql);
      $stmt->bindParam(':inicio_mes', $inicioMes, PDO::PARAM_STR);
      $stmt->bindParam(':inicio_mes_siguiente', $inicioMesSiguiente, PDO::PARAM_STR);
      $stmt->execute();

      // Obtener el resultado y almacenar en el array
      $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
      $incidenciasPorMes[$mes] = $resultado['incidencias_mes_año'];
    }

    return $incidenciasPorMes;
  }

  // TODO: Metodos para  obtener las cantidades de incidencias mensuales
  public function contarIncidenciasEnero($anio)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS incidencias_enero
                      FROM INCIDENCIA
                      WHERE YEAR(INC_FECHA) = :anio
                      AND MONTH(INC_FECHA) = 1";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['incidencias_enero'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias de enero: " . $e->getMessage());
    }
  }


  // Metodo para obtener la cantidad de incidencias en febrero
  public function contarIncidenciasFebrero($anio)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS incidencias_febrero
        FROM INCIDENCIA
        WHERE YEAR(INC_FECHA) = :anio
        AND MONTH(INC_FECHA) = 2";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['incidencias_febrero'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias de febrero: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la cantidad de incidencias en marzo
  public function contarIncidenciasMarzo($anio)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS incidencias_marzo
        FROM INCIDENCIA
        WHERE YEAR(INC_FECHA) = :anio
        AND MONTH(INC_FECHA) = 3";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['incidencias_marzo'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias de marzo: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la cantidad de incidencias en abril
  public function contarIncidenciasAbril($anio)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS incidencias_abril
        FROM INCIDENCIA
        WHERE YEAR(INC_FECHA) = :anio
        AND MONTH(INC_FECHA) = 4";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['incidencias_abril'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias de abril: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la cantidad de incidencias en mayo
  public function contarIncidenciasMayo($anio)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS incidencias_mayo
        FROM INCIDENCIA
        WHERE YEAR(INC_FECHA) = :anio
        AND MONTH(INC_FECHA) = 5";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['incidencias_mayo'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias de mayo: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la cantidad de incidencias en junio
  public function contarIncidenciasJunio($anio)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS incidencias_junio
        FROM INCIDENCIA
        WHERE YEAR(INC_FECHA) = :anio
        AND MONTH(INC_FECHA) = 6";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['incidencias_junio'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias de junio: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la cantidad de incidencias en junio
  public function contarIncidenciasJulio($anio)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS incidencias_julio
        FROM INCIDENCIA
        WHERE YEAR(INC_FECHA) = :anio
        AND MONTH(INC_FECHA) = 7";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['incidencias_julio'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias de julio: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la cantidad de incidencias en agosto
  public function contarIncidenciasAgosto($anio)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS incidencias_agosto
        FROM INCIDENCIA
        WHERE YEAR(INC_FECHA) = :anio
        AND MONTH(INC_FECHA) = 8";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['incidencias_agosto'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias de agosto: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la cantidad de incidencias en septiembre
  public function contarIncidenciasSetiembre($anio)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS incidencias_setiembre
        FROM INCIDENCIA
        WHERE YEAR(INC_FECHA) = :anio
        AND MONTH(INC_FECHA) = 9";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['incidencias_setiembre'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias de septiembre: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la cantidad de incidencias en octubre
  public function contarIncidenciasOctubre($anio)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS incidencias_octubre
        FROM INCIDENCIA
        WHERE YEAR(INC_FECHA) = :anio
        AND MONTH(INC_FECHA) = 10";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['incidencias_octubre'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias de octubre: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la cantidad de incidencias en noviembre
  public function contarIncidenciasNoviembre($anio)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS incidencias_noviembre
        FROM INCIDENCIA
        WHERE YEAR(INC_FECHA) = :anio
        AND MONTH(INC_FECHA) = 11";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['incidencias_noviembre'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias de noviembre: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la cantidad de incidencias en diciembre
  public function contarIncidenciasDiciembre($anio)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) AS incidencias_diciembre
        FROM INCIDENCIA
        WHERE YEAR(INC_FECHA) = :anio
        AND MONTH(INC_FECHA) = 12";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['incidencias_diciembre'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las incidencias de diciembre: " . $e->getMessage());
      return null;
    }
  }
}
