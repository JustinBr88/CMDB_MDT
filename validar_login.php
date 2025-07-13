<?php
require_once 'php/Conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $conexion = new Conexion();
    $db = $conexion->getConnection();
    $user = $conexion->validarsuario($usuario, $contrasena);

    if ($user) {
        echo json_encode(['success' => true, 'mensaje' => 'Login exitoso']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Usuario o contraseña incorrectos']);
    }
}


?>