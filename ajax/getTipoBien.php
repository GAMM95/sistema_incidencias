<?php
require_once '../config/conexion.php';

class TipoBien extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getTipoBien($codigoPatrimonial)
  {
    try {
      $conector = parent::getConexion();
      // Consulta simplificada para buscar en la tabla BIEN
      $query = "SELECT BIE_nombre AS Tipo_de_Bien
                FROM BIEN
                WHERE BIE_codigoIdentificador = :codigoPatrimonial";

      $stmt = $conector->prepare($query);
      $stmt->bindParam(':codigoPatrimonial', $codigoPatrimonial, PDO::PARAM_STR);
      $stmt->execute();
      $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
      return $resultado ? $resultado['Tipo_de_Bien'] : 'No encontrado';
    } catch (PDOException $e) {
      error_log('Error en getTipoBien: ' . $e->getMessage());
      return 'Error';
    }
  }
}

// Obtener el código patrimonial del parámetro GET
$codigoPatrimonial = isset($_GET['codigo_patrimonial']) ? $_GET['codigo_patrimonial'] : '';

// Instanciar la clase y obtener el tipo de bien
$tipoBien = new TipoBien();
$bien = $tipoBien->getTipoBien($codigoPatrimonial);

// Enviar la respuesta JSON
header('Content-Type: application/json');
echo json_encode(['tipo_bien' => $bien]);
