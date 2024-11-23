<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class SolucionModel extends Conexion
{

  public function __construct()
  {
    parent::__construct();
  }

  // Metodo para obtener soluciones por el ID
  public function obtenerSolicionPorID($codigoSolucion)
  {
    try {
      $conector = $this->getConexion();
      if ($conector != null) {
        $sql = "SELECT * FROM SOLUCION WHERE SOL_codigo = :codigoSolucion";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoSolucion]);
        $registros = $stmt->fetch(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener la solución: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para insertar la solución
  public function insertarSolucion($descripcion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Cambié la tabla a SOLUCION y la columna a algo acorde (por ejemplo, SOL_descripcion)
        $sql = "EXEC sp_registrar_solucion :descripcion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->execute();

        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $auditoria = new AuditoriaModel($conector);
          $auditoria->registrarEvento('SOLUCIÓN', 'Registrar solución');
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexión a la base de datos");
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al insertar solución: " . $e->getMessage());
    }
  }

  // Metodo para editar el tipo de bien
  public function editarSolucion($descripcion, $codigoSolucion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $query = "UPDATE SOLUCION SET SOL_descripcion = :descripcion
                WHERE SOL_codigo = :codigoSolucion";
        $stmt = $conector->prepare($query);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':codigoSolucion', $codigoSolucion);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $auditoria = new AuditoriaModel($conector);
        $auditoria->registrarEvento('BIEN', 'Actualizar solución');

        return $stmt->rowCount();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al editar la soliución: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar las soluciones
  public function listarSoluciones()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM SOLUCION";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al listar soluciones: " . $e->getMessage());
      return null;
    }
  }

  // Método para habilitar solucion 
  public function habilitarSolucion($codigoSolucion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_habilitar_solucion :codigoSolucion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoSolucion', $codigoSolucion, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $auditoria = new AuditoriaModel($conector);
          $auditoria->registrarEvento('BIEN', 'Habilitar solución');
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al habilitar solución: " . $e->getMessage());
      return null;
    }
  }

  // METODO PARA DESHABILITAR SOLUCIONES
  public function deshabilitarSolucion($codigoSolucion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_deshabilitar_solucion :codigoSolucion";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoSolucion', $codigoSolucion, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $auditoria = new AuditoriaModel($conector);
          $auditoria->registrarEvento('BIEN', 'Deshabilitar solución');
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al deshabilitar solución: " . $e->getMessage());
    }
  }
}
