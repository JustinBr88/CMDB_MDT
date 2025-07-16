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
    }

    public function getConexion() {
        return $this->conn;
    }
    public function validarsuario($usuario, $contrasena) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE nombre = ? ");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario_db = $result->fetch_assoc();
        if ($usuario_db && password_verify($contrasena, $usuario_db['contrasena'])) {
            return $usuario_db;
        }
        return false;
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
    public function obtenerCategoriasyinventario() {
        $sql = "SELECT i.*, c.nombre AS categoria FROM inventario i JOIN categorias c ON i.categoria_id = c.id";
        $result = $this->conn->query($sql);
        $inventario = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $inventario[] = $row;
            }
        }
        return $inventario;
    }
   public function obtenerDepartamentos() {
        $sql = "SELECT id, nombre FROM departamentos";
        $result = $this->conn->query($sql);
        $departamentos = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $departamentos[] = $row;
            }
        }
        return $departamentos;
    }
    public function obtenercolaboradores() {
        $sql = "SELECT c.*, d.nombre as dep_nombre FROM colaboradores c LEFT JOIN departamentos d ON c.departamento_id = d.id";
        $result = $this->conn->query($sql);
        $colaboradores = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $colaboradores[] = $row;
            }
        }
        return $colaboradores;
    }
    public function obtenerCategorias() {
        $sql = "SELECT * FROM categorias";
        $result = $this->conn->query($sql);
        $categorias = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categorias[] = $row;
            }
        }
        return $categorias;
    }
    public function obtenerUsuarios() {
        $sql = "SELECT id, nombre FROM usuarios";
        $result = $this->conn->query($sql);
        $usuarios = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }
        return $usuarios;
    }

}
?>