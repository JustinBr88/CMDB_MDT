<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($nombre) && !empty($apellido) && !empty($correo) && !empty($password)) {
        try {
            $conexion = new Conexion();
            
            // 1. Insertar colaborador
            $stmt = $conexion->getConexion()->prepare("
                INSERT INTO colaboradores (nombre, apellido, identificacion, correo, activo) 
                VALUES (?, ?, ?, ?, 1)
                ON DUPLICATE KEY UPDATE nombre=VALUES(nombre), apellido=VALUES(apellido)
            ");
            $identificacion = uniqid(); // ID temporal único
            $stmt->bind_param("ssss", $nombre, $apellido, $identificacion, $correo);
            $stmt->execute();
            $stmt->close();
            
            // 2. Insertar/actualizar usuario
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $nombre_completo = "$nombre $apellido";
            
            $stmt = $conexion->getConexion()->prepare("
                INSERT INTO usuarios (nombre, correo, contrasena, rol, activo) 
                VALUES (?, ?, ?, 'colab', 1)
                ON DUPLICATE KEY UPDATE contrasena=VALUES(contrasena), rol='colab', activo=1
            ");
            $stmt->bind_param("sss", $nombre_completo, $correo, $password_hash);
            $stmt->execute();
            $stmt->close();
            
            echo "<div class='alert alert-success'>✅ Colaborador creado/actualizado exitosamente!</div>";
            echo "<p><strong>Datos para login:</strong></p>";
            echo "<p>Correo: $correo</p>";
            echo "<p>Contraseña: $password</p>";
            
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>⚠️ Todos los campos son obligatorios</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crear Colaborador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Crear Colaborador de Prueba</h2>
    
    <form method="POST" class="mt-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control" required value="Juan">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellido *</label>
                <input type="text" name="apellido" class="form-control" required value="Pérez">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo *</label>
            <input type="email" name="correo" class="form-control" required value="juan.perez@test.com">
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña *</label>
            <input type="text" name="password" class="form-control" required value="123456">
        </div>
        <button type="submit" class="btn btn-primary">Crear Colaborador</button>
        <a href="Usuario/Login.php" class="btn btn-success">Ir al Login</a>
    </form>
    
    <hr>
    
    <h3>Colaboradores Existentes:</h3>
    <?php
    try {
        require_once 'conexion.php';
        $conexion = new Conexion();
        
        $result = $conexion->getConexion()->query("
            SELECT c.nombre, c.apellido, c.correo, c.activo,
                   u.nombre as usuario_nombre, u.rol, u.activo as usuario_activo
            FROM colaboradores c
            LEFT JOIN usuarios u ON c.correo = u.correo AND u.rol = 'colab'
            ORDER BY c.correo
        ");
        
        if ($result->num_rows > 0) {
            echo "<table class='table table-striped'>";
            echo "<thead><tr><th>Nombre</th><th>Correo</th><th>Estado Colab</th><th>Estado Usuario</th><th>Rol</th></tr></thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                $colab_status = $row['activo'] ? 'Activo' : 'Inactivo';
                $user_status = $row['usuario_activo'] ? 'Activo' : 'Inactivo';
                $user_rol = $row['rol'] ?? 'Sin usuario';
                
                $row_class = ($row['activo'] && $row['usuario_activo'] && $row['rol'] === 'colab') ? 'table-success' : 'table-warning';
                
                echo "<tr class='$row_class'>";
                echo "<td>{$row['nombre']} {$row['apellido']}</td>";
                echo "<td>{$row['correo']}</td>";
                echo "<td>$colab_status</td>";
                echo "<td>$user_status</td>";
                echo "<td>$user_rol</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
            echo "<small class='text-muted'>Filas verdes = Configuración completa, Filas amarillas = Configuración incompleta</small>";
        } else {
            echo "<p>No hay colaboradores registrados</p>";
        }
    } catch (Exception $e) {
        echo "<p class='text-danger'>Error: " . $e->getMessage() . "</p>";
    }
    ?>
</div>
</body>
</html>
