<?php
session_start();
require_once 'conexion.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id'])) {
    // Mostrar imagen por defecto si no hay usuario logueado
    $imagen_defecto = file_get_contents('img/perfil.jpg');
    header('Content-Type: image/jpeg');
    echo $imagen_defecto;
    exit;
}

$usuario_id = $_SESSION['id'];

try {
    $conexion = new Conexion();
    $mysqli = $conexion->getConexion();
    
    // Obtener la foto del usuario
    $stmt = $mysqli->prepare("SELECT foto, foto_tipo FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    
    if ($usuario && $usuario['foto']) {
        // Mostrar la foto desde la base de datos
        $tipo_mime = $usuario['foto_tipo'] ?: 'image/jpeg';
        header('Content-Type: ' . $tipo_mime);
        echo $usuario['foto'];
    } else {
        // Mostrar imagen por defecto si no tiene foto
        $imagen_defecto = file_get_contents('img/perfil.jpg');
        header('Content-Type: image/jpeg');
        echo $imagen_defecto;
    }
    
} catch (Exception $e) {
    // En caso de error, mostrar imagen por defecto
    $imagen_defecto = file_get_contents('img/perfil.jpg');
    header('Content-Type: image/jpeg');
    echo $imagen_defecto;
}
?>
