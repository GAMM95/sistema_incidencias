<?php
require_once '../config/conexion.php';

class TipoBienReporte extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getTipoBienReporte($codigoPatrimonial)
  {
    try {
      $conector = parent::getConexion();
      // Consulta simplificada para buscar en la tabla BIEN
      $query = "SELECT B.BIE_nombre AS TipoBien
      FROM INCIDENCIA I
      JOIN BIEN B ON LEFT(I.INC_codigoPatrimonial, 8) = B.BIE_codigoIdentificador
      WHERE I.INC_codigoPatrimonial = :codigoPatrimonial";

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
$codigoPatrimonial = isset($_GET['codigoPatrimonial']) ? $_GET['codigoPatrimonial'] : '';

// Instanciar la clase y obtener el tipo de bien
$tipoBien = new TipoBienReporte();
$bien = $tipoBien->getTipoBienReporte($codigoPatrimonial);

// Enviar la respuesta JSON
header('Content-Type: application/json');
echo json_encode(['tipo_bien' => $bien]);
