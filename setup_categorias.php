<?php
require_once 'conexion.php';

echo "<h2>Insertar Categorías de Ejemplo</h2>";

try {
    $conexion = new Conexion();
    
    // Verificar si la tabla categorías existe
    $result = $conexion->getConexion()->query("SHOW TABLES LIKE 'categorias'");
    if ($result->num_rows == 0) {
        echo "<p>La tabla 'categorias' no existe. Creándola...</p>";
        
        $sql_create = "CREATE TABLE categorias (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            descripcion TEXT,
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($conexion->getConexion()->query($sql_create)) {
            echo "<p>Tabla 'categorias' creada exitosamente</p>";
        } else {
            echo "<p>Error creando tabla: " . $conexion->getConexion()->error . "</p>";
            exit;
        }
    } else {
        echo "<p>La tabla 'categorias' existe</p>";
    }
    
    // Verificar cuántas categorías hay
    $result = $conexion->getConexion()->query("SELECT COUNT(*) as total FROM categorias");
    $row = $result->fetch_assoc();
    $total_categorias = $row['total'];
    
    echo "<p>Categorías existentes: $total_categorias</p>";
    
    if ($total_categorias == 0) {
        echo "<p>Insertando categorías de ejemplo...</p>";
        
        $categorias_ejemplo = [
            [1, 'Hardware', 'Equipos físicos de cómputo'],
            [2, 'Software', 'Programas y aplicaciones'],
            [3, 'Periféricos', 'Dispositivos de entrada y salida'],
            [4, 'Redes', 'Equipos de conectividad y comunicación'],
            [5, 'Móviles', 'Dispositivos móviles y tablets'],
            [6, 'Servidores', 'Equipos servidor y almacenamiento']
        ];
        
        // Insertar con IDs específicos
        $stmt = $conexion->getConexion()->prepare("INSERT INTO categorias (id, nombre, descripcion) VALUES (?, ?, ?)");
        
        foreach ($categorias_ejemplo as $categoria) {
            $stmt->bind_param("iss", $categoria[0], $categoria[1], $categoria[2]);
            if ($stmt->execute()) {
                echo "<p>✓ Categoría '{$categoria[1]}' insertada con ID {$categoria[0]}</p>";
            } else {
                echo "<p>✗ Error insertando '{$categoria[1]}': " . $stmt->error . "</p>";
            }
        }
        
        $stmt->close();
        echo "<p>Proceso de inserción completado</p>";
    }
    
    // Mostrar todas las categorías
    echo "<h3>Categorías actuales:</h3>";
    $categorias = $conexion->obtenerCategorias();
    if (count($categorias) > 0) {
        echo "<ul>";
        foreach ($categorias as $cat) {
            echo "<li>ID: " . $cat['id'] . " - Nombre: " . $cat['nombre'] . " - Descripción: " . $cat['descripcion'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No se encontraron categorías</p>";
    }
    
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>

<p><a href="api_categorias.php?action=getCategorias" target="_blank">Probar API de categorías</a></p>
<p><a href="Usuario/Inventario.php">Volver al inventario</a></p>
