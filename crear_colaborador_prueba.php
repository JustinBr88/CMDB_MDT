<?php
require_once 'conexion.php';

echo "<h2>Crear Colaborador de Prueba</h2>";

try {
    $conexion = new Conexion();
    
    // Datos del colaborador de prueba
    $nombre = "Juan";
    $apellido = "Pérez";
    $identificacion = "12345678";
    $correo = "juan.perez@test.com";
    $password = "123456"; // Contraseña simple para pruebas
    
    echo "<h3>Creando colaborador de prueba:</h3>";
    echo "<p>Nombre: $nombre $apellido</p>";
    echo "<p>Correo: $correo</p>";
    echo "<p>Contraseña: $password</p>";
    
    // Verificar si ya existe
    $stmt = $conexion->getConexion()->prepare("SELECT id FROM colaboradores WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<p>⚠️ Ya existe un colaborador con este correo</p>";
    } else {
        // 1. Insertar en la tabla colaboradores
        echo "<p>1. Insertando en tabla colaboradores...</p>";
        $stmt = $conexion->getConexion()->prepare("
            INSERT INTO colaboradores (nombre, apellido, identificacion, correo, activo) 
            VALUES (?, ?, ?, ?, 1)
        ");
        $stmt->bind_param("ssss", $nombre, $apellido, $identificacion, $correo);
        
        if ($stmt->execute()) {
            echo "<p>✅ Colaborador insertado exitosamente</p>";
        } else {
            echo "<p>❌ Error insertando colaborador: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    
    // Verificar si ya existe usuario
    $stmt = $conexion->getConexion()->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<p>⚠️ Ya existe un usuario con este correo</p>";
    } else {
        // 2. Insertar en la tabla usuarios (credenciales)
        echo "<p>2. Insertando credenciales en tabla usuarios...</p>";
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conexion->getConexion()->prepare("
            INSERT INTO usuarios (nombre, correo, contrasena, rol, activo) 
            VALUES (?, ?, ?, 'colab', 1)
        ");
        $nombre_completo = "$nombre $apellido";
        $stmt->bind_param("sss", $nombre_completo, $correo, $password_hash);
        
        if ($stmt->execute()) {
            echo "<p>✅ Usuario insertado exitosamente</p>";
            echo "<p><strong>Hash generado:</strong> " . substr($password_hash, 0, 30) . "...</p>";
        } else {
            echo "<p>❌ Error insertando usuario: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    
    // 3. Probar el login
    echo "<h3>3. Probando login con el nuevo colaborador:</h3>";
    $resultado = $conexion->validarColaborador($correo, $password);
    
    if ($resultado) {
        echo "<p>✅ ¡Login exitoso!</p>";
        echo "<p>Datos del colaborador:</p>";
        echo "<ul>";
        echo "<li>ID: " . $resultado['id'] . "</li>";
        echo "<li>Nombre: " . $resultado['nombre'] . " " . $resultado['apellido'] . "</li>";
        echo "<li>Correo: " . $resultado['correo'] . "</li>";
        echo "<li>Departamento: " . ($resultado['departamento_nombre'] ?? 'Sin asignar') . "</li>";
        echo "</ul>";
    } else {
        echo "<p>❌ Login falló - verificar configuración</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>

<h3>Ahora puedes probar el login:</h3>
<p><strong>Correo:</strong> juan.perez@test.com</p>
<p><strong>Contraseña:</strong> 123456</p>
<p><a href="Usuario/Login.php" class="btn btn-primary">Probar Login</a></p>

<script>
// Limpiar este archivo después de usarlo
setTimeout(function() {
    if (confirm('¿Quieres eliminar este archivo de prueba por seguridad?')) {
        fetch('<?php echo $_SERVER["PHP_SELF"]; ?>', {
            method: 'DELETE'
        });
    }
}, 5000);
</script>
