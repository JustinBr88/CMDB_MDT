<?php
session_start();
require_once '../conexion.php';
require_once '../sanitizardatos.php';

// Establecer headers para JSON y limpiar output buffer
if (ob_get_level()) {
    ob_clean();
}
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['id'];
    
    // Obtener y sanitizar datos
    $password_actual = trim($_POST['password_actual'] ?? '');
    $password_nueva = trim($_POST['password_nueva'] ?? '');
    $password_confirmar = trim($_POST['password_confirmar'] ?? '');
    
    // Validaciones básicas
    if (empty($password_actual) || empty($password_nueva) || empty($password_confirmar)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    if ($password_nueva !== $password_confirmar) {
        echo json_encode(['success' => false, 'message' => 'Las nuevas contraseñas no coinciden'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    if (strlen($password_nueva) < 6) {
        echo json_encode(['success' => false, 'message' => 'La nueva contraseña debe tener al menos 6 caracteres'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    if (strlen($password_nueva) > 100) {
        echo json_encode(['success' => false, 'message' => 'La nueva contraseña es demasiado larga'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $conexion = new Conexion();
        $conn = $conexion->getConexion();
        
        // Verificar contraseña actual
        $stmt = $conn->prepare("SELECT contrasena FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();
        
        if (!$usuario) {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // Verificar contraseña actual
        if (!password_verify($password_actual, $usuario['contrasena'])) {
            echo json_encode(['success' => false, 'message' => 'La contraseña actual es incorrecta'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // Hashear nueva contraseña
        $password_hash = password_hash($password_nueva, PASSWORD_DEFAULT);
        
        // Actualizar contraseña
        $stmt = $conn->prepare("UPDATE usuarios SET contrasena = ? WHERE id = ?");
        $stmt->bind_param("si", $password_hash, $usuario_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña'], JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        
    } catch (Exception $e) {
        error_log("Error en cambiar_password.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error del servidor. Intente nuevamente'], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);
}
