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
        $stmt = $this->conn->prepare("SELECT * FROM colaboradores WHERE (usuario = ? OR correo = ?) AND activo = 1");
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

    // ...el resto de métodos igual...
}
?>