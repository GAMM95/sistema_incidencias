<?php
require_once '../config/conexion.php';

class PersonaAuditoria extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getPersonaAuditoria()
  {
    $conector = parent::getConexion();
    $query = "SELECT u.USU_codigo, p.PER_codigo, 
            -- (USU_nombre + '  ' + '-'+ '  '+ PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno) AS persona 
            (PER_nombres + ' ' + PER_apellidoPaterno + ' ' + PER_apellidoMaterno) AS persona 
            FROM PERSONA p
            INNER JOIN USUARIO u ON u.PER_codigo = p.PER_codigo
            ORDER BY persona ASC";
    $stmt = $conector->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
  }
}

$personaAuditoria = new PersonaAuditoria();
$personas = $personaAuditoria->getPersonaAuditoria();

header('Content-Type: application/json');
echo json_encode($personas);
exit();