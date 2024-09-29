<?php
require_once '../config/conexion.php';

class PrioridadModel extends Conexion
{
    public function __construct()
    {
        parent::__construct();
    }

    // Metodo para cargar prioridades
    public function getPrioridadData()
    {
        $conector = parent::getConexion();
        $query = "SELECT * FROM PRIORIDAD
        order by PRI_codigo";
        $stmt = $conector->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        return $resultado;
    }
}

$prioridadModel = new PrioridadModel();
$prioridades = $prioridadModel->getPrioridadData();

header('Content-Type: application/json');
echo json_encode($prioridades);
