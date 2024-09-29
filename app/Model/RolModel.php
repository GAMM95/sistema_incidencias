<?php
// Importamos las credenciales y la clase de conexión
require_once 'config/conexion.php';
class RolModel extends Conexion
{
  protected $codigoRol;
  protected $nombreRol;

  public function __construct($codigoRol = null, $nombreRol = null)
  {
    parent::__construct();
    $this->codigoRol = $codigoRol;
    $this->nombreRol = $nombreRol;
  }

  // Metodo para registrar nuevo rol
  public function registrarRol()
  {
    if ($this->nombreRol === null || trim($this->nombreRol) === '') {
      throw new Exception("El nombre del rol no puede estar vacío.");
    }
    try {
      $conector = $this->getConexion();
      $sql = "INSERT INTO ROL (ROL_nombre) VALUES (?)";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$this->nombreRol]);
      return $conector->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception("Error al insertar el rol: " . $e->getMessage());
    }
  }

  // MEtodo para listarRoles
  public function listarRol()
  {
    try {
      $conector = $this->getConexion();
      $sql = "SELECT ROL_codigo, ROL_nombre FROM ROL ORDER BY ROL_codigo";
      $stmt = $conector->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener los roles: " . $e->getMessage());
    }
  }

  // Metodo para editar roles
  public function editarRol()
  {
    if ($this->codigoRol === null) {
      throw new Exception("El código del rol no puede ser nulo.");
    }

    if ($this->nombreRol === null || trim($this->nombreRol) === '') {
      throw new Exception("El nombre del rol no puede estar vacío.");
    }

    try {
      $conector = $this->getConexion();
      $sql = "UPDATE ROL SET ROL_nombre = ? WHERE ROL_codigo = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$this->nombreRol, $this->codigoRol]);
      return $stmt->rowCount();
    } catch (PDOException $e) {
      throw new Exception("Error al actualizar el rol: " . $e->getMessage());
    }
  }

  // Metodo para obtener rol por id
  public function obtenerRolPorId($codigoRol)
  {
    if ($codigoRol === null) {
      throw new Exception("El código del rol no puede ser nulo.");
    }

    try {
      $conector = $this->getConexion();
      $sql = "SELECT * FROM ROL WHERE ROL_codigo = ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute([$codigoRol]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener el rol: " . $e->getMessage());
    }
  }

  // Method to filter categories by a search term
  public function filtrarBusqueda($termino)
  {
    if ($termino === null || trim($termino) === '') {
      throw new Exception("El término de búsqueda no puede estar vacío.");
    }

    try {
      $conector = $this->getConexion();
      $sql = "SELECT * FROM ROL WHERE ROL_nombre LIKE ?";
      $stmt = $conector->prepare($sql);
      $stmt->execute(['%' . $termino . '%']);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al buscar rol: " . $e->getMessage());
    }
  }
}
