<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'Conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $conexion = new Conexion();
    $db = $conexion->getConexion();
    $user = $conexion->validarsuario($usuario, $contrasena);

    if ($user) {
        session_start();
        $_SESSION['usuario'] = $user['nombre'];
        $_SESSION['logeado'] = true;
        echo json_encode(['success' => true, 'mensaje' => 'Login exitoso']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Usuario o contraseña incorrectos']);
    }
}
?>