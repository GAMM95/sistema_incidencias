<?php
require_once '../config/conexion.php';

class Estado extends Conexion
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEstadoIncidencia()
    {
        try {
            $conector = parent::getConexion();
            $query = "SELECT * FROM ESTADO 
            WHERE EST_codigo IN (3,4)";
            $stmt = $conector->prepare($query);
            $stmt->execute();
            $resultado = $stmt->fetchAll();
            return $resultado;
        } catch (PDOException $e) {
            error_log('Error en getEstadoIncidencia: ' . $e->getMessage());
            return []; // Devolver un array vacío en caso de error
        }
    }
}

$estadoModel = new Estado();
$estados = $estadoModel->getEstadoIncidencia();

header('Content-Type: application/json');
echo json_encode($estados);
