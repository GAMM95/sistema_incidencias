<?php
require_once '../config/conexion.php';

class Estado
{
    private $conector;

    public function __construct()
    {
        $this->conector = (new Conexion())->getConexion();
    }

    public function getEstadoData()
    {
        $query = "SELECT * FROM Estado";
        $stmt = $this->conector->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        return $resultado;
    }
}

$estadoModel = new Estado();
$estados = $estadoModel->getEstadoData();

header('Content-Type: application/json');
echo json_encode($estados);