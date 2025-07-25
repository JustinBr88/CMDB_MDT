<?php
require_once '../conexion.php';

echo "<h2>Test de Categorías en Inventario</h2>";

try {
    $conexion = new Conexion();
    echo "<p>✅ Conexión creada exitosamente</p>";
    
    $categorias = $conexion->obtenerCategorias();
    echo "<p>Número de categorías obtenidas: " . count($categorias) . "</p>";
    
    if (count($categorias) > 0) {
        echo "<h3>Categorías encontradas:</h3>";
        echo "<ul>";
        foreach ($categorias as $cat) {
            echo "<li>ID: " . $cat['id'] . " - Nombre: " . htmlspecialchars($cat['nombre']) . " - Descripción: " . htmlspecialchars($cat['descripcion'] ?? 'Sin descripción') . "</li>";
        }
        echo "</ul>";
        
        echo "<h3>JSON para JavaScript:</h3>";
        $categorias_js = [];
        foreach ($categorias as $cat) {
            $categorias_js[] = [
                'id' => (int)$cat['id'],
                'nombre' => htmlspecialchars($cat['nombre'], ENT_QUOTES, 'UTF-8')
            ];
        }
        echo "<pre>" . json_encode($categorias_js, JSON_PRETTY_PRINT | JSON_HEX_QUOT | JSON_HEX_APOS) . "</pre>";
    } else {
        echo "<p>❌ No se encontraron categorías</p>";
        
        // Test directo
        echo "<h3>Test directo con la conexión:</h3>";
        $conn = $conexion->getConexion();
        $result = $conn->query("SELECT COUNT(*) as total FROM categorias");
        $row = $result->fetch_assoc();
        echo "<p>Total en BD: " . $row['total'] . "</p>";
        
        if ($row['total'] > 0) {
            $result = $conn->query("SELECT id, nombre, descripcion FROM categorias ORDER BY nombre");
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>ID: " . $row['id'] . " - Nombre: " . $row['nombre'] . "</li>";
            }
            echo "</ul>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>

<p><a href="Inventario.php">Volver al Inventario</a></p>
