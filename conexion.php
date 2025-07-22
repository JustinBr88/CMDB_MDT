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

    // --- COLABORADORES: FUNCIONES START ---

    public function existeIdentificacionColaborador($identificacion) {
        $stmt = $this->conn->prepare("SELECT id FROM colaboradores WHERE identificacion = ?");
        $stmt->bind_param("s", $identificacion);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    public function insertarColaborador($nombre, $apellido, $identificacion, $foto, $direccion, $ubicacion, $telefono, $correo, $departamento_id, $usuario, $contrasena) {
        if ($this->existeIdentificacionColaborador($identificacion)) {
            return false;
        }
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "INSERT INTO colaboradores (nombre, apellido, identificacion, foto, direccion, ubicacion, telefono, correo, departamento_id, usuario, contrasena, activo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)"
        );
        $stmt->bind_param("sssssssssss",
            $nombre, $apellido, $identificacion, $foto, $direccion, $ubicacion, $telefono, $correo, $departamento_id, $usuario, $hash
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function editarColaborador($id, $nombre, $apellido, $foto, $direccion, $ubicacion, $telefono, $correo, $departamento_id, $usuario) {
        $stmt = $this->conn->prepare(
            "UPDATE colaboradores SET nombre=?, apellido=?, foto=?, direccion=?, ubicacion=?, telefono=?, correo=?, departamento_id=?, usuario=? WHERE id=?"
        );
        $stmt->bind_param("sssssssssi", $nombre, $apellido, $foto, $direccion, $ubicacion, $telefono, $correo, $departamento_id, $usuario, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function bajaColaborador($id) {
        $stmt = $this->conn->prepare("UPDATE colaboradores SET activo = 0 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function obtenerColaboradorPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM colaboradores WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $colaborador = $res->fetch_assoc();
        $stmt->close();
        return $colaborador;
    }

    // Obtener todos los colaboradores con nombre del departamento
    public function obtenerColaboradores() {
        $sql = "SELECT c.*, d.nombre AS dep_nombre 
                FROM colaboradores c 
                LEFT JOIN departamentos d ON c.departamento_id = d.id
                WHERE c.activo = 1";
        $result = $this->conn->query($sql);
        $colaboradores = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $colaboradores[] = $row;
            }
        }
        return $colaboradores;
    }

    // Registrar acceso del colaborador en historial
    public function registrarAccesoColaborador($id) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $stmt = $this->conn->prepare("INSERT INTO historial_accesos_colaborador (colaborador_id, fecha_hora, ip, user_agent) VALUES (?, NOW(), ?, ?)");
        $stmt->bind_param("iss", $id, $ip, $user_agent);
        $stmt->execute();
        $stmt->close();
    }

    // Obtener historial de accesos (con límite)
    public function obtenerHistorialAccesosColaborador($id, $limit = 20) {
        $stmt = $this->conn->prepare("SELECT fecha_hora, ip, user_agent FROM historial_accesos_colaborador WHERE colaborador_id = ? ORDER BY fecha_hora DESC LIMIT ?");
        $stmt->bind_param("ii", $id, $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        $historial = [];
        while ($row = $res->fetch_assoc()) {
            $historial[] = $row;
        }
        $stmt->close();
        return $historial;
    }

    // Validación de login colaborador
    public function validarColaborador($usuario, $contrasena) {
        $stmt = $this->conn->prepare("SELECT * FROM colaboradores WHERE usuario = ? AND activo = 1");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $colaborador = $result->fetch_assoc();
        $stmt->close();
        if ($colaborador && password_verify($contrasena, $colaborador['contrasena'])) {
            return $colaborador;
        }
        return false;
    }

    // Verifica si el correo está duplicado (en otro colaborador)
    public function correoDuplicadoColaborador($correo, $id) {
        $stmt = $this->conn->prepare("SELECT id FROM colaboradores WHERE correo = ? AND id <> ?");
        $stmt->bind_param("si", $correo, $id);
        $stmt->execute();
        $stmt->store_result();
        $duplicado = $stmt->num_rows > 0;
        $stmt->close();
        return $duplicado;
    }

    // Actualizar datos de perfil del colaborador (excepto usuario, identificación, contraseña)
    public function actualizarPerfilColaborador($id, $nombre, $apellido, $correo, $telefono, $direccion, $ubicacion, $foto_path = null) {
        if ($foto_path) {
            $stmt = $this->conn->prepare(
                "UPDATE colaboradores SET nombre=?, apellido=?, correo=?, telefono=?, direccion=?, ubicacion=?, foto=? WHERE id=?"
            );
            $stmt->bind_param("sssssssi", $nombre, $apellido, $correo, $telefono, $direccion, $ubicacion, $foto_path, $id);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE colaboradores SET nombre=?, apellido=?, correo=?, telefono=?, direccion=?, ubicacion=? WHERE id=?"
            );
            $stmt->bind_param("ssssssi", $nombre, $apellido, $correo, $telefono, $direccion, $ubicacion, $id);
        }
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function actualizarPasswordColaborador($id, $newpass) {
        $hash = password_hash($newpass, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE colaboradores SET contrasena = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

        public function existeUsuarioColaborador($usuario) {
        $stmt = $this->conn->prepare("SELECT id FROM colaboradores WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    public function existeCorreoColaborador($correo) {
        $stmt = $this->conn->prepare("SELECT id FROM colaboradores WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    // --- COLABORADORES: FUNCIONES END ---

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