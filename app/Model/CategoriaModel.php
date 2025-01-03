<?php
require_once 'config/conexion.php';
require_once 'app/Model/AuditoriaModel.php';

class CategoriaModel extends Conexion
{
  private $auditoria;

  public function __construct()
  {
    parent::__construct();
    $conector = parent::getConexion();
    // Inicializar la instancia de AuditoriaModel
    if ($conector != null) {
      $this->auditoria = new AuditoriaModel($conector);
    }
  }

  // Metodo para obtener el ultimo codigo registrado de categoria
  private function obtenerUltimoCodigoCategoria()
  {
    try {
      $conector = $this->getConexion();
      if ($conector != null) {
        $sql = "SELECT MAX(CAT_codigo) AS ultimoCodigo FROM CATEGORIA";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['ultimoCodigo'];
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el último código de categoria: " . $e->getMessage());
    }
  }

  // Metodo para insertar una nueva categoria
  public function insertarCategoria($nombreCategoria)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_registrar_categoria :nombreCategoria";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':nombreCategoria', $nombreCategoria);
        $stmt->execute();

        // Obtener el ID de la categoría recién insertada
        $categoriaID = $this->obtenerUltimoCodigoCategoria();

        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $this->auditoria->registrarEvento('CATEGORIA', 'Registrar categoría', $categoriaID);
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
        $sql = "SELECT * FROM vw_categorias 
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
        $sql = "EXEC sp_editar_categoria :nombreCategoria, :codigoCategoria";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':nombreCategoria', $nombreCategoria, PDO::PARAM_STR);
        $stmt->bindParam(':codigoCategoria', $codigoCategoria, PDO::PARAM_INT);
        $stmt->execute();

        // Registrar el evento en la auditoría
        $this->auditoria->registrarEvento('CATEGORIA', 'Actualizar categoría', $codigoCategoria);

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
        $sql = "EXEC sp_habilitar_categoria :codigoCategoria";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoCategoria', $codigoCategoria, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $this->auditoria->registrarEvento('CATEGORIA', 'Habilitar categoría', $codigoCategoria);
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
        $sql = "EXEC sp_deshabilitar_categoria :codigoCategoria";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoCategoria', $codigoCategoria, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          // Registrar el evento en la auditoría
          $this->auditoria->registrarEvento('CATEGORIA', 'Deshabilitar categoría', $codigoCategoria);
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

  // Metodo para listar eventos de categorias
  public function listarEventosCategorias()
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "SELECT * FROM vw_eventos_categorias
            ORDER BY AUD_fecha DESC, AUD_hora DESC";
        $stmt = $conector->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
      }
    } catch (PDOException $e) {
      throw new Exception("Error al listar eventos de categorias en la tabla de auditoria: " . $e->getMessage());
    }
  }

  // Metodo para consultar eventos categorias - auditoria
  public function buscarEventosCategorias($usuario, $fechaInicio, $fechaFin)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_consultar_eventos_categorias :usuario, :fechaInicio, :fechaFin";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':fechaFin', $fechaFin);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
      } else {
        throw new Exception("Error de conexión a la base de datos.");
        return null;
      }
    } catch (PDOException $e) {
      throw new Exception("Error al consultar eventos de categorias en la tabla de auditoria: " . $e->getMessage());
      return null;
    }
  }
}
