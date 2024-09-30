<?php
require_once '../config/conexion.php';
// require_once 'app/Model/AuditoriaModel.php';

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
        $sql = "EXEC sp_deshabilitar_usuario :codigoUsuario";
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
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al deshabilitar usuario: " . $e->getMessage());
      return null;
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
