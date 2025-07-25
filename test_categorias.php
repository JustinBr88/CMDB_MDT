<?php
require_once 'conexion.php';

echo "<h2>Test de Categorías</h2>";

try {
    $conexion = new Conexion();
    echo "<p>Conexión establecida correctamente</p>";
    
    // Probar consulta directa
    $sql = "SELECT id, nombre, descripcion FROM categorias ORDER BY nombre ASC";
    $result = $conexion->getConexion()->query($sql);
    
    if ($result) {
        echo "<p>Consulta ejecutada. Número de filas: " . $result->num_rows . "</p>";
        
        if ($result->num_rows > 0) {
            echo "<h3>Categorías encontradas:</h3>";
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>ID: " . $row['id'] . " - Nombre: " . $row['nombre'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No se encontraron categorías en la base de datos</p>";
        }
    } else {
        echo "<p>Error en la consulta: " . $conexion->getConexion()->error . "</p>";
    }
    
    // Probar el método obtenerCategorias
    echo "<h3>Test del método obtenerCategorias():</h3>";
    $categorias = $conexion->obtenerCategorias();
    echo "<p>Categorías devueltas por el método: " . count($categorias) . "</p>";
    echo "<pre>" . print_r($categorias, true) . "</pre>";
    
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
