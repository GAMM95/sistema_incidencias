<?php
require_once '../config/conexion.php';

class AnioModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    public function getAnio()
    {
        $query = "SELECT DISTINCT YEAR(FechaIncidencia) as Year
        FROM INCIDENCIA
        ORDER BY Year Asc";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        return $resultado;
    }
}

$anioModel = new AnioModel();
$anios = $anioModel->getAnio();

header('Content-Type: application/json');
echo json_encode($anios);