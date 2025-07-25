<?php 
include 'loginSesionColaborador.php';
include('../navbar_unificado.php');
require_once(__DIR__ . '/../conexion.php');
$conexion = new Conexion();

// --- Procesamiento del formulario ---
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    // Validación de identificación, usuario y correo únicos
    $identificacion = trim($_POST['identificacion']);
    $usuario = trim($_POST['usuario']);
    $correo = trim($_POST['correo']);

    if ($conexion->existeIdentificacionColaborador($identificacion)) {
        $msg = "<div class='alert alert-danger'>La identificación ya existe.</div>";
    } elseif ($conexion->existeUsuarioColaborador($usuario)) {
        $msg = "<div class='alert alert-danger'>El nombre de usuario ya existe. Elija otro nombre de usuario.</div>";
    } elseif ($conexion->existeCorreoColaborador($correo)) {
        $msg = "<div class='alert alert-danger'>El correo electrónico ya está registrado por otro colaborador.</div>";
    } else {
        // Procesar foto
        $foto_path = '';
        if (!empty($_FILES['foto']['name'])) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif'];
            if (in_array($ext, $allowed) && $_FILES['foto']['size'] <= 2*1024*1024) { // 2MB max
                $foto_path = "fotos/colaboradores/" . uniqid('col_', true) . "." . $ext;
                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $foto_path)) {
                    $msg = "<div class='alert alert-warning'>No se pudo subir la foto. Se registrará sin foto.</div>";
                    $foto_path = '';
                }
            } else {
                $msg = "<div class='alert alert-warning'>Formato o tamaño de imagen no válido. Debe ser JPG, PNG o GIF y menor a 2MB.</div>";
                $foto_path = '';
            }
        }
        // Insertar colaborador
        $ok = $conexion->insertarColaborador(
            $_POST['nombre'], $_POST['apellido'], $identificacion, $foto_path,
            $_POST['direccion'], $_POST['ubicacion'], $_POST['telefono'],
            $correo, $_POST['departamento_id'],
            $usuario, $_POST['contrasena']
        );
        if ($ok) {
            $msg = "<div class='alert alert-success'>Colaborador agregado exitosamente.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Error al agregar colaborador.</div>";
        }
    }
}
?>
<div class="container mt-5">
    <h2>Colaboradores</h2>
    <?= $msg ?>
    <!-- Formulario de Alta -->
    <form action="Colaboradores.php" method="post" class="mb-4" enctype="multipart/form-data" id="formAgregarColaborador">
        <div class="row">
            <div class="col-md-4">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Apellido</label>
                <input type="text" name="apellido" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Identificación</label>
                <input type="text" name="identificacion" class="form-control" required>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Dirección</label>
                <input type="text" name="direccion" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Ubicación (Ej: Edificio 303)</label>
                <input type="text" name="ubicacion" class="form-control">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Correo electrónico</label>
                <input type="email" name="correo" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Departamento</label>
                <select name="departamento_id" class="form-control" required>
                    <?php
                    $departamentos = $conexion->obtenerDepartamentos();
                    foreach ($departamentos as $row) {
                        echo "<option value='{$row['id']}'>".htmlspecialchars($row['nombre'])."</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4">
                <label>Usuario para login</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control" required>
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit" name="crear">Agregar colaborador</button>
        </div>
    </form>
    <!-- Tabla de Colaboradores -->
    <table class="table table-bordered table-striped mt-4">
        <thead class="thead-dark">
            <tr>
                <th>ID</th><th>Nombre</th><th>Apellido</th><th>Identificación</th>
                <th>Foto</th><th>Dirección</th><th>Ubicación</th>
                <th>Teléfono</th><th>Correo</th><th>Departamento</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $colaboradores = $conexion->obtenerColaboradores();
            foreach ($colaboradores as $row) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>" . htmlspecialchars($row['nombre'] ?? '') . "</td>
                    <td>" . htmlspecialchars($row['apellido'] ?? '') . "</td>
                    <td>" . htmlspecialchars($row['identificacion'] ?? '') . "</td>
                    <td>".($row['foto'] ? "<img src='".htmlspecialchars($row['foto'])."' style='width:40px;'>" : "")."</td>
                    <td>" . htmlspecialchars($row['direccion'] ?? '') . "</td>
                    <td>" . htmlspecialchars($row['ubicacion'] ?? '') . "</td>
                    <td>" . htmlspecialchars($row['telefono'] ?? '') . "</td>
                    <td>" . htmlspecialchars($row['correo'] ?? '') . "</td>
                    <td>" . htmlspecialchars($row['departamento_nombre'] ?? 'Sin departamento') . "</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include('footer.php'); ?>