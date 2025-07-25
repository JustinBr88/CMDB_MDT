<?php
// Iniciar buffer de salida y configurar para no mostrar errores
ob_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Función para enviar respuesta JSON limpia
function enviarRespuestaJSON($data) {
    // Limpiar cualquier output previo
    if (ob_get_level()) {
        ob_clean();
    }
    
    // Configurar headers
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Cache-Control: no-cache, must-revalidate');
    
    // Enviar JSON y terminar
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Manejar preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Incluir archivos con manejo de errores
try {
    require_once 'sanitizardatos.php';
} catch (Exception $e) {
    enviarRespuestaJSON(['success' => false, 'mensaje' => 'Error de configuración del sistema']);
}

try {
    require_once 'conexion.php';
} catch (Exception $e) {
    enviarRespuestaJSON(['success' => false, 'mensaje' => 'Error de conexión a la base de datos']);
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitizar datos de entrada
        $usuario = SanitizarDatos::sanitizarTexto($_POST['usuario'] ?? '');
        $contrasena = $_POST['contrasena'] ?? ''; // No sanitizar contraseña (puede tener caracteres especiales)

        if (empty($usuario) || empty($contrasena)) {
            enviarRespuestaJSON(['success' => false, 'mensaje' => 'Usuario y contraseña son requeridos']);
        }

        // Validar longitud para prevenir ataques
        if (!SanitizarDatos::validarLongitud($usuario, 3, 50)) {
            enviarRespuestaJSON(['success' => false, 'mensaje' => 'Usuario debe tener entre 3 y 50 caracteres']);
        }

        if (!SanitizarDatos::validarLongitud($contrasena, 4, 255)) {
            enviarRespuestaJSON(['success' => false, 'mensaje' => 'Contraseña no válida']);
        }

        try {
            $conexion = new Conexion();
        } catch (Exception $e) {
            enviarRespuestaJSON(['success' => false, 'mensaje' => 'Error de conexión a la base de datos']);
        }

        // Primero intenta como usuario/admin
        try {
            $user = $conexion->validarUsuario($usuario, $contrasena);

            if ($user) {
                session_start();
                // Limpiar cualquier sesión de colaborador anterior
                unset($_SESSION['colaborador_logeado']);
                unset($_SESSION['colaborador_id']);
                unset($_SESSION['colaborador_nombre']);
                unset($_SESSION['colaborador_apellido']);
                unset($_SESSION['colaborador_foto']);
                unset($_SESSION['colaborador_usuario']);
                
                // Establecer variables de usuario/admin
                $_SESSION['usuario'] = $user['nombre'];
                $_SESSION['logeado'] = true;
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['id'] = $user['id']; // Agregar ID para verificación de roles
                $_SESSION['foto'] = $user['foto'] ?? '';
                $_SESSION['tipo'] = 'usuario';
                
                enviarRespuestaJSON([
                    'success' => true, 
                    'mensaje' => 'Login exitoso como usuario',
                    'redirect' => 'Usuario/Home.php',
                    'tipo' => 'usuario'
                ]);
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
                // Limpiar cualquier sesión de usuario anterior
                unset($_SESSION['logeado']);
                unset($_SESSION['usuario']);
                unset($_SESSION['rol']);
                unset($_SESSION['id']);
                unset($_SESSION['foto']);
                unset($_SESSION['tipo']);
                
                // Establecer solo variables de colaborador
                $_SESSION['colaborador_logeado'] = true;
                $_SESSION['colaborador_id'] = $colab['id'];
                $_SESSION['colaborador_nombre'] = $colab['nombre'];
                $_SESSION['colaborador_apellido'] = $colab['apellido'];
                $_SESSION['colaborador_foto'] = $colab['foto'] ?? '';
                $_SESSION['colaborador_usuario'] = $colab['correo']; // Usar correo como usuario
                
                // Log para debug
                error_log("Login colaborador exitoso - ID: " . $colab['id'] . ", Nombre: " . $colab['nombre']);
                
                enviarRespuestaJSON([
                    'success' => true, 
                    'mensaje' => 'Login exitoso como colaborador',
                    'redirect' => 'colaboradores/portal_colaborador.php',
                    'tipo' => 'colaborador'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error validando colaborador: " . $e->getMessage());
        }

        // Si ninguno funciona
        enviarRespuestaJSON(['success' => false, 'mensaje' => 'Usuario o contraseña incorrectos']);
        
    } else {
        enviarRespuestaJSON(['success' => false, 'mensaje' => 'Método no permitido']);
    }

} catch (Exception $e) {
    error_log("Error general en validar_login.php: " . $e->getMessage());
    enviarRespuestaJSON(['success' => false, 'mensaje' => 'Error del servidor']);
}
