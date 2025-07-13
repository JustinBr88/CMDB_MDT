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
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE usuario = ? ");
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

}
?>