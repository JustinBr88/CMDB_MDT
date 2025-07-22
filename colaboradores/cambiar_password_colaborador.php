<?php
session_start();
require_once('conexion.php');

if (!isset($_SESSION['colaborador_logeado']) || !$_SESSION['colaborador_id']) {
    header('Location: LoginColaborador.php');
    exit;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldpass = $_POST['oldpass'] ?? '';
    $newpass = $_POST['newpass'] ?? '';

    $conexion = new Conexion();
    $colab = $conexion->obtenerColaboradorPorId($_SESSION['colaborador_id']);

    if ($colab && password_verify($oldpass, $colab['contrasena'])) {
        // Cambiar la contraseña
        $ok = $conexion->actualizarPasswordColaborador($colab['id'], $newpass);
        if ($ok) {
            $_SESSION['colab_pass_msg'] = "Contraseña actualizada correctamente.";
        } else {
            $_SESSION['colab_pass_msg'] = "No se pudo actualizar la contraseña.";
        }
    } else {
        $_SESSION['colab_pass_msg'] = "La contraseña actual es incorrecta.";
    }
    header('Location: portal_colaborador.php');
    exit;
}
?>