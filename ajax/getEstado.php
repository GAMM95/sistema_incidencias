<?php
require_once '../config/conexion.php';

class Estado extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getEstadoData()
  {
    try {
      $conector = parent::getConexion();
      $query = "SELECT * FROM Estado";
      $stmt = $conector->prepare($query);
      $stmt->execute();
      $resultado = $stmt->fetchAll();
      return $resultado;
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }
}

$estadoModel = new Estado();
$estados = $estadoModel->getEstadoData();

header('Content-Type: application/json');
echo json_encode($estados);
