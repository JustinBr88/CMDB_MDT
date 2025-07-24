<?php
class Conexion {
    private $servername = "localhost";
    private $username = "root";
    private $password = "1234";
    private $dbname = "cmdb";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
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
}
?>