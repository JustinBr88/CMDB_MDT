<?php include('loginSesion.php'); ?>
<?php include('navbar.php'); ?>
<div class="container mt-5">
    <h2>Inventario disponible para solicitud</h2>
    <table class="table table-bordered table-striped mt-4">
        <thead class="thead-dark">
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Serie</th>
                <th>Costo</th>
                <th>Ingreso</th>
                <th>Depreciación</th>
                <th>Solicitar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include('conexion.php');
            $conexion = new Conexion();
            // Mostrar solo los estados permitidos
            $result = $conexion->obtenerInventarioDisponible();
            foreach ($result as $row) {
                echo "<tr>
                    <td>";
                if (!empty($row['imagen'])) {
                    echo "<img src='uploads/{$row['imagen']}' width='60'>";
                } else {
                    echo "<img src='img/equipo.jpg' width='60'>";
                }
                echo "</td>
                    <td>{$row['nombre_equipo']}</td>
                    <td>{$row['categoria']}</td>
                    <td>{$row['marca']}</td>
                    <td>{$row['modelo']}</td>
                    <td>{$row['numero_serie']}</td>
                    <td>{$row['costo']}</td>
                    <td>{$row['fecha_ingreso']}</td>
                    <td>{$row['tiempo_depreciacion']}</td>
                    <td>
                        <form action='solicitar_equipo.php' method='post'>
                            <input type='hidden' name='inventario_id' value='{$row['id']}'>
                            <button class='btn btn-primary btn-sm' type='submit'>Solicitar</button>
                        </form>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include('footer.php'); ?>