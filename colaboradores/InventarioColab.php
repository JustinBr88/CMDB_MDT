<?php include('navbar.php'); ?>
<div class="container mt-5">
    <h2>Inventario disponible para solicitud</h2>
    <table class="table table-bordered table-striped mt-4" id="tabla-inventario-colab">
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
            include('../conexion.php');
            $conexion = new Conexion();
            // Mostrar solo los equipos en estado activo o inventario
            $result = $conexion->obtenerInventarioDisponible(); // Debe filtrar por estado activo/inventario
            foreach ($result as $row) {
                echo "<tr data-id='{$row['id']}' data-nombre='{$row['nombre_equipo']}'>
                    <td>";
                if (!empty($row['imagen'])) {
                    echo "<img src='../uploads/{$row['imagen']}' width='60'>";
                } else {
                    echo "<img src='../img/equipo.jpg' width='60'>";
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
                    <td>";
                if ($row['estado'] === "activo" || $row['estado'] === "inventario") {
                    // Solo muestra el botón si el estado lo permite
                    echo "<button class='btn btn-primary btn-sm btn-solicitar' type='button'>Solicitar</button>";
                } else {
                    echo "<span class='text-muted'>No disponible</span>";
                }
                echo "</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal de confirmación de solicitud -->
<div class="modal fade" id="modalSolicitar" tabindex="-1" aria-labelledby="modalSolicitarLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formSolicitar">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSolicitarLabel">Confirmar solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Seguro que deseas solicitar el equipo <span id="nombreEquipoSolicitado"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Confirmar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
                <input type="hidden" name="inventario_id" id="inputInventarioId">
            </form>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>

<script src="../js/enviarSoli.js"></script>