<?php
require_once '../config/conexion.php';

class HabilitarUsuario extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  public function habilitarUsuario($codigoUsuario)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        $sql = "EXEC sp_habilitar_usuario :codigoUsuario";
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
      throw new PDOException("Error al habilitar usuario: " . $e->getMessage());
      return null;
    }
  }
}

// Obtener el código del parámetro POST
$codigoUsuario = isset($_POST['codigoUsuario']) ? $_POST['codigoUsuario'] : '';

// Instanciar la clase y actualizar el estado del usuario
$usuario = new HabilitarUsuario();
$estado = $usuario->habilitarUsuario($codigoUsuario);

// Devolver una respuesta JSON
header('Content-Type: application/json');
echo json_encode(['success' => $estado]);
