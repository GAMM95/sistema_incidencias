<?php
// require_once '../config/conexion.php';


// $db = (new Conexion())->getConexion();
// $query = "SELECT TOP 1 * FROM INCIDENCIA ORDER BY INC_numero DESC";

// $stmt = $db->prepare($query);
// $stmt->execute();
// $resultado = $stmt->fetch();
// if (!$resultado) {
//     $resultado = array('INC_numero' => 1);
// }
// else {
//     $resultado = array('INC_numero' => $resultado['INC_numero'] + 1);
// }
// header('Content-Type: application/json');
// echo json_encode($resultado);