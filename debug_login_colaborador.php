<?php
require_once 'conexion.php';
require_once 'sanitizardatos.php';

echo "<h2>Test Detallado: Login de Colaboradores</h2>";

// Simular el proceso de validar_login.php paso a paso
$test_correo = "juan.perez@test.com"; // Cambiar por un correo real
$test_password = "123456";

echo "<h3>Simulando el proceso de validar_login.php</h3>";
echo "<p><strong>Correo de prueba:</strong> $test_correo</p>";
echo "<p><strong>Contraseña de prueba:</strong> $test_password</p>";

try {
    $conexion = new Conexion();
    
    // Paso 1: Sanitizar datos (como en validar_login.php)
    echo "<h4>1. Sanitización de datos:</h4>";
    $usuario = SanitizarDatos::sanitizarTexto($test_correo);
    $contrasena = $test_password; // No se sanitiza la contraseña
    
    echo "<p>Usuario sanitizado: '$usuario'</p>";
    echo "<p>Contraseña (sin sanitizar): '$contrasena'</p>";
    
    // Paso 2: Validaciones básicas
    echo "<h4>2. Validaciones básicas:</h4>";
    if (empty($usuario) || empty($contrasena)) {
        echo "<p>❌ Usuario o contraseña vacíos</p>";
        exit;
    } else {
        echo "<p>✅ Usuario y contraseña no están vacíos</p>";
    }
    
    if (!SanitizarDatos::validarLongitud($usuario, 3, 50)) {
        echo "<p>❌ Usuario debe tener entre 3 y 50 caracteres</p>";
        exit;
    } else {
        echo "<p>✅ Longitud de usuario válida: " . strlen($usuario) . " caracteres</p>";
    }
    
    if (!SanitizarDatos::validarLongitud($contrasena, 4, 255)) {
        echo "<p>❌ Contraseña no válida</p>";
        exit;
    } else {
        echo "<p>✅ Longitud de contraseña válida: " . strlen($contrasena) . " caracteres</p>";
    }
    
    // Paso 3: Intentar como usuario/admin primero
    echo "<h4>3. Intentando validar como usuario/admin:</h4>";
    try {
        $user = $conexion->validarUsuario($usuario, $contrasena);
        if ($user) {
            echo "<p>✅ Se encontró como usuario/admin: " . $user['nombre'] . " (Rol: " . $user['rol'] . ")</p>";
            echo "<p>⚠️ Se detendría aquí y no intentaría como colaborador</p>";
            exit;
        } else {
            echo "<p>❌ No se encontró como usuario/admin</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error validando usuario: " . $e->getMessage() . "</p>";
    }
    
    // Paso 4: Intentar como colaborador
    echo "<h4>4. Intentando validar como colaborador:</h4>";
    
    // Primero verificar que existe el colaborador
    echo "<p><strong>4.1 Buscando colaborador en BD:</strong></p>";
    $stmt = $conexion->getConexion()->prepare("
        SELECT c.*, d.nombre as departamento_nombre 
        FROM colaboradores c
        LEFT JOIN departamentos d ON c.departamento_id = d.id
        WHERE c.correo = ? AND c.activo = 1
    ");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $colaborador = $result->fetch_assoc();
    $stmt->close();
    
    if ($colaborador) {
        echo "<p>✅ Colaborador encontrado:</p>";
        echo "<ul>";
        echo "<li>ID: " . $colaborador['id'] . "</li>";
        echo "<li>Nombre: " . $colaborador['nombre'] . " " . $colaborador['apellido'] . "</li>";
        echo "<li>Correo: " . $colaborador['correo'] . "</li>";
        echo "<li>Activo: " . $colaborador['activo'] . "</li>";
        echo "<li>Departamento: " . ($colaborador['departamento_nombre'] ?? 'Sin asignar') . "</li>";
        echo "</ul>";
    } else {
        echo "<p>❌ No se encontró colaborador con ese correo o está inactivo</p>";
        
        // Mostrar todos los colaboradores para debug
        echo "<p><strong>Colaboradores disponibles:</strong></p>";
        $result = $conexion->getConexion()->query("SELECT id, nombre, apellido, correo, activo FROM colaboradores");
        while ($row = $result->fetch_assoc()) {
            $status = $row['activo'] ? 'Activo' : 'Inactivo';
            echo "<p>- {$row['correo']} ({$row['nombre']} {$row['apellido']}) - $status</p>";
        }
        exit;
    }
    
    // Buscar credenciales en tabla usuarios
    echo "<p><strong>4.2 Buscando credenciales en tabla usuarios:</strong></p>";
    $stmt = $conexion->getConexion()->prepare("
        SELECT contrasena 
        FROM usuarios 
        WHERE correo = ? AND rol = 'colab' AND activo = 1
    ");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario_cred = $result->fetch_assoc();
    $stmt->close();
    
    if ($usuario_cred) {
        echo "<p>✅ Credenciales encontradas en tabla usuarios</p>";
        echo "<p>Hash almacenado: " . substr($usuario_cred['contrasena'], 0, 30) . "...</p>";
        
        // Verificar contraseña
        echo "<p><strong>4.3 Verificando contraseña:</strong></p>";
        if (password_verify($contrasena, $usuario_cred['contrasena'])) {
            echo "<p>✅ ¡Contraseña correcta! El login debería funcionar</p>";
            
            // Test del método completo
            echo "<p><strong>4.4 Test del método validarColaborador completo:</strong></p>";
            $resultado_final = $conexion->validarColaborador($usuario, $contrasena);
            if ($resultado_final) {
                echo "<p>✅ ¡validarColaborador() funciona correctamente!</p>";
                echo "<pre>" . print_r($resultado_final, true) . "</pre>";
            } else {
                echo "<p>❌ validarColaborador() devolvió false - revisar método</p>";
            }
        } else {
            echo "<p>❌ Contraseña incorrecta</p>";
            echo "<p>Puedes generar una nueva contraseña:</p>";
            $nueva_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            echo "<p>UPDATE usuarios SET contrasena = '$nueva_hash' WHERE correo = '$usuario';</p>";
        }
    } else {
        echo "<p>❌ No se encontraron credenciales en tabla usuarios</p>";
        
        // Mostrar usuarios con rol colab
        echo "<p><strong>Usuarios con rol 'colab':</strong></p>";
        $result = $conexion->getConexion()->query("SELECT correo, nombre, rol, activo FROM usuarios WHERE rol = 'colab'");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row['activo'] ? 'Activo' : 'Inactivo';
                echo "<p>- {$row['correo']} ({$row['nombre']}) - Rol: {$row['rol']} - $status</p>";
            }
        } else {
            echo "<p>No hay usuarios con rol 'colab'</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error general: " . $e->getMessage() . "</p>";
}
?>

<h3>Recomendaciones:</h3>
<ul>
    <li>Verifica que el colaborador existe en la tabla `colaboradores`</li>
    <li>Verifica que existe un usuario con el mismo correo y rol 'colab' en la tabla `usuarios`</li>
    <li>Verifica que la contraseña esté hasheada correctamente</li>
    <li>Ambos registros deben estar activos (activo = 1)</li>
</ul>

<p><a href="Usuario/Login.php">Probar Login Real</a></p>
