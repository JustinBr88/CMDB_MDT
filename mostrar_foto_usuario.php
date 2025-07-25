<?php
session_start();
require_once 'conexion.php';

// Determinar qué tipo de usuario y ID usar
$usuario_id = null;
$colaborador_id = null;
$tipo = 'usuario'; // por defecto

// Verificar si se pasa un parámetro específico
if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    if ($tipo === 'colaborador') {
        $colaborador_id = (int)$_GET['id'];
    } else {
        $usuario_id = (int)$_GET['id'];
    }
} else {
    // Usar datos de sesión
    if (isset($_SESSION['colaborador_id']) && isset($_SESSION['colaborador_logeado'])) {
        $colaborador_id = $_SESSION['colaborador_id'];
        $tipo = 'colaborador';
    } elseif (isset($_SESSION['id'])) {
        $usuario_id = $_SESSION['id'];
        $tipo = 'usuario';
    }
}

// Si no hay ID válido, mostrar imagen por defecto
if (!$usuario_id && !$colaborador_id) {
    $imagen_defecto = file_get_contents('img/usuarios/default.jpg');
    header('Content-Type: image/jpeg');
    echo $imagen_defecto;
    exit;
}

try {
    $conexion = new Conexion();
    $mysqli = $conexion->getConexion();
    
    if ($tipo === 'colaborador' && $colaborador_id) {
        // Obtener la foto del colaborador
        $stmt = $mysqli->prepare("SELECT foto, foto_tipo FROM colaboradores WHERE id = ?");
        $stmt->bind_param("i", $colaborador_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $colaborador = $result->fetch_assoc();
        
        if ($colaborador && $colaborador['foto']) {
            // Mostrar la foto desde la base de datos
            $tipo_mime = $colaborador['foto_tipo'] ?: 'image/jpeg';
            header('Content-Type: ' . $tipo_mime);
            echo $colaborador['foto'];
        } else {
            // Mostrar imagen por defecto si no tiene foto
            $imagen_defecto = file_get_contents('img/usuarios/default.jpg');
            header('Content-Type: image/jpeg');
            echo $imagen_defecto;
        }
    } else {
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
    }
    
} catch (Exception $e) {
    // En caso de error, mostrar imagen por defecto
    $imagen_defecto = file_get_contents('img/usuarios/default.jpg');
    header('Content-Type: image/jpeg');
    echo $imagen_defecto;
}
?>
