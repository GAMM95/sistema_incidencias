<?php
require_once '../config/conexion.php';

class DeshabilitarUsuario extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function deshabilitarUsuario($codigoUsuario)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_deshabilitarUsuario :codigoUsuario";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':codigoUsuario', $codigoUsuario, PDO::PARAM_INT);
        $stmt->execute();
        // Confirmar que se ha actualizado al menos una fila
        if ($stmt->rowCount() > 0) {
          return true;
        } else {
          return false;
        }
      } else {
        throw new Exception("Error de conexion a la base de datos");
      }
    } catch (PDOException $e) {
      return false;
    }
  }
}

// Obtener el código patrimonial del parámetro GET
$codigoUsuario = isset($_POST['codigoUsuario']) ? $_POST['codigoUsuario'] : '';

// Instanciar la clase y obtener el tipo de bien
$usuario = new DeshabilitarUsuario();
$estado = $usuario->deshabilitarUsuario($codigoUsuario);

// Devolver una respuesta JSON
header('Content-Type: application/json');
echo json_encode(['success' => $estado]);
