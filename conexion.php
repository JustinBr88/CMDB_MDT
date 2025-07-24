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
}
?>