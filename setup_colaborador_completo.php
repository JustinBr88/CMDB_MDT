<?php
require_once 'conexion.php';

// Este script crea un colaborador completo con registros en ambas tablas
$conexion = new Conexion();

// Datos del colaborador de prueba
$datos_colaborador = [
    'nombre' => 'Juan',
    'apellido' => 'Pérez',
    'identificacion' => 'C123456789',
    'direccion' => 'Calle Principal #123',
    'ubicacion' => 'Oficina A',
    'telefono' => '555-1234567',
    'correo' => 'juan.perez@test.com',
    'departamento_id' => 1
];

$datos_usuario = [
    'nombre' => 'juan.perez',  // Nombre de usuario para login
    'correo' => 'juan.perez@test.com',  // Mismo correo que el colaborador
    'contrasena' => '123456',  // Contraseña sin encriptar
    'rol' => 'colab'
];

try {
    // 1. Verificar si existe el departamento
    $departamento_query = "SELECT id FROM departamentos WHERE id = ?";
    $stmt = $conexion->conn->prepare($departamento_query);
    $stmt->bind_param("i", $datos_colaborador['departamento_id']);
    $stmt->execute();
    $dept_result = $stmt->get_result();
    
    if ($dept_result->num_rows == 0) {
        // Crear departamento si no existe
        $crear_dept = "INSERT INTO departamentos (nombre, ubicacion) VALUES ('Sistemas', 'Planta Baja')";
        $conexion->conn->query($crear_dept);
        echo "✓ Departamento 'Sistemas' creado<br>";
    }
    
    // 2. Verificar si ya existe el usuario
    $check_usuario = "SELECT id FROM usuarios WHERE correo = ?";
    $stmt = $conexion->conn->prepare($check_usuario);
    $stmt->bind_param("s", $datos_usuario['correo']);
    $stmt->execute();
    $user_result = $stmt->get_result();
    
    if ($user_result->num_rows > 0) {
        echo "⚠️ El usuario con correo {$datos_usuario['correo']} ya existe<br>";
    } else {
        // Crear usuario
        $contrasena_hash = password_hash($datos_usuario['contrasena'], PASSWORD_DEFAULT);
        $crear_usuario = "INSERT INTO usuarios (nombre, correo, contrasena, rol, activo) VALUES (?, ?, ?, ?, 1)";
        $stmt = $conexion->conn->prepare($crear_usuario);
        $stmt->bind_param("ssss", 
            $datos_usuario['nombre'], 
            $datos_usuario['correo'], 
            $contrasena_hash, 
            $datos_usuario['rol']
        );
        
        if ($stmt->execute()) {
            echo "✓ Usuario '{$datos_usuario['nombre']}' creado con rol '{$datos_usuario['rol']}'<br>";
        } else {
            throw new Exception("Error creando usuario: " . $stmt->error);
        }
    }
    
    // 3. Verificar si ya existe el colaborador
    $check_colaborador = "SELECT id FROM colaboradores WHERE correo = ?";
    $stmt = $conexion->conn->prepare($check_colaborador);
    $stmt->bind_param("s", $datos_colaborador['correo']);
    $stmt->execute();
    $colab_result = $stmt->get_result();
    
    if ($colab_result->num_rows > 0) {
        echo "⚠️ El colaborador con correo {$datos_colaborador['correo']} ya existe<br>";
    } else {
        // Crear colaborador
        $crear_colaborador = "INSERT INTO colaboradores (nombre, apellido, identificacion, direccion, ubicacion, telefono, correo, departamento_id, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)";
        $stmt = $conexion->conn->prepare($crear_colaborador);
        $stmt->bind_param("sssssssi", 
            $datos_colaborador['nombre'],
            $datos_colaborador['apellido'],
            $datos_colaborador['identificacion'],
            $datos_colaborador['direccion'],
            $datos_colaborador['ubicacion'],
            $datos_colaborador['telefono'],
            $datos_colaborador['correo'],
            $datos_colaborador['departamento_id']
        );
        
        if ($stmt->execute()) {
            echo "✓ Colaborador '{$datos_colaborador['nombre']} {$datos_colaborador['apellido']}' creado<br>";
        } else {
            throw new Exception("Error creando colaborador: " . $stmt->error);
        }
    }
    
    echo "<br><strong>📋 RESUMEN DE CONFIGURACIÓN:</strong><br>";
    echo "• Colaborador: {$datos_colaborador['nombre']} {$datos_colaborador['apellido']}<br>";
    echo "• Usuario de login: <strong>{$datos_usuario['nombre']}</strong> (también puede usar el correo)<br>";
    echo "• Correo: <strong>{$datos_usuario['correo']}</strong><br>";
    echo "• Contraseña: <strong>{$datos_usuario['contrasena']}</strong><br>";
    echo "• Rol: <strong>{$datos_usuario['rol']}</strong><br>";
    
    echo "<br><strong>🔐 FORMAS DE LOGIN:</strong><br>";
    echo "• Con usuario: <strong>{$datos_usuario['nombre']}</strong> + contraseña<br>";
    echo "• Con correo: <strong>{$datos_usuario['correo']}</strong> + contraseña<br>";
    
    // 4. Probar el login
    echo "<br><strong>🧪 PRUEBA DE VALIDACIÓN:</strong><br>";
    
    // Probar con nombre de usuario
    $resultado_usuario = $conexion->validarColaborador($datos_usuario['nombre'], $datos_usuario['contrasena']);
    if ($resultado_usuario) {
        echo "✓ Login con usuario '{$datos_usuario['nombre']}' funciona correctamente<br>";
    } else {
        echo "❌ Login con usuario '{$datos_usuario['nombre']}' falló<br>";
    }
    
    // Probar con correo
    $resultado_correo = $conexion->validarColaborador($datos_usuario['correo'], $datos_usuario['contrasena']);
    if ($resultado_correo) {
        echo "✓ Login con correo '{$datos_usuario['correo']}' funciona correctamente<br>";
    } else {
        echo "❌ Login con correo '{$datos_usuario['correo']}' falló<br>";
    }
    
    echo "<br><strong>✅ CONFIGURACIÓN COMPLETADA</strong><br>";
    echo "Ahora puedes probar el login de colaborador en la página principal.";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
