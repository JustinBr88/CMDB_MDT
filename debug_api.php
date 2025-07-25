<?php
require_once 'conexion.php';

echo "<h2>Debug API Categorías</h2>";

try {
    $conexion = new Conexion();
    echo "<p>✓ Conexión creada</p>";
    
    $categorias = $conexion->obtenerCategorias();
    echo "<p>✓ Método obtenerCategorias() ejecutado</p>";
    echo "<p>Número de categorías: " . count($categorias) . "</p>";
    
    if (count($categorias) > 0) {
        echo "<h3>Categorías encontradas:</h3>";
        echo "<pre>" . print_r($categorias, true) . "</pre>";
        
        echo "<h3>JSON de salida:</h3>";
        echo "<pre>" . json_encode($categorias) . "</pre>";
    } else {
        echo "<p>No se encontraron categorías</p>";
        
        // Intentar consulta directa
        echo "<h3>Consulta directa:</h3>";
        $sql = "SELECT id, nombre, descripcion, fecha_creacion FROM categorias ORDER BY nombre ASC";
        $result = $conexion->getConexion()->query($sql);
        
        if ($result) {
            echo "<p>Consulta exitosa. Filas: " . $result->num_rows . "</p>";
            while ($row = $result->fetch_assoc()) {
                echo "<p>Fila: " . print_r($row, true) . "</p>";
            }
        } else {
            echo "<p>Error en consulta: " . $conexion->getConexion()->error . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
