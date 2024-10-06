<?php
require 'settings.php';

class Conexion
{
  private $conector = null;

  /**
   * Este método permite obtener una conexión a la base de datos de SQL SERVER, utilizando los valores de configuración.
   * @return PDO|null Retorna el objeto de conexión PDO si la conexión es exitosa, o null en caso contrario.
   */
  public function __construct()
  {
    try {
      // Crea una nueva instancia de PDO.
      $this->conector = new PDO("sqlsrv:server=" . SERVIDOR . ";database=" . DATABASE, USUARIO, PASSWORD);
      // Establece el modo de error de PDO a excepción
      $this->conector->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      // En caso de error, muestra un mensaje de error y devuelve null
      echo "Error de conexión a la base de datos: " . $e->getMessage();
      return null;
    }
  }

  public function getConexion()
  {
    // Devuelve el objeto de conexión PDO.
    return $this->conector;
  }
}
