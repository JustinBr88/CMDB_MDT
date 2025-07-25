<?php
require_once '../conexion.php';
session_start();
header('Content-Type: application/json');

// Verificar que el usuario sea administrador
if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    echo json_encode(['success' => false, 'error' => 'No tiene permisos de administrador.']);
    exit;
}

$conexion = new Conexion();
$data = json_decode(file_get_contents("php://input"), true);

$asignacion_id = $data['asignacion_id'] ?? null;
$inventario_id = $data['inventario_id'] ?? null;
$motivo = $data['motivo'] ?? '';
$nuevo_estado = $data['nuevo_estado'] ?? 'inventario';

// Validar datos requeridos
if (!$asignacion_id || !$inventario_id) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
    exit;
}

// Validar que el nuevo estado sea válido
$estados_validos = ['inventario', 'reparacion', 'descarte', 'activo'];
if (!in_array($nuevo_estado, $estados_validos)) {
    echo json_encode(['success' => false, 'error' => 'Estado no válido.']);
    exit;
}

// Iniciar transacción
$conexion->getConexion()->begin_transaction();

try {
    // Verificar que la asignación existe y está activa
    $stmt = $conexion->getConexion()->prepare("
        SELECT a.id, a.inventario_id, a.colaborador_id, i.nombre_equipo, c.nombre as colaborador_nombre
        FROM asignaciones a 
        LEFT JOIN inventario i ON a.inventario_id = i.id
        LEFT JOIN colaboradores c ON a.colaborador_id = c.id
        WHERE a.id = ? AND a.estado = 'asignado'
    ");
    $stmt->bind_param("i", $asignacion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Asignación no encontrada o ya fue retirada.');
    }
    
    $asignacion = $result->fetch_assoc();
    $stmt->close();
    
    // 1. Actualizar el estado de la asignación a "retirado"
    $stmt = $conexion->getConexion()->prepare("
        UPDATE asignaciones 
        SET estado = 'retirado', fecha_retiro = NOW(), motivo_retiro = ?
        WHERE id = ?
    ");
    $stmt->bind_param("si", $motivo, $asignacion_id);
    $stmt->execute();
    $stmt->close();
    
    // 2. Actualizar el estado del equipo en inventario
    $stmt = $conexion->getConexion()->prepare("
        UPDATE inventario 
        SET estado = ?
        WHERE id = ?
    ");
    $stmt->bind_param("si", $nuevo_estado, $inventario_id);
    $stmt->execute();
    $stmt->close();
    
    // Confirmar transacción
    $conexion->getConexion()->commit();
    
    $mensaje = "Equipo '{$asignacion['nombre_equipo']}' retirado correctamente del colaborador '{$asignacion['colaborador_nombre']}' y cambiado a estado '{$nuevo_estado}'.";
    
    echo json_encode([
        'success' => true, 
        'message' => $mensaje
    ]);
    
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conexion->getConexion()->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
