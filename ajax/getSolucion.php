<?php
require_once '../config/conexion.php';

class SolucionModel extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  // Metodo para cargar operatividad
  public function getSolucionData()
  {
    $conector = parent::getConexion();
    $query = "SELECT * FROM SOLUCION 
      ORDER BY SOL_codigo";
    $stmt = $conector->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll(); 
    return $resultado;
  }
}

$solucionModel = new SolucionModel();
$soluciones = $solucionModel->getSolucionData();

header('Content-Type: application/json');
echo json_encode($soluciones);
