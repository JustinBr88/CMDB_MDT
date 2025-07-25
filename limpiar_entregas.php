<?php
require_once 'conexion.php';

try {
    $conexion = new Conexion();
    $mysqli = $conexion->getConexion();
    
    echo "<h2>🗑️ Eliminación de tabla entregas_colaborador</h2>";
    echo "<div style='background: #fff3cd; padding: 20px; margin: 20px; border-radius: 8px; border-left: 4px solid #ffc107;'>";
    
    // Verificar si la tabla existe
    $result = $mysqli->query("SHOW TABLES LIKE 'entregas_colaborador'");
    if ($result->num_rows > 0) {
        echo "<p style='color: orange;'>⚠️ La tabla 'entregas_colaborador' existe. Eliminándola...</p>";
        
        // Eliminar la tabla
        if ($mysqli->query("DROP TABLE entregas_colaborador")) {
            echo "<p style='color: green;'>✅ Tabla 'entregas_colaborador' eliminada exitosamente.</p>";
        } else {
            echo "<p style='color: red;'>❌ Error eliminando la tabla: " . $mysqli->error . "</p>";
        }
    } else {
        echo "<p style='color: green;'>✅ La tabla 'entregas_colaborador' no existe (ya eliminada).</p>";
    }
    
    // Mostrar las tablas existentes
    echo "<h3>📋 Tablas restantes en la base de datos:</h3>";
    $result = $mysqli->query("SHOW TABLES");
    echo "<ul>";
    while ($row = $result->fetch_array()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
    
    echo "</div>";
    echo "<p><a href='Usuario/Home.php' class='btn btn-primary'>Ir al Panel Administrativo</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

<script>
// Auto-eliminar este archivo después de 5 segundos
setTimeout(function() {
    if (confirm('¿Deseas eliminar este archivo de limpieza?')) {
        window.location.href = 'auto_delete_cleanup.php';
    }
}, 5000);
</script>
