<?php
require_once '../config/conexion.php';

class PersonaModel extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getPersonaData()
  {
    $conector = parent::getConexion();
    $query = "SELECT PER_codigo, (PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno) AS persona 
              FROM PERSONA ORDER BY persona ASC";
    $stmt = $conector->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$personaModel = new PersonaModel();
$personas = $personaModel->getPersonaData();

header('Content-Type: application/json');
echo json_encode($personas);
