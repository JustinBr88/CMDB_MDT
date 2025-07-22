<?php include('loginSesion.php'); ?>
<?php include('navbar.php'); ?>
<div class="container mt-5">
    <h2>Inventario - Equipos y Software</h2>
    <!-- Formulario de Alta -->
    <form action="Inventario.php" method="post" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label>Nombre del equipo</label>
                <input type="text" name="nombre_equipo" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Categoría</label>
                <select name="categoria_id" class="form-control" required>
                    <?php
                    // Cargar categorías
                    include('conexion.php');
                     $conexion = new Conexion();
                    $result = $conexion->obtenerCategorias();
                    foreach ($result as $row) {
                        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>Marca</label>
                <input type="text" name="marca" class="form-control">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4">
                <label>Modelo</label>
                <input type="text" name="modelo" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Número de Serie</label>
                <input type="text" name="numero_serie" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Costo</label>
                <input type="number" step="0.01" name="costo" class="form-control">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4">
                <label>Fecha de ingreso</label>
                <input type="date" name="fecha_ingreso" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Tiempo de depreciación (meses)</label>
                <input type="number" name="tiempo_depreciacion" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="activo">Activo</option>
                    <option value="baja">Baja</option>
                    <option value="reparacion">En reparación</option>
                    <option value="descarte">En descarte</option>
                    <option value="donado">Donado</option>
                    <option value="inventario">Inventario</option>
                </select>
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit" name="crear">Agregar al inventario</button>
        </div>
    </form>
    <!-- Tabla de Inventario -->
    <table class="table table-bordered table-striped mt-4">
        <thead class="thead-dark">
            <tr>
                <th>ID</th><th>Nombre</th><th>Categoría</th><th>Marca</th>
                <th>Modelo</th><th>Serie</th><th>Costo</th><th>Ingreso</th><th>Depreciación</th><th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conexion->obtenerCategoriasyinventario();
            foreach ($result as $row) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nombre_equipo']}</td>
                    <td>{$row['categoria']}</td>
                    <td>{$row['marca']}</td>
                    <td>{$row['modelo']}</td>
                    <td>{$row['numero_serie']}</td>
                    <td>{$row['costo']}</td>
                    <td>{$row['fecha_ingreso']}</td>
                    <td>{$row['tiempo_depreciacion']}</td>
                    <td>{$row['estado']}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include('footer.php'); ?>