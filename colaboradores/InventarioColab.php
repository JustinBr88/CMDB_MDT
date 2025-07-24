<?php 
require_once '../vendor/autoload.php';
include('navbar.php'); 
?>
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
                    echo "<button class='btn btn-primary btn-sm btn-solicitar' type='button'>Solicitar</button>
                          <button class='btn btn-success btn-sm btn-qr ms-1' type='button'>QR</button>";
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
                    <h5 class="modal-title" id="modalSolicitarLabel">Solicitar equipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Deseas solicitar el equipo <strong><span id="nombreEquipoSolicitado"></span></strong>?</p>
                    
                    <div class="mb-3">
                        <label for="motivoSolicitud" class="form-label"><strong>Motivo de la solicitud:</strong></label>
                        <textarea class="form-control" id="motivoSolicitud" name="motivo" rows="3" 
                                  placeholder="Describe el motivo por el cual necesitas este equipo..." required></textarea>
                        <div class="form-text">Ejemplo: Para desarrollo de proyectos, trabajo remoto, presentaciones, etc.</div>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Tipo de solicitud:</strong> Asignación de equipo
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Enviar Solicitud</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
                <input type="hidden" name="inventario_id" id="inputInventarioId">
                <input type="hidden" name="nombre_equipo" id="inputNombreEquipo">
            </form>
        </div>
    </div>
</div>

<!-- Modal para mostrar QR -->
<div class="modal fade" id="modalQr" tabindex="-1" aria-labelledby="modalQrLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalQrLabel">Código QR del equipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="qrContainer" class="text-center"></div>
                <div id="qrEquipoDatos" class="mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
<?php include('../modelos.php'); ?>

<!-- Usar API de Google Charts para QR (más simple) -->
<script src="../js/enviarSoli.js"></script>
<script src="../js/qrinventario.js"></script>