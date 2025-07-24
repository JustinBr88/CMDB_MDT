<?php
// Configurar headers para JSON
header('Content-Type: application/json');
ini_set('display_errors', 0); // No mostrar errores en output
error_reporting(E_ALL);

try {
    require_once 'conexion.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = $_POST['usuario'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        if (empty($usuario) || empty($contrasena)) {
            echo json_encode(['success' => false, 'mensaje' => 'Usuario y contraseña son requeridos']);
            exit;
        }

        try {
            $conexion = new Conexion();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'mensaje' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
            exit;
        }

        // Primero intenta como usuario/admin
        try {
            $user = $conexion->validarUsuario($usuario, $contrasena);

            if ($user) {
                session_start();
                $_SESSION['usuario'] = $user['nombre'];
                $_SESSION['logeado'] = true;
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['id'] = $user['id']; // Agregar ID para verificación de roles
                $_SESSION['foto'] = $user['foto'] ?? '';
                $_SESSION['tipo'] = 'usuario';
                echo json_encode(['success' => true, 'mensaje' => 'Login exitoso como usuario']);
                exit;
            }
        } catch (Exception $e) {
            // Log el error pero continúa intentando con colaborador
            error_log("Error validando usuario: " . $e->getMessage());
        }

        // Luego intenta como colaborador
        try {
            $colab = $conexion->validarColaborador($usuario, $contrasena);

            if ($colab) {
                session_start();
                $_SESSION['usuario'] = $colab['nombre'] . ' ' . $colab['apellido'];
                $_SESSION['logeado'] = true;
                $_SESSION['rol'] = 'colaborador';
                $_SESSION['id'] = $colab['id']; // Agregar ID
                $_SESSION['foto'] = $colab['foto'] ?? '';
                $_SESSION['tipo'] = 'colaborador';
                echo json_encode(['success' => true, 'mensaje' => 'Login exitoso como colaborador']);
                exit;
            }
        } catch (Exception $e) {
            error_log("Error validando colaborador: " . $e->getMessage());
        }

        // Si ninguno funciona
        echo json_encode(['success' => false, 'mensaje' => 'Usuario o contraseña incorrectos']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'mensaje' => 'Error del servidor: ' . $e->getMessage()]);
}
?>