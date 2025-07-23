<?php include('loginSesion.php'); ?>
<?php include('navbar.php'); ?>
<div class="container mt-5">
    <h2>Usuarios del Sistema</h2>
    <!-- Formulario de Alta -->
    <form action="Usuarios.php" method="post" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Correo</label>
                <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Rol</label>
                <select name="rol" class="form-control" required>
                    <option value="admin">Administrador</option>
                    <option value="tecnico">Técnico</option>
                </select>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Activo</label>
                <select name="activo" class="form-control">
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit" name="crear">Agregar usuario</button>
        </div>
    </form>
    <!-- Tabla de Usuarios -->
    <table class="table table-bordered table-striped mt-4">
        <thead class="thead-dark">
            <tr>
                <th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Activo</th><th>Fecha Creación</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once('conexion.php');
            $conexion = new Conexion();
            $result = $conexion->obtenerUsuarios();
            foreach ($result as $row) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['correo']}</td>
                    <td>{$row['rol']}</td>
                    <td>".($row['activo'] ? "Sí" : "No")."</td>
                    <td>{$row['fecha_creacion']}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include('footer.php'); ?>