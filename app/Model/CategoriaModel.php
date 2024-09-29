<?php
require_once 'config/conexion.php';

class CategoriaModel extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  // Metodo para insertar una nueva categoria
  public function insertarCategoria($nombreCategoria)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // $sql = "INSERT INTO CATEGORIA (CAT_nombre) VALUES (?)";
        $sql = "EXEC sp_registrarCategoria :nombreCategoria";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':nombreCategoria', $nombreCategoria);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al insertar la categoría: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para listar categorias
  public function listarCategorias()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT CAT_codigo, CAT_nombre, CAT_estado FROM CATEGORIA 
                ORDER BY CAT_codigo ASC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener las categorías: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para obtener la categoria por el ID
  public function obtenerCategoriaPorId($codigoCategoria)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM CATEGORIA 
                WHERE CAT_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoCategoria]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al obtener la categoría: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para editar categoria
  public function editarCategoria($nombreCategoria, $codigoCategoria)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "UPDATE CATEGORIA SET CAT_nombre = ? WHERE CAT_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([
          $nombreCategoria,
          $codigoCategoria
        ]);
        return $stmt->rowCount();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al actualizar la categoría: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para eliminar categoria
  public function eliminarCategoria($codigoCategoria)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "DELETE FROM CATEGORIA WHERE CAT_codigo = ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute([$codigoCategoria]);
        return $stmt->rowCount();
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al eliminar la categoría: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para filtrar categoria por termino de busqueda
  public function filtrarBusqueda($termino)
  {
    if ($termino === null || trim($termino) === '') {
      throw new Exception("El término de búsqueda no puede estar vacío.");
    }

    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM CATEGORIA WHERE CAT_nombre LIKE ?";
        $stmt = $conector->prepare($sql);
        $stmt->execute(['%' . $termino . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al buscar categorías: " . $e->getMessage());
      return null;
    }
  }

  // Metodo para contar la cantidad de categorias registradas
  public function contarCategorias()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "  SELECT COUNT(*) AS cantidadCategorias FROM CATEGORIA WHERE EST_codigo = 1";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cantidadCategorias'];
      } else {
        throw new Exception("Error de conexión con la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al contar categorias: " . $e->getMessage());
      return null;
    }
  }

  // Método para habilitar categoria
  public function habilitarCategoria($codigoCategoria)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_habilitarCategoria :codigoCategoria";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoCategoria', $codigoCategoria, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al habilitar categoria: " . $e->getMessage());
      return null;
    }
  }

  // METODO PARA DESHABILITAR categoria
  public function deshabilitarCategoria($codigoCategoria)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_deshabilitarCategoria :codigoCategoria";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoCategoria', $codigoCategoria, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al deshabilitar categoria: " . $e->getMessage());
    }
  }
}
