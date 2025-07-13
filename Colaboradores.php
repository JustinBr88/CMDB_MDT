<?php include('loginSesion.php'); ?>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Colaboradores</h2>
    <!-- Formulario de Alta -->
    <form action="Colaboradores.php" method="post" class="mb-4" enctype="multipart/form-data">
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
                    $conn = new mysqli("localhost", "root", "", "cmdb");
                    $result = $conn->query("SELECT id, nombre FROM departamentos");
                    while($row = $result->fetch_assoc()){
                        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                    }
                    ?>
                </select>
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
                <th>ID</th><th>Nombre</th><th>Apellido</th><th>ID</th>
                <th>Foto</th><th>Dirección</th><th>Ubicación</th>
                <th>Teléfono</th><th>Correo</th><th>Departamento</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT c.*, d.nombre as dep_nombre FROM colaboradores c LEFT JOIN departamentos d ON c.departamento_id = d.id");
            while($row = $result->fetch_assoc()){
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['apellido']}</td>
                    <td>{$row['identificacion']}</td>
                    <td>".($row['foto'] ? "<img src='{$row['foto']}' style='width:40px;'>" : "")."</td>
                    <td>{$row['direccion']}</td>
                    <td>{$row['ubicacion']}</td>
                    <td>{$row['telefono']}</td>
                    <td>{$row['correo']}</td>
                    <td>{$row['dep_nombre']}</td>
                </tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>
<?php include('footer.php'); ?>