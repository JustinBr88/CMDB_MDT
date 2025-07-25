<?php
require_once 'conexion.php';
$conexion = new Conexion();

echo "<h2>Verificación rápida de datos para donaciones</h2>";

// 1. Verificar colaboradores existentes
echo "<h3>1. Colaboradores activos:</h3>";
$result = $conexion->getConexion()->query("SELECT id, nombre, apellido FROM colaboradores WHERE activo = 1");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p>ID: {$row['id']} - {$row['nombre']} {$row['apellido']}</p>";
        
        // Para cada colaborador, verificar equipos asignados
        $equipos = $conexion->obtenerEquiposAsignadosColaborador($row['id']);
        echo "<p style='margin-left: 20px;'>Equipos asignados: " . count($equipos) . "</p>";
        
        foreach ($equipos as $equipo) {
            $disponible = $equipo['estado_asignacion'] === 'asignado' ? '✅ Disponible para donación' : '⚠️ No disponible';
            echo "<p style='margin-left: 40px; color: " . ($equipo['estado_asignacion'] === 'asignado' ? 'green' : 'orange') . ";'>";
            echo "- {$equipo['nombre_equipo']} (Estado: {$equipo['estado_asignacion']}) $disponible</p>";
        }
        echo "<hr>";
    }
} else {
    echo "<p>❌ No hay colaboradores activos</p>";
}

// 2. Si no hay asignaciones, intentar crear una automáticamente
$result = $conexion->getConexion()->query("SELECT COUNT(*) as total FROM asignaciones WHERE estado = 'asignado'");
$asignaciones = $result->fetch_assoc()['total'];

if ($asignaciones == 0) {
    echo "<h3>2. Creando datos de prueba...</h3>";
    
    // Buscar colaborador
    $result = $conexion->getConexion()->query("SELECT id FROM colaboradores WHERE activo = 1 LIMIT 1");
    if ($result->num_rows > 0) {
        $colaborador_id = $result->fetch_assoc()['id'];
        
        // Buscar equipo disponible
        $result = $conexion->getConexion()->query("
            SELECT id, nombre_equipo FROM inventario 
            WHERE estado IN ('inventario', 'activo') 
            AND id NOT IN (SELECT COALESCE(inventario_id, 0) FROM asignaciones WHERE estado = 'asignado')
            LIMIT 1
        ");
        
        if ($result->num_rows > 0) {
            $equipo = $result->fetch_assoc();
            
            try {
                // Crear asignación directamente
                $stmt = $conexion->getConexion()->prepare("
                    INSERT INTO asignaciones (inventario_id, colaborador_id, fecha_asignacion, estado) 
                    VALUES (?, ?, NOW(), 'asignado')
                ");
                $stmt->bind_param("ii", $equipo['id'], $colaborador_id);
                $stmt->execute();
                $stmt->close();
                
                // Actualizar estado del inventario
                $stmt = $conexion->getConexion()->prepare("UPDATE inventario SET estado = 'asignado' WHERE id = ?");
                $stmt->bind_param("i", $equipo['id']);
                $stmt->execute();
                $stmt->close();
                
                echo "<p style='color: green;'>✅ Asignación creada: {$equipo['nombre_equipo']} para colaborador ID $colaborador_id</p>";
                
            } catch (Exception $e) {
                echo "<p style='color: red;'>❌ Error creando asignación: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p>❌ No hay equipos disponibles en inventario</p>";
        }
    } else {
        echo "<p>❌ No hay colaboradores para asignar equipos</p>";
    }
} else {
    echo "<h3>2. Ya existen $asignaciones asignaciones activas</h3>";
}

echo "<hr>";
echo "<p><strong>Próximos pasos:</strong></p>";
echo "<ul>";
echo "<li>1. <a href='Usuario/Login.php' target='_blank'>Iniciar sesión como colaborador</a></li>";
echo "<li>2. <a href='colaboradores/portal_colaborador.php' target='_blank'>Ver portal colaborador</a></li>";
echo "<li>3. <a href='colaboradores/solicitar_donacion.php' target='_blank'>Ir a solicitar donación</a></li>";
echo "</ul>";
?>
