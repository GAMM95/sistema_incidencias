<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class AreaModel extends Conexion
{

  public function __construct()
  {
    parent::__construct();
  }

  // Método para validar la existencia de una area
  public function validarAreaExistente($nombreArea)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT COUNT(*) FROM AREA WHERE ARE_nombre = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$nombreArea]);
        $count = $stmt->fetchColumn();
        return $count > 0;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al verificar el nombre de la area: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para registrar areas
  public function insertarArea($nombreArea)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_registrar_area :nombreArea";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':nombreArea', $nombreArea);
        $stmt->execute();

        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $auditoria = new AuditoriaModel($conector);
          $auditoria->registrarEvento('AREA', 'Registro de área');
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException(("Error al insertar area: " . $e->getMessage()));
    }
  }

  // Metodo para listar areas
  public function listarArea()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT ARE_codigo, ARE_nombre, EST_codigo FROM AREA WHERE ARE_codigo <> 0 ORDER BY ARE_codigo DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultados;
      } else {
        throw new Exception("Error al conectar a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las áreas: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener areas por el ID
  public function obtenerAreaPorId($codigoArea)
  {
    try {
      $conector = $this->getConexion();
      if ($conector != null) {
        $sql = "SELECT * FROM AREA WHERE ARE_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoArea]);
        $registros = $stmt->fetch(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexión con la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el área: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para editar areas
  public function editarArea($nombreArea, $codigoArea)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "UPDATE AREA SET ARE_nombre = ? WHERE ARE_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([
          $nombreArea,
          $codigoArea
        ]);
        // Registrar el evento en la auditoría
        $auditoria = new AuditoriaModel($conector);
        $auditoria->registrarEvento('AREA', 'Actualización de área');
        return $stmt->rowCount();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al actualizar el área: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para contar la cantidad de areas
  public function contarAreas()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "  SELECT COUNT(*) AS cantidadAreas FROM AREA WHERE EST_codigo = 1";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cantidadAreas'];
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al contar areas: " . $e->getMessage());
      return null;
    }
  }

  // Método para filtrar areas por término de búsqueda
  public function filtrarAreas($terminoBusqueda)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM AREA
                 WHERE ARE_nombre LIKE :terminoBusqueda";
        $stmt = $conector->prepare($sql);
        $terminoBusqueda = "%$terminoBusqueda%";
        $stmt->bindParam(':terminoBusqueda', $terminoBusqueda, PDO::PARAM_STR);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $registros;
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al filtrar areas: " . $e->getMessage());
      return null;
    }
  }

  // Método para habilitar area
  public function habilitarArea($codigoArea)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_habilitar_area :codigoArea";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoArea', $codigoArea, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $auditoria = new AuditoriaModel($conector);
          $auditoria->registrarEvento('AREA', 'Habilitar de área');
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al habilitar area: " . $e->getMessage());
      return null;
    }
  }

  // METODO PARA DESHABILITAR AREA
  public function deshabilitarArea($codigoArea)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_deshabilitar_area :codigoArea";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoArea', $codigoArea, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $auditoria = new AuditoriaModel($conector);
          $auditoria->registrarEvento('AREA', 'Deshabilitar de área');
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al deshabilitar area: " . $e->getMessage());
    }
  }
}
