<?php
require_once '../config/conexion.php';

class RolModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    public function getRolData()
    {
        $query = "SELECT * FROM Rol";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        return $resultado;
    }
}

$rolModel = new RolModel();
$roles = $rolModel->getRolData();

header('Content-Type: application/json');
echo json_encode($roles);