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
// $codigoPatrimonial = isset($_GET['codigo_patrimonial']) ? $_GET['codigoEquipo'] : '';
// Obtener el código patrimonial de los parámetros GET (prioridad a 'codigo_patrimonial' si ambos están presentes)
$codigoPatrimonial = '';
if (isset($_GET['codigo_patrimonial']) && !empty($_GET['codigo_patrimonial'])) {
  $codigoPatrimonial = $_GET['codigo_patrimonial'];
} elseif (isset($_GET['codigoEquipo']) && !empty($_GET['codigoEquipo'])) {
  $codigoPatrimonial = $_GET['codigoEquipo'];
}elseif (isset($_GET['codigoPatrimonial']) && !empty($_GET['codigoPatrimonial'])) {
  $codigoPatrimonial = $_GET['codigoPatrimonial'];
}

// Instanciar la clase y obtener el tipo de bien
$tipoBien = new TipoBien();
$bien = $tipoBien->getTipoBien($codigoPatrimonial);

// Enviar la respuesta JSON
header('Content-Type: application/json');
echo json_encode(['tipo_bien' => $bien]);
