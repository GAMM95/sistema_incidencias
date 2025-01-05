<?php
require_once '../config/conexion.php';

class CategoryModel extends Conexion
{

  public function __construct()
  {
    parent::__construct();
  }

  // Método para obtener datos de categorías
  public function getCategoryData()
  {
    try {
      $conector = parent::getConexion();
      $query = "SELECT * FROM CATEGORIA WHERE EST_codigo <> 2";
      $stmt = $conector->prepare($query);
      $stmt->execute();
      $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $categorias;
    } catch (PDOException $e) {
      error_log('Error en getCategoryData: ' . $e->getMessage());
      return [];
    }
  }
}

// Instanciar el modelo y obtener datos de categorías
$categoryModel = new CategoryModel();
$categories = $categoryModel->getCategoryData();

// Devolver datos como JSON
header('Content-Type: application/json');
echo json_encode($categories);
