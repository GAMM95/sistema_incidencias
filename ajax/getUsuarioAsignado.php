<?php
require_once '../config/conexion.php';

class UsuarioAsignado extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getUsuarioAsignado()
  {
    try {
      $conector = parent::getConexion();
      // Consulta simplificada para buscar en la tabla BIEN
      $query = "SELECT USU_codigo, 
      (PER_nombres + ' ' + PER_apellidoPaterno) as usuarioAsignado
      FROM USUARIO u
      INNER JOIN PERSONA p ON p.PER_codigo = u.PER_codigo
      WHERE ROL_codigo NOT IN (1,3) AND
      EST_codigo <> 2";
      $stmt = $conector->prepare($query);
      $stmt->execute();
      $resultado = $stmt->fetchAll();
       return $resultado;
    } catch (PDOException $e) {
      error_log('Error en getUsuarioAsignado: ' . $e->getMessage());
      return 'Error';
    }
  }
}

// Instanciar la clase y obtener el tipo de bien
$usuarioAsignadoModel = new UsuarioAsignado();
$usuarios = $usuarioAsignadoModel->getUsuarioAsignado();

// Enviar la respuesta JSON
header('Content-Type: application/json');
echo json_encode($usuarios);
