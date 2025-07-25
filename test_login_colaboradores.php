<?php
require_once 'conexion.php';

echo "<h2>Test de Login de Colaboradores</h2>";

// Test de datos
$test_correo = "colaborador@test.com"; // Cambia esto por un correo real de colaborador
$test_password = "123456"; // Cambia esto por la contraseña real

try {
    $conexion = new Conexion();
    echo "<p>✅ Conexión establecida</p>";
    
    // 1. Verificar si existe el colaborador
    echo "<h3>1. Verificando colaboradores en la BD:</h3>";
    $stmt = $conexion->getConexion()->prepare("SELECT id, nombre, apellido, correo, activo FROM colaboradores");
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $status = $row['activo'] ? 'Activo' : 'Inactivo';
            echo "<p>- ID: {$row['id']} - {$row['nombre']} {$row['apellido']} - {$row['correo']} - {$status}</p>";
        }
    } else {
        echo "<p>❌ No hay colaboradores en la base de datos</p>";
    }
    $stmt->close();
    
    // 2. Verificar usuarios con rol colab
    echo "<h3>2. Verificando usuarios con rol 'colab':</h3>";
    $stmt = $conexion->getConexion()->prepare("SELECT id, nombre, correo, rol, activo FROM usuarios WHERE rol = 'colab'");
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $status = $row['activo'] ? 'Activo' : 'Inactivo';
            echo "<p>- ID: {$row['id']} - {$row['nombre']} - {$row['correo']} - Rol: {$row['rol']} - {$status}</p>";
        }
    } else {
        echo "<p>❌ No hay usuarios con rol 'colab' en la base de datos</p>";
    }
    $stmt->close();
    
    // 3. Test del método validarColaborador con datos reales
    echo "<h3>3. Test de validación de colaborador:</h3>";
    if ($result->num_rows > 0) {
        // Tomar el primer usuario colab para el test
        $stmt = $conexion->getConexion()->prepare("SELECT correo FROM usuarios WHERE rol = 'colab' AND activo = 1 LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $usuario_test = $result->fetch_assoc();
            echo "<p>Probando con correo: " . $usuario_test['correo'] . "</p>";
            echo "<p>⚠️ Para probar la contraseña, necesitas conocer la contraseña real del colaborador</p>";
            
            // Mostrar el hash almacenado
            $stmt2 = $conexion->getConexion()->prepare("SELECT contrasena FROM usuarios WHERE correo = ? AND rol = 'colab'");
            $stmt2->bind_param("s", $usuario_test['correo']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $hash_data = $result2->fetch_assoc();
            echo "<p>Hash almacenado: " . substr($hash_data['contrasena'], 0, 20) . "...</p>";
            $stmt2->close();
        }
        $stmt->close();
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>

<h3>4. Crear un colaborador de prueba:</h3>
<p>Si no tienes colaboradores, puedes crear uno manualmente en la base de datos:</p>
<pre>
-- 1. Insertar en la tabla colaboradores
INSERT INTO colaboradores (nombre, apellido, identificacion, correo, activo) 
VALUES ('Juan', 'Pérez', '12345678', 'juan.perez@test.com', 1);

-- 2. Insertar en la tabla usuarios (para las credenciales)
INSERT INTO usuarios (nombre, correo, contrasena, rol, activo) 
VALUES ('Juan Pérez', 'juan.perez@test.com', '$2y$10$ejemplo...', 'colab', 1);
</pre>

<p><a href="Usuario/Login.php">Probar Login</a></p>
