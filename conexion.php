<?php
class Conexion {
    private $servername = "localhost";
    private $username = "root";
    private $password = "12345";
    private $dbname = "cmdb";
    private $port = 3306;
    private $conn;

    public function __construct() {
        try {
            // Opción 1: Conexión con configuración específica para WAMP
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            
            if ($this->conn->connect_error) {
                throw new Exception("Error de conexión: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            // Opción 2: Intentar con 127.0.0.1 en lugar de localhost
            try {
                $this->conn = new mysqli("127.0.0.1", $this->username, $this->password, $this->dbname, $this->port);
                if ($this->conn->connect_error) {
                    throw new Exception("Error de conexión: " . $this->conn->connect_error);
                }
                $this->conn->set_charset("utf8mb4");
                
            } catch (Exception $e2) {
                // Opción 3: Intentar con socket local (para WAMP)
                try {
                    $this->conn = new mysqli("localhost:/tmp/mysql.sock", $this->username, $this->password, $this->dbname);
                    if ($this->conn->connect_error) {
                        throw new Exception("Error de conexión: " . $this->conn->connect_error);
                    }
                    $this->conn->set_charset("utf8mb4");
                } catch (Exception $e3) {
                    die("No se pudo establecer conexión a la base de datos. Posibles soluciones:<br>
                         1. Verifique que MySQL esté ejecutándose en WAMP<br>
                         2. Verifique que la contraseña del usuario root sea '1234'<br>
                         3. En phpMyAdmin, cambie el plugin de autenticación del usuario root a 'mysql_native_password'<br>
                         Error: " . $e3->getMessage());
                }
            }
        }
    }

    public function getConexion() {
        return $this->conn;
    }

    // Validación de usuario/admin por correo o usuario
    public function validarUsuario($usuario_correo, $contrasena) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE (nombre = ? OR correo = ?)");
        $stmt->bind_param("ss", $usuario_correo, $usuario_correo);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario_db = $result->fetch_assoc();
        $stmt->close();
        if ($usuario_db && password_verify($contrasena, $usuario_db['contrasena'])) {
            return $usuario_db;
        }
        return false;
    }

    // Validación de colaborador por correo o usuario
    public function validarColaborador($usuario_correo, $contrasena) {
        $stmt = $this->conn->prepare("SELECT * FROM colaboradores WHERE (nombre = ? OR correo = ?) AND activo = 1");
        $stmt->bind_param("ss", $usuario_correo, $usuario_correo);
        $stmt->execute();
        $result = $stmt->get_result();
        $colaborador = $result->fetch_assoc();
        $stmt->close();
        if ($colaborador && password_verify($contrasena, $colaborador['contrasena'])) {
            return $colaborador;
        }
        return false;
    }

    
    // Obtener todos los departamentos
    public function obtenerDepartamentos() {
        $sql = "SELECT * FROM departamentos";
        $result = $this->conn->query($sql);
        $departamentos = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $departamentos[] = $row;
            }
        }
        return $departamentos;
    }

    // Obtener todas las categorías
    public function obtenerCategorias() {
        $sql = "SELECT * FROM categorias";
        $result = $this->conn->query($sql);
        $categorias = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categorias[] = $row;
            }
        }
        return $categorias;
    }

    // Obtener todo el inventario con nombre de categoría
    public function obtenerInventario() {
        $sql = "SELECT i.*, c.nombre AS categoria FROM inventario i 
                LEFT JOIN categorias c ON i.categoria_id = c.id";
        $result = $this->conn->query($sql);
        $inventario = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $inventario[] = $row;
            }
        }
        return $inventario;
    }

    // Obtener todas las solicitudes
    public function obtenerSolicitudes() {
        $sql = "SELECT s.*, i.nombre_equipo, c.nombre AS colaborador_nombre, c.apellido AS colaborador_apellido
                FROM solicitudes s
                LEFT JOIN inventario i ON s.inventario_id = i.id
                LEFT JOIN colaboradores c ON s.colaborador_id = c.id";
        $result = $this->conn->query($sql);
        $solicitudes = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $solicitudes[] = $row;
            }
        }
        return $solicitudes;
    }

    // Obtener todas las asignaciones
    public function obtenerAsignaciones() {
        $sql = "SELECT a.*, i.nombre_equipo, c.nombre AS colaborador_nombre, c.apellido AS colaborador_apellido
                FROM asignaciones a
                LEFT JOIN inventario i ON a.inventario_id = i.id
                LEFT JOIN colaboradores c ON a.colaborador_id = c.id";
        $result = $this->conn->query($sql);
        $asignaciones = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $asignaciones[] = $row;
            }
        }
        return $asignaciones;
    }

    // Obtener historial de accesos de un colaborador
    public function obtenerHistorialAccesosColaborador($colaborador_id) {
        $stmt = $this->conn->prepare("SELECT * FROM historial_accesos_colaborador WHERE colaborador_id = ?");
        $stmt->bind_param("i", $colaborador_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $historial = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $historial[] = $row;
            }
        }
        $stmt->close();
        return $historial;
    }

    public function obtenerInventarioDisponible() {
    $sql = "SELECT i.*, c.nombre as categoria FROM inventario i
            LEFT JOIN categorias c ON i.categoria_id = c.id
            WHERE i.estado IN ('activo', 'inventario')";
    $result = $this->getConexion()->query($sql);
    $inventario = [];
    while ($row = $result->fetch_assoc()) {
        $inventario[] = $row;
    }
    return $inventario;
    }

    // Métodos CRUD para Categorías
    
    // Insertar nueva categoría
    public function insertarCategoria($nombre, $descripcion) {
        $stmt = $this->conn->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $descripcion);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    // Actualizar categoría
    public function actualizarCategoria($id, $nombre, $descripcion) {
        $stmt = $this->conn->prepare("UPDATE categorias SET nombre = ?, descripcion = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    // Eliminar categoría
    public function eliminarCategoria($id) {
        // Verificar si la categoría está siendo usada en inventario
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM inventario WHERE categoria_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        if ($row['count'] > 0) {
            return false; // No se puede eliminar porque está en uso
        }
        
        $stmt = $this->conn->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    // Obtener categoría por ID
    public function obtenerCategoriaPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $categoria = $result->fetch_assoc();
        $stmt->close();
        return $categoria;
    }

    // Verificar si el usuario es administrador
    public function esAdministrador($usuario_id) {
        $stmt = $this->conn->prepare("SELECT rol FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();
        
        // Debug temporal
        error_log("DEBUG esAdministrador - Usuario ID: $usuario_id");
        error_log("DEBUG esAdministrador - Usuario encontrado: " . print_r($usuario, true));
        error_log("DEBUG esAdministrador - Rol: " . ($usuario ? $usuario['rol'] : 'NULL'));
        
        return $usuario && $usuario['rol'] === 'admin';
    }

    // MÉTODOS PARA REPORTES

    // Obtener estadísticas por categoría
    public function obtenerEstadisticasPorCategoria() {
        $sql = "SELECT 
                    c.id as categoria_id,
                    c.nombre as categoria,
                    COUNT(i.id) as total_equipos,
                    COUNT(a.id) as equipos_asignados
                FROM categorias c
                LEFT JOIN inventario i ON c.id = i.categoria_id
                LEFT JOIN asignaciones a ON i.id = a.inventario_id AND a.estado = 'asignado'
                GROUP BY c.id, c.nombre
                ORDER BY c.nombre";
        
        $result = $this->conn->query($sql);
        $estadisticas = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $estadisticas[] = $row;
            }
        }
        return $estadisticas;
    }

    // Obtener equipos disponibles por categoría
    public function obtenerEquiposDisponiblesPorCategoria() {
        $sql = "SELECT 
                    c.nombre as categoria,
                    COUNT(i.id) as equipos_disponibles
                FROM categorias c
                LEFT JOIN inventario i ON c.id = i.categoria_id
                LEFT JOIN asignaciones a ON i.id = a.inventario_id AND a.estado = 'asignado'
                WHERE a.id IS NULL
                GROUP BY c.id, c.nombre
                ORDER BY c.nombre";
        
        $result = $this->conn->query($sql);
        $disponibles = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $disponibles[] = $row;
            }
        }
        return $disponibles;
    }

    // Obtener equipos asignados por categoría
    public function obtenerEquiposAsignadosPorCategoria() {
        $sql = "SELECT 
                    c.nombre as categoria,
                    COUNT(a.id) as equipos_asignados,
                    GROUP_CONCAT(CONCAT(col.nombre, ' ', col.apellido) SEPARATOR ', ') as colaboradores
                FROM categorias c
                LEFT JOIN inventario i ON c.id = i.categoria_id
                LEFT JOIN asignaciones a ON i.id = a.inventario_id AND a.estado = 'asignado'
                LEFT JOIN colaboradores col ON a.colaborador_id = col.id
                WHERE a.id IS NOT NULL
                GROUP BY c.id, c.nombre
                ORDER BY c.nombre";
        
        $result = $this->conn->query($sql);
        $asignados = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $asignados[] = $row;
            }
        }
        return $asignados;
    }

    // Obtener detalle de equipos por categoría
    public function obtenerEquiposPorCategoria($categoria_id, $estado = null) {
        $sql = "SELECT 
                    i.*,
                    c.nombre as categoria,
                    CASE 
                        WHEN a.id IS NOT NULL THEN 'Asignado'
                        ELSE 'Disponible'
                    END as estado_equipo,
                    CONCAT(col.nombre, ' ', col.apellido) as asignado_a,
                    a.fecha_asignacion
                FROM inventario i
                LEFT JOIN categorias c ON i.categoria_id = c.id
                LEFT JOIN asignaciones a ON i.id = a.inventario_id AND a.estado = 'asignado'
                LEFT JOIN colaboradores col ON a.colaborador_id = col.id
                WHERE i.categoria_id = ?";
        
        if ($estado === 'disponible') {
            $sql .= " AND a.id IS NULL";
        } elseif ($estado === 'asignado') {
            $sql .= " AND a.id IS NOT NULL";
        }
        
        $sql .= " ORDER BY i.nombre_equipo";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $categoria_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $equipos = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $equipos[] = $row;
            }
        }
        $stmt->close();
        return $equipos;
    }

    // Obtener reporte filtrado para exportación
    public function obtenerReporteFiltrado($categoria_id = null, $estado = null, $fecha_desde = null, $fecha_hasta = null) {
        $sql = "SELECT 
                    i.id,
                    i.nombre_equipo,
                    i.marca,
                    i.modelo,
                    i.numero_serie,
                    c.nombre as categoria,
                    CASE 
                        WHEN a.id IS NOT NULL THEN 'Asignado'
                        ELSE 'Disponible'
                    END as estado_equipo,
                    CONCAT(col.nombre, ' ', col.apellido) as asignado_a,
                    a.fecha_asignacion,
                    i.fecha_ingreso
                FROM inventario i
                LEFT JOIN categorias c ON i.categoria_id = c.id
                LEFT JOIN asignaciones a ON i.id = a.inventario_id AND a.estado = 'asignado'
                LEFT JOIN colaboradores col ON a.colaborador_id = col.id
                WHERE 1=1";
        
        $params = [];
        $types = "";
        
        if ($categoria_id) {
            $sql .= " AND i.categoria_id = ?";
            $params[] = $categoria_id;
            $types .= "i";
        }
        
        if ($estado === 'disponible') {
            $sql .= " AND a.id IS NULL";
        } elseif ($estado === 'asignado') {
            $sql .= " AND a.id IS NOT NULL";
        }
        
        if ($fecha_desde) {
            $sql .= " AND DATE(i.fecha_ingreso) >= ?";
            $params[] = $fecha_desde;
            $types .= "s";
        }
        
        if ($fecha_hasta) {
            $sql .= " AND DATE(i.fecha_ingreso) <= ?";
            $params[] = $fecha_hasta;
            $types .= "s";
        }
        
        $sql .= " ORDER BY c.nombre, i.nombre_equipo";
        
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        $reporte = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reporte[] = $row;
            }
        }
        $stmt->close();
        return $reporte;
    }

    // ===== MÉTODOS DE DESCARTE =====
    
    public function marcarDescarte($inventario_id, $observaciones, $tecnico) {
        try {
            // Primero verificar que el equipo exista y no esté ya en descarte
            $check_sql = "SELECT id, nombre_equipo, estado_descarte FROM inventario WHERE id = ?";
            $check_stmt = $this->conn->prepare($check_sql);
            $check_stmt->bind_param("i", $inventario_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'message' => 'El equipo no existe'];
            }
            
            $equipo = $result->fetch_assoc();
            if ($equipo['estado_descarte'] === 'descarte') {
                return ['success' => false, 'message' => 'El equipo ya está marcado como descarte'];
            }
            
            $check_stmt->close();
            
            // Iniciar transacción
            $this->conn->begin_transaction();
            
            // Si el equipo está asignado, liberarlo primero
            $liberar_sql = "UPDATE asignaciones SET estado = 'devuelto', fecha_devolucion = NOW() 
                           WHERE inventario_id = ? AND estado = 'asignado'";
            $liberar_stmt = $this->conn->prepare($liberar_sql);
            $liberar_stmt->bind_param("i", $inventario_id);
            $liberar_stmt->execute();
            $liberar_stmt->close();
            
            // Marcar equipo como descarte
            $descarte_sql = "UPDATE inventario SET 
                            estado_descarte = 'descarte',
                            fecha_descarte = NOW(),
                            observaciones_descarte = ?,
                            tecnico_descarte = ?
                            WHERE id = ?";
            
            $descarte_stmt = $this->conn->prepare($descarte_sql);
            $descarte_stmt->bind_param("ssi", $observaciones, $tecnico, $inventario_id);
            
            if ($descarte_stmt->execute()) {
                $this->conn->commit();
                $descarte_stmt->close();
                return ['success' => true, 'message' => 'Equipo marcado como descarte correctamente'];
            } else {
                $this->conn->rollback();
                $descarte_stmt->close();
                return ['success' => false, 'message' => 'Error al marcar el equipo como descarte'];
            }
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    public function restaurarDescarte($inventario_id) {
        try {
            $sql = "UPDATE inventario SET 
                    estado_descarte = 'activo',
                    fecha_descarte = NULL,
                    observaciones_descarte = NULL,
                    tecnico_descarte = NULL
                    WHERE id = ? AND estado_descarte = 'descarte'";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $inventario_id);
            
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $stmt->close();
                return ['success' => true, 'message' => 'Equipo restaurado del descarte correctamente'];
            } else {
                $stmt->close();
                return ['success' => false, 'message' => 'No se pudo restaurar el equipo o no estaba en descarte'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    public function obtenerEquiposDescarte() {
        $sql = "SELECT i.*, c.nombre as categoria 
                FROM inventario i
                LEFT JOIN categorias c ON i.categoria_id = c.id
                WHERE i.estado_descarte = 'descarte'
                ORDER BY i.fecha_descarte DESC";
        
        $result = $this->conn->query($sql);
        $equipos = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $equipos[] = $row;
            }
        }
        
        return $equipos;
    }
    
    public function obtenerDetalleDescarte($inventario_id) {
        $sql = "SELECT i.*, c.nombre as categoria 
                FROM inventario i
                LEFT JOIN categorias c ON i.categoria_id = c.id
                WHERE i.id = ? AND i.estado_descarte = 'descarte'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $inventario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $detalle = null;
        if ($result && $result->num_rows > 0) {
            $detalle = $result->fetch_assoc();
        }
        
        $stmt->close();
        return $detalle;
    }
}
?>