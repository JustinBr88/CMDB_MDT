<?php
header('Content-Type: application/json');

if (isset($_GET['action']) && $_GET['action'] === 'getCategorias') {
    
    $servername = "localhost";
    $username = "root";
    $password = "12345";
    $dbname = "cmdb";
    
    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            echo json_encode(['error' => 'Error de conexión: ' . $conn->connect_error]);
            exit;
        }
        
        $sql = "SELECT id, nombre, descripcion FROM categorias ORDER BY nombre ASC";
        $result = $conn->query($sql);
        
        if ($result) {
            $categorias = [];
            while ($row = $result->fetch_assoc()) {
                $categorias[] = $row;
            }
            echo json_encode($categorias);
        } else {
            echo json_encode(['error' => 'Error en consulta: ' . $conn->error]);
        }
        
        $conn->close();
        
    } catch (Exception $e) {
        echo json_encode(['error' => 'Excepción: ' . $e->getMessage()]);
    }
    
    exit;
}

echo json_encode(['error' => 'Acción no válida']);
?>
