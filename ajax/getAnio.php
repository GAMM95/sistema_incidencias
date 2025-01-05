<?php
require_once '../config/conexion.php';

class AnioModel extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getAnio()
  {
    try {
      $conector = parent::getConexion();
      $sql = "SELECT DISTINCT YEAR(INC_fecha) as YEAR
              FROM INCIDENCIA
              ORDER BY YEAR DESC";
      $stmt = $conector->prepare($sql);
      $stmt->execute();
      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $resultado;
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }
}

$anioModel = new AnioModel();
$anios = $anioModel->getAnio();

header('Content-Type: application/json');
echo json_encode($anios);
