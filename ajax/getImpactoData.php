<?php
require_once '../config/conexion.php';

class ImpactoModel extends Conexion
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getImpactoData()
    {
        $conector = parent::getConexion();
        $query = "SELECT * FROM IMPACTO";
        $stmt = $conector->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        return $resultado;
    }
}
$impactoModel = new ImpactoModel();
$impactos = $impactoModel->getImpactoData();

header('Content-Type: application/json');
echo json_encode($impactos);
