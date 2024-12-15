<?php
require_once '../config/conexion.php';

class UsuarioEvento extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getUsuarioEvento()
  {
    $conector = parent::getConexion();
    $query = "SELECT
              USU_codigo,
              p.PER_codigo,
              ( PER_nombres + ' ' + PER_apellidoPaterno ) AS usuario 
            FROM
              USUARIO u
              INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo 
            WHERE
              ROL_codigo IN (1,2)
            ORDER BY usuario ASC";
    $stmt = $conector->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$usuarioEvento = new UsuarioEvento();
$usuario = $usuarioEvento->getUsuarioEvento();

header('Content-Type: application/json');
echo json_encode($usuario);
exit();
