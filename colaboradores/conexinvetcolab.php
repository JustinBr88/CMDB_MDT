<?php
require_once '../conexion.php';
session_start();
header('Content-Type: application/json');
$conexion = new Conexion();

// Asegúrate de tener el id del colaborador en la sesión
if (!isset($_SESSION['colaborador_id'])) {
    echo json_encode(['success' => false, 'error' => 'No has iniciado sesión como colaborador.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$inventario_id = $data['inventario_id'] ?? null;

// Verifica que el inventario exista y esté disponible
$stmt = $conexion->getConexion()->prepare("SELECT estado FROM inventario WHERE id=?");
$stmt->bind_param("i", $inventario_id);
$stmt->execute();
$stmt->bind_result($estado);
if ($stmt->fetch()) {
    if ($estado !== 'activo' && $estado !== 'inventario') {
        echo json_encode(['success' => false, 'error' => 'El equipo no está disponible para solicitud.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Equipo no encontrado.']);
    exit;
}
$stmt->close();

// Inserta la solicitud
$colaborador_id = $_SESSION['colaborador_id'];
$fecha_solicitud = date('Y-m-d H:i:s');
$estado_solicitud = 'pendiente';

$stmt = $conexion->getConexion()->prepare(
    "INSERT INTO solicitudes (inventario_id, colaborador_id, fecha_solicitud, estado) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("iiss", $inventario_id, $colaborador_id, $fecha_solicitud, $estado_solicitud);
$success = $stmt->execute();
$stmt->close();

echo json_encode(['success' => $success]);
?>