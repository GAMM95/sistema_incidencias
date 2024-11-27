<?php
require_once '../config/conexion.php';

class UsuarioCierre extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getUsuarioCierre()
  {
    try {
      $conector = parent::getConexion();
      $query = "SELECT USU_codigo, 
      (PER_nombres + ' ' + PER_apellidoPaterno) as usuarioCierre
      FROM USUARIO u
      INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
      INNER JOIN AREA A ON A.ARE_codigo = U.ARE_codigo
      WHERE A.ARE_codigo = 1
      ORDER BY usuarioCierre ASC";
      $stmt = $conector->prepare($query);
      $stmt->execute();
      $resultado = $stmt->fetchAll();
       return $resultado;
    } catch (PDOException $e) {
      error_log('Error en getUsuarioCierre: ' . $e->getMessage());
      return 'Error';
    }
  }
}

// Instanciar la clase y obtener el usuario de cierre
$usuarioCierre = new UsuarioCierre();
$usuarios = $usuarioCierre->getUsuarioCierre();

// Enviar la respuesta JSON
header('Content-Type: application/json');
echo json_encode($usuarios);
