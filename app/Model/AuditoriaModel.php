<?php
require_once 'config/conexion.php';

class AuditoriaModel extends Conexion
{
  public function __construct()
  {
    parent::__construct();
  }

  // Método para registrar un evento de auditoría
  public function registrarEvento($tabla, $operacion)
  {
    $conector = parent::getConexion();
    try {
      if ($conector != null) {
        // Obtener IP del cliente y nombre del equipo
        $ipCliente = $this->obtenerIP();
        $nombreEquipo = gethostbyaddr($ipCliente);

        // Capturar el usuario logueado desde la sesión
        $usuario = isset($_SESSION['codigoUsuario']) ? $_SESSION['codigoUsuario'] : null;

        // Insertar en la tabla AUDITORIA
        $sql = "INSERT INTO AUDITORIA (AUD_fecha, AUD_hora, AUD_usuario, AUD_tabla, AUD_operacion, AUD_ip, AUD_nombreEquipo) 
                    VALUES (GETDATE(), CONVERT(TIME, GETDATE()), :usuario, :tabla, :operacion, :ipCliente, :nombreEquipo)";
        $stmt = $conector->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':tabla', $tabla);
        $stmt->bindParam(':operacion', $operacion);
        $stmt->bindParam(':ipCliente', $ipCliente);
        $stmt->bindParam(':nombreEquipo', $nombreEquipo);
        $stmt->execute();
        return true;
      } else {
        throw new Exception("Error de conexión a la base de datos");
        return null;
      }
    } catch (PDOException $e) {
      throw new PDOException("Error al registrar en la auditoría: " . $e->getMessage());
      return null;
    }
  }


  // Metodo para obtener la ip del equipo
  private function obtenerIP()
  {
    // Comprobar si hay proxies
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      // En caso de que esté detrás de un proxy
      $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    // Validar que la IP sea válida (IPv4 o IPv6)
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
      return $ip; // Si es IPv4
    } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
      return $ip; // Si es IPv6
    } else {
      // Devolver una dirección IP por defecto si no es válida
      return 'IP no válida';
    }
  }
}
