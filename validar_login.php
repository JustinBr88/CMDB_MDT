<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario']; // Puede ser usuario o correo
    $contrasena = $_POST['contrasena'];

    $conexion = new Conexion();

    // Primero intenta como usuario/admin
    $user = $conexion->validarUsuario($usuario, $contrasena);

    if ($user) {
        session_start();
        $_SESSION['usuario'] = $user['nombre'];
        $_SESSION['logeado'] = true;
        $_SESSION['rol'] = $user['rol'];
        $_SESSION['foto'] = $user['foto'] ?? '';
        $_SESSION['tipo'] = 'usuario';
        echo json_encode(['success' => true, 'mensaje' => 'Login exitoso (usuario/admin)']);
        exit;
    }

    // Luego intenta como colaborador
    $colab = $conexion->validarColaborador($usuario, $contrasena);

    if ($colab) {
        session_start();
        $_SESSION['usuario'] = $colab['nombre'] . ' ' . $colab['apellido'];
        $_SESSION['logeado'] = true;
        $_SESSION['rol'] = 'colaborador';
        $_SESSION['foto'] = $colab['foto'] ?? '';
        $_SESSION['tipo'] = 'colaborador';
        echo json_encode(['success' => true, 'mensaje' => 'Login exitoso (colaborador)']);
        exit;
    }

    // Si ninguno funciona
    echo json_encode(['success' => false, 'mensaje' => 'Usuario o contraseña incorrectos']);
}
?>