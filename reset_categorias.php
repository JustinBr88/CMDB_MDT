<?php
require_once 'conexion.php';

echo "<h2>Reset y Configuración de Categorías</h2>";

try {
    $conexion = new Conexion();
    
    // Limpiar categorías existentes
    echo "<p>Limpiando categorías existentes...</p>";
    $conexion->getConexion()->query("DELETE FROM categorias");
    
    // Reiniciar el auto_increment
    $conexion->getConexion()->query("ALTER TABLE categorias AUTO_INCREMENT = 1");
    
    // Insertar categorías con IDs específicos
    echo "<p>Insertando categorías con IDs específicos...</p>";
    
    $categorias = [
        [1, 'Hardware', 'Equipos físicos de cómputo'],
        [2, 'Software', 'Programas y aplicaciones'],
        [3, 'Periféricos', 'Dispositivos de entrada y salida'],
        [4, 'Redes', 'Equipos de conectividad y comunicación'],
        [5, 'Móviles', 'Dispositivos móviles y tablets'],
        [6, 'Servidores', 'Equipos servidor y almacenamiento']
    ];
    
    $stmt = $conexion->getConexion()->prepare("INSERT INTO categorias (id, nombre, descripcion) VALUES (?, ?, ?)");
    
    foreach ($categorias as $cat) {
        $stmt->bind_param("iss", $cat[0], $cat[1], $cat[2]);
        if ($stmt->execute()) {
            echo "<p>✓ Categoría '{$cat[1]}' insertada con ID {$cat[0]}</p>";
        } else {
            echo "<p>✗ Error: " . $stmt->error . "</p>";
        }
    }
    
    $stmt->close();
    
    // Verificar resultado
    echo "<h3>Verificación final:</h3>";
    $categorias_result = $conexion->obtenerCategorias();
    echo "<p>Total de categorías: " . count($categorias_result) . "</p>";
    
    foreach ($categorias_result as $cat) {
        echo "<p>ID: {$cat['id']} - Nombre: {$cat['nombre']}</p>";
    }
    
    echo "<p>✅ Configuración completada exitosamente</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>

<p><a href="api_categorias.php?action=getCategorias" target="_blank">Probar API de categorías</a></p>
<p><a href="Usuario/Inventario.php">Ir al inventario</a></p>
