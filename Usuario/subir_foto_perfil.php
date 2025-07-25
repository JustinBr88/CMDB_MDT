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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    $usuario_id = $_SESSION['id'];
    $archivo = $_FILES['foto_perfil'];
    
    // Validar archivo
    $tipos_permitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $tamano_maximo = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($archivo['type'], $tipos_permitidos)) {
        echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido. Solo se permiten JPG, PNG y GIF.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    if ($archivo['size'] > $tamano_maximo) {
        echo json_encode(['success' => false, 'message' => 'El archivo es demasiado grande. Máximo 5MB.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Error al subir el archivo.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $conexion = new Conexion();
        $conn = $conexion->getConexion();
        
        // Leer el archivo como datos binarios
        $foto_datos = file_get_contents($archivo['tmp_name']);
        $foto_tipo = $archivo['type'];
        
        // Actualizar la foto en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET foto = ?, foto_tipo = ? WHERE id = ?");
        $stmt->bind_param("ssi", $foto_datos, $foto_tipo, $usuario_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Foto de perfil actualizada correctamente'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar la foto en la base de datos'], JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
        
    } catch (Exception $e) {
        error_log("Error en subir_foto_perfil.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error del servidor. Intente nuevamente'], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No se recibió ningún archivo'], JSON_UNESCAPED_UNICODE);
}
