<?php
echo "<h2>Test de Debug: Conexión y Categorías</h2>";

// Test 1: Conexión directa a la base de datos
echo "<h3>1. Test de Conexión Directa</h3>";
$servername = "localhost";
$username = "root";
$password = "12345";
$dbname = "cmdb";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>✅ Conexión PDO exitosa</p>";
    
    // Verificar categorías con PDO
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM categorias");
    $result = $stmt->fetch();
    echo "<p>Total categorías en BD (PDO): " . $result['total'] . "</p>";
    
    if ($result['total'] > 0) {
        $stmt = $pdo->query("SELECT id, nombre, descripcion FROM categorias ORDER BY id");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<p>ID: {$row['id']} - Nombre: {$row['nombre']} - Descripción: {$row['descripcion']}</p>";
        }
    }
    
} catch(PDOException $e) {
    echo "<p>❌ Error PDO: " . $e->getMessage() . "</p>";
}

// Test 2: Conexión MySQLi directa
echo "<h3>2. Test de Conexión MySQLi Directa</h3>";
try {
    $mysqli = new mysqli($servername, $username, $password, $dbname);
    
    if ($mysqli->connect_error) {
        echo "<p>❌ Error MySQLi: " . $mysqli->connect_error . "</p>";
    } else {
        echo "<p>✅ Conexión MySQLi exitosa</p>";
        
        $result = $mysqli->query("SELECT COUNT(*) as total FROM categorias");
        $row = $result->fetch_assoc();
        echo "<p>Total categorías en BD (MySQLi): " . $row['total'] . "</p>";
        
        if ($row['total'] > 0) {
            $result = $mysqli->query("SELECT id, nombre, descripcion FROM categorias ORDER BY id");
            while ($row = $result->fetch_assoc()) {
                echo "<p>ID: {$row['id']} - Nombre: {$row['nombre']} - Descripción: {$row['descripcion']}</p>";
            }
        }
    }
    $mysqli->close();
} catch (Exception $e) {
    echo "<p>❌ Error MySQLi: " . $e->getMessage() . "</p>";
}

// Test 3: Usando la clase Conexion
echo "<h3>3. Test con Clase Conexion</h3>";
try {
    require_once 'conexion.php';
    $conexion = new Conexion();
    echo "<p>✅ Instancia de Conexion creada</p>";
    
    // Test del método obtenerCategorias
    $categorias = $conexion->obtenerCategorias();
    echo "<p>Categorías desde obtenerCategorias(): " . count($categorias) . "</p>";
    
    if (count($categorias) > 0) {
        foreach ($categorias as $cat) {
            echo "<p>ID: {$cat['id']} - Nombre: {$cat['nombre']} - Descripción: " . ($cat['descripcion'] ?? 'Sin descripción') . "</p>";
        }
    } else {
        echo "<p>❌ No se obtuvieron categorías con la clase Conexion</p>";
        
        // Test directo con la conexión de la clase
        $conn = $conexion->getConexion();
        $result = $conn->query("SELECT COUNT(*) as total FROM categorias");
        $row = $result->fetch_assoc();
        echo "<p>Test directo con conexión de la clase - Total: " . $row['total'] . "</p>";
        
        if ($row['total'] > 0) {
            $result = $conn->query("SELECT id, nombre, descripcion FROM categorias");
            echo "<p>Consultando directamente:</p>";
            while ($row = $result->fetch_assoc()) {
                echo "<p>- ID: {$row['id']} - Nombre: {$row['nombre']}</p>";
            }
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error con clase Conexion: " . $e->getMessage() . "</p>";
}

// Test 4: Verificar estructura de tabla
echo "<h3>4. Test de Estructura de Tabla</h3>";
try {
    $mysqli = new mysqli($servername, $username, $password, $dbname);
    $result = $mysqli->query("DESCRIBE categorias");
    echo "<p>Estructura de tabla categorias:</p>";
    while ($row = $result->fetch_assoc()) {
        echo "<p>- {$row['Field']}: {$row['Type']}</p>";
    }
    $mysqli->close();
} catch (Exception $e) {
    echo "<p>❌ Error verificando estructura: " . $e->getMessage() . "</p>";
}
?>
