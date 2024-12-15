<?php
require_once '../config/conexion.php';

class PersonaAuditoriaAdmin extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getPersonaAuditoriaAdmin()
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
              ROL_codigo =  1
            ORDER BY usuario ASC";
    $stmt = $conector->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$personaAuditoriaAdmin = new PersonaAuditoriaAdmin();
$personas = $personaAuditoriaAdmin->getPersonaAuditoriaAdmin();

header('Content-Type: application/json');
echo json_encode($personas);
exit();