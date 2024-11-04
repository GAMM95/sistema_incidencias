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

  /**
   * Método para insertar una nueva incidencia en la base de datos - ADMINISTRADOR / USUARIO.
   * 
   * Este método permite registrar una nueva incidencia en el sistema. Es utilizado tanto por
   * administradores como por usuarios. La incidencia se almacena en la base de datos a través
   * de un procedimiento almacenado.
   * 
   * Retorno:
   * - @return bool: Retorna `true` si la incidencia fue registrada exitosamente, `false` en caso contrario.
   */
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

  /**
   * Método para consultar incidencias de la base de datos para el ADMINISTRADOR
   * 
   * Este método permite a un administrador y usuario consultar incidencias en el sistema. 
   * La incidencia se consulta con los detalles proporcionados, incluyendo la fecha, 
   * asunto, descripción, documento adjunto, código patrimonial, código de estado, código de categoría, 
   * código de área, y el código de usuario que registra la incidencia.
   * 
   * @return int|false Retorna el ID de la incidencia recién insertada si la operación es exitosa. 
   *                   En caso de error, retorna false.
   */

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

  // Metodo listar las incidencias totales - ADMINISTRADOR 
  public function listarIncidenciasTotalesAdministrador()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_incidencias_totales_administrador
          ORDER BY INC_numero DESC";
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
  public function listarIncidenciasUsuario($ARE_codigo)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vista_incidencias_totales_usuario
        WHERE ARE_codigo = :are_codigo
        ORDER BY INC_numero_formato DESC";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':are_codigo', $ARE_codigo, PDO::PARAM_INT); // Vinculamos el parámetro
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

  // Metodo para consultar incidencias por area - USUARIO
  public function buscarIncidenciaUsuario($area, $codigoPatrimonial, $estado, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_ConsultarIncidenciasUsuario :area, :codigoPatrimonial, :estado, :fechaInicio, :fechaFin";
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
      throw new Exception("Error al obtener las incidencias  usuario DX: " . $e->getMessage());
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
}
