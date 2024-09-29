<?php
// require_once '../config/conexion.php';


// $db = (new Conexion())->getConexion();
// $query = "SELECT TOP 1 * FROM RECEPCION ORDER BY REC_codigo DESC";

// $stmt = $db->prepare($query);
// $stmt->execute();
// $resultado = $stmt->fetch();
// if (!$resultado) {
//     $resultado = array('REC_codigo' => 1);
// }
// else {
//     $resultado = array('REC_codigo' => $resultado['REC_codigo'] + 1);
// }
// header('Content-Type: application/json');
// echo json_encode($resultado);