<?php
session_start();
require_once 'conexion.php';

// Permitir pasar el ID del usuario como parámetro para flexibilidad
$usuario_id = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $usuario_id = (int)$_GET['id'];
} elseif (isset($_SESSION['id'])) {
    $usuario_id = $_SESSION['id'];
}

if (!$usuario_id) {
    // Mostrar imagen por defecto si no hay usuario
    $imagen_defecto = file_get_contents('img/usuarios/default.jpg');
    header('Content-Type: image/jpeg');
    echo $imagen_defecto;
    exit;
}

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
        $imagen_defecto = file_get_contents('img/usuarios/default.jpg');
        header('Content-Type: image/jpeg');
        echo $imagen_defecto;
    }
    
} catch (Exception $e) {
    // En caso de error, mostrar imagen por defecto
    $imagen_defecto = file_get_contents('img/usuarios/default.jpg');
    header('Content-Type: image/jpeg');
    echo $imagen_defecto;
}
?>
