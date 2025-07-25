<?php
echo "<h2>Test de Conexión y Base de Datos</h2>";

// Test de conexión manual
$servername = "localhost";
$username = "root";
$password = "12345";
$dbname = "cmdb";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        echo "<p>❌ Error de conexión: " . $conn->connect_error . "</p>";
        exit;
    }
    
    echo "<p>✓ Conexión exitosa a la base de datos</p>";
    
    // Verificar qué tablas existen
    echo "<h3>Tablas en la base de datos:</h3>";
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        while ($row = $result->fetch_array()) {
            echo "<p>- " . $row[0] . "</p>";
        }
    }
    
    // Verificar estructura de la tabla categorias
    echo "<h3>Estructura de la tabla categorias:</h3>";
    $result = $conn->query("DESCRIBE categorias");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>Campo: " . $row['Field'] . " - Tipo: " . $row['Type'] . "</p>";
        }
    } else {
        echo "<p>❌ Error: " . $conn->error . "</p>";
    }
    
    // Verificar datos en categorias
    echo "<h3>Datos en la tabla categorias:</h3>";
    $result = $conn->query("SELECT * FROM categorias");
    if ($result) {
        echo "<p>Total de registros: " . $result->num_rows . "</p>";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p>ID: " . $row['id'] . " - Nombre: " . $row['nombre'] . "</p>";
            }
        }
    } else {
        echo "<p>❌ Error consultando categorias: " . $conn->error . "</p>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Test con clase Conexion:</h3>";

require_once 'conexion.php';

try {
    $conexion = new Conexion();
    $categorias = $conexion->obtenerCategorias();
    echo "<p>Categorías desde clase Conexion: " . count($categorias) . "</p>";
    if (count($categorias) > 0) {
        foreach ($categorias as $cat) {
            echo "<p>- " . $cat['nombre'] . "</p>";
        }
    }
} catch (Exception $e) {
    echo "<p>❌ Error con clase Conexion: " . $e->getMessage() . "</p>";
}
?>
