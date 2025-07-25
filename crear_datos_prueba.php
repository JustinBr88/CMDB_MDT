<?php
require_once 'conexion.php';
$conexion = new Conexion();

echo "<h2>SCRIPT DE PRUEBA - Crear solicitud y asignación</h2>";

try {
    // 1. Verificar que existe el colaborador
    $colaborador_id = 1;
    $result = $conexion->getConexion()->query("SELECT id, nombre, apellido FROM colaboradores WHERE id = $colaborador_id");
    if ($result->num_rows === 0) {
        throw new Exception("Colaborador no existe");
    }
    $colaborador = $result->fetch_assoc();
    echo "<p>✅ Colaborador encontrado: {$colaborador['nombre']} {$colaborador['apellido']}</p>";

    // 2. Buscar un equipo disponible en inventario
    $result = $conexion->getConexion()->query("
        SELECT id, nombre_equipo FROM inventario 
        WHERE estado IN ('inventario', 'activo') 
        AND id NOT IN (SELECT inventario_id FROM asignaciones WHERE estado = 'asignado')
        LIMIT 1
    ");
    
    if ($result->num_rows === 0) {
        throw new Exception("No hay equipos disponibles");
    }
    $equipo = $result->fetch_assoc();
    echo "<p>✅ Equipo disponible encontrado: {$equipo['nombre_equipo']} (ID: {$equipo['id']})</p>";

    // 3. Crear una solicitud
    $stmt = $conexion->getConexion()->prepare("
        INSERT INTO solicitudes (colaborador_id, inventario_id, nombre_equipo, fecha_solicitud, estado, motivo, tipo) 
        VALUES (?, ?, ?, NOW(), 'pendiente', 'Solicitud de prueba para testing', 'asignacion')
    ");
    $stmt->bind_param("iis", $colaborador_id, $equipo['id'], $equipo['nombre_equipo']);
    $stmt->execute();
    $solicitud_id = $conexion->getConexion()->insert_id;
    $stmt->close();
    echo "<p>✅ Solicitud creada con ID: $solicitud_id</p>";

    // 4. Simular aprobación de la solicitud (como admin)
    $conexion->getConexion()->begin_transaction();
    
    // Actualizar estado del equipo
    $stmt = $conexion->getConexion()->prepare("UPDATE inventario SET estado = 'asignado' WHERE id = ?");
    $stmt->bind_param("i", $equipo['id']);
    $stmt->execute();
    $stmt->close();
    
    // Crear asignación
    $stmt = $conexion->getConexion()->prepare("
        INSERT INTO asignaciones (inventario_id, colaborador_id, fecha_asignacion, estado) 
        VALUES (?, ?, NOW(), 'asignado')
    ");
    $stmt->bind_param("ii", $equipo['id'], $colaborador_id);
    $stmt->execute();
    $asignacion_id = $conexion->getConexion()->insert_id;
    $stmt->close();
    
    // Actualizar solicitud
    $stmt = $conexion->getConexion()->prepare("
        UPDATE solicitudes SET estado = 'aceptada', fecha_respuesta = NOW(), usuario_admin_id = 1 WHERE id = ?
    ");
    $stmt->bind_param("i", $solicitud_id);
    $stmt->execute();
    $stmt->close();
    
    $conexion->getConexion()->commit();
    echo "<p>✅ Solicitud aprobada y asignación creada con ID: $asignacion_id</p>";

    // 5. Verificar resultado
    echo "<h3>Verificación final:</h3>";
    
    // Verificar método obtenerEquiposAsignadosColaborador
    $equipos = $conexion->obtenerEquiposAsignadosColaborador($colaborador_id);
    echo "<p><strong>Equipos devueltos por el método:</strong> " . count($equipos) . "</p>";
    
    foreach ($equipos as $eq) {
        echo "<p style='color: green;'>- {$eq['nombre_equipo']} (Estado: {$eq['estado_asignacion']})</p>";
    }
    
    // Filtrar para donación
    $equipos_donables = array_filter($equipos, function($equipo) {
        return $equipo['estado_asignacion'] === 'asignado';
    });
    echo "<p><strong>Equipos disponibles para donación:</strong> " . count($equipos_donables) . "</p>";
    
    if (count($equipos_donables) > 0) {
        echo "<p style='color: green; font-weight: bold;'>🎉 ¡ÉXITO! Ahora el colaborador puede solicitar donaciones</p>";
        echo "<p><a href='colaboradores/solicitar_donacion.php' target='_blank'>➤ Ir a solicitar donación</a></p>";
    } else {
        echo "<p style='color: red;'>❌ Aún no hay equipos disponibles para donación</p>";
    }

} catch (Exception $e) {
    if (isset($conexion)) {
        $conexion->getConexion()->rollback();
    }
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Nota:</strong> Este script crea datos de prueba. Si ya tienes datos reales, úsalos en su lugar.</p>";
echo "<p><a href='debug_donaciones.php'>Ver debug completo</a></p>";
?>
