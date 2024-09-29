<?php
require_once '../config/conexion.php';

class AreaModel extends Conexion
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAreaData()
    {
        $conector = parent::getConexion();
        $query = "SELECT * FROM AREA WHERE ARE_estado <> 2 
                ORDER BY ARE_nombre ASC";
        $stmt = $conector->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        return $resultado;
    }
}

$areaModel = new AreaModel();
$areas = $areaModel->getAreaData();

header('Content-Type: application/json');
echo json_encode($areas);