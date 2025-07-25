<?php
session_start();
include 'loginSesionColaborador.php';
require_once(__DIR__ . '/../conexion.php');

// Validación de sesión colaborador
if (!isset($_SESSION['colaborador_logeado']) || !$_SESSION['colaborador_logeado']) {
    header('Location: ../Usuario/Login.php');
    exit;
}

$conexion = new Conexion();
$colaborador_id = $_SESSION['colaborador_id'];

// Procesar solicitud de donación
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitar_donacion'])) {
    $inventario_id = $_POST['inventario_id'];
    $destinatario = trim($_POST['destinatario']);
    $motivo = trim($_POST['motivo']);
    
    if (empty($destinatario) || strlen($destinatario) < 3) {
        $mensaje = "<div class='alert alert-danger'>El destinatario debe tener al menos 3 caracteres.</div>";
    } elseif (empty($motivo) || strlen($motivo) < 20) {
        $mensaje = "<div class='alert alert-danger'>El motivo debe tener al menos 20 caracteres.</div>";
    } else {
        $resultado = $conexion->procesarSolicitudDonacion($inventario_id, $colaborador_id, $destinatario, $motivo);
        if ($resultado['success']) {
            $mensaje = "<div class='alert alert-success'>" . $resultado['message'] . "</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>" . $resultado['message'] . "</div>";
        }
    }
}

// Obtener equipos asignados al colaborador
$equipos_asignados = $conexion->obtenerEquiposAsignadosColaborador($colaborador_id);

// Debug temporal - mostrar información
echo "<!-- DEBUG: Colaborador ID: $colaborador_id -->";
echo "<!-- DEBUG: Total equipos asignados: " . count($equipos_asignados) . " -->";
foreach ($equipos_asignados as $eq) {
    echo "<!-- DEBUG: Equipo: {$eq['nombre_equipo']}, Estado: {$eq['estado_asignacion']} -->";
}

// Filtrar solo equipos con estado 'asignado' para donación
$equipos_disponibles_donacion = array_filter($equipos_asignados, function($equipo) {
    return $equipo['estado_asignacion'] === 'asignado';
});

echo "<!-- DEBUG: Equipos disponibles para donación: " . count($equipos_disponibles_donacion) . " -->";

include('../navbar_unificado.php');
?>

<!-- Breadcrumb Start -->
<div class="container-fluid">
  <div class="row px-xl-5">
    <div class="col-12">
      <nav class="breadcrumb bg-light mb-30">
        <a class="breadcrumb-item text-dark" href="portal_colaborador.php">Portal Colaborador</a>
        <span class="breadcrumb-item active">Solicitar Donación</span>
      </nav>
    </div>
  </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-heart"></i> Solicitar Donación de Equipo</h2>
            <p class="text-muted">Puedes solicitar donar equipos que tienes asignados a instituciones benéficas, educativas o de desarrollo social.</p>
            
            <?= $mensaje ?>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Información importante sobre donaciones:</strong>
                <ul class="mb-0 mt-2">
                    <li>Solo puedes donar equipos que estén asignados a ti</li>
                    <li>La donación debe ser aprobada por un administrador</li>
                    <li>Una vez aprobada, el equipo sale permanentemente del inventario</li>
                    <li>Debes especificar claramente el destinatario y motivo de la donación</li>
                </ul>
            </div>
            
            <!-- DEBUG: Información visible para usuario -->
            <div class="alert alert-warning" style="font-size: 12px;">
                <strong>🔧 Debug Info:</strong> 
                Colaborador ID: <?= $colaborador_id ?> | 
                Equipos asignados: <?= count($equipos_asignados) ?> | 
                Disponibles para donación: <?= count($equipos_disponibles_donacion) ?>
                <?php if (count($equipos_asignados) > 0): ?>
                    <br><strong>Estados:</strong>
                    <?php foreach ($equipos_asignados as $eq): ?>
                        <?= $eq['nombre_equipo'] ?> (<?= $eq['estado_asignacion'] ?>),
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <?php if (empty($equipos_disponibles_donacion)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> No tienes equipos asignados disponibles para donación.
                    <a href="portal_colaborador.php" class="btn btn-primary btn-sm ms-2">Volver al Portal</a>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5><i class="fas fa-laptop"></i> Mis Equipos Disponibles para Donación (<?= count($equipos_disponibles_donacion) ?>)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Imagen</th>
                                        <th>Equipo</th>
                                        <th>Marca/Modelo</th>
                                        <th>Serie</th>
                                        <th>Fecha Asignación</th>
                                        <th>Valor</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($equipos_disponibles_donacion as $equipo): ?>
                                    <tr>
                                        <td>
                                            <?php 
                                            $imagen_equipo = !empty($equipo['imagen']) ? "../uploads/" . $equipo['imagen'] : "../img/perfil.jpg";
                                            ?>
                                            <img src="<?= htmlspecialchars($imagen_equipo) ?>" width="50" height="50" class="rounded" alt="Imagen equipo">
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($equipo['nombre_equipo']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($equipo['categoria'] ?? 'Sin categoría') ?></small>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($equipo['marca']) ?><br>
                                            <small class="text-muted"><?= htmlspecialchars($equipo['modelo']) ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($equipo['numero_serie']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($equipo['fecha_asignacion'])) ?></td>
                                        <td>
                                            <strong>$<?= number_format($equipo['costo'], 2) ?></strong>
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm btn-donar" 
                                                    data-inventario="<?= $equipo['inventario_id'] ?>"
                                                    data-equipo="<?= htmlspecialchars($equipo['nombre_equipo']) ?>"
                                                    data-serie="<?= htmlspecialchars($equipo['numero_serie']) ?>"
                                                    data-costo="<?= $equipo['costo'] ?>">
                                                <i class="fas fa-heart"></i> Solicitar Donación
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para solicitar donación -->
<div class="modal fade" id="modalSolicitarDonacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-heart"></i> Solicitar Donación de Equipo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-heart"></i>
                        <strong>¡Excelente iniciativa!</strong> Estás contribuyendo a una causa social donando este equipo.
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Equipo a donar:</strong> <span id="equipoNombre"></span></p>
                            <p><strong>Número de serie:</strong> <span id="equipoSerie"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Valor estimado:</strong> $<span id="equipoCosto"></span></p>
                            <p class="text-success"><strong>Impacto social:</strong> Alto</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="destinatario" class="form-label"><strong>Destinatario de la donación *</strong></label>
                        <input type="text" name="destinatario" id="destinatario" class="form-control" 
                               placeholder="Ej: Fundación XYZ, Escuela ABC, Hogar de Ancianos..." required>
                        <div class="form-text">Nombre de la institución, fundación o persona que recibirá el equipo.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="motivo" class="form-label"><strong>Motivo y justificación de la donación *</strong></label>
                        <textarea name="motivo" id="motivo" class="form-control" rows="5" 
                                  placeholder="Describe detalladamente por qué deseas donar este equipo, cómo será utilizado y qué impacto tendrá..." required></textarea>
                        <div class="form-text">Mínimo 20 caracteres. Explica el propósito social o educativo de la donación.</div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Proceso de donación:</strong>
                        <ol class="mb-0 mt-2">
                            <li>Tu solicitud será revisada por un administrador</li>
                            <li>Se evaluará el destinatario y propósito de la donación</li>
                            <li>Si es aprobada, el equipo será preparado para entrega</li>
                            <li>El equipo saldrá permanentemente del inventario de la empresa</li>
                        </ol>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="solicitar_donacion" class="btn btn-success">
                        <i class="fas fa-heart"></i> Enviar Solicitud de Donación
                    </button>
                </div>
                <input type="hidden" name="inventario_id" id="inputInventarioId">
            </form>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar botones de donar
    document.querySelectorAll('.btn-donar').forEach(btn => {
        btn.addEventListener('click', function() {
            const inventarioId = this.getAttribute('data-inventario');
            const equipoNombre = this.getAttribute('data-equipo');
            const equipoSerie = this.getAttribute('data-serie');
            const equipoCosto = this.getAttribute('data-costo');
            
            document.getElementById('equipoNombre').textContent = equipoNombre;
            document.getElementById('equipoSerie').textContent = equipoSerie;
            document.getElementById('equipoCosto').textContent = parseFloat(equipoCosto).toFixed(2);
            document.getElementById('inputInventarioId').value = inventarioId;
            
            // Limpiar formulario
            document.getElementById('destinatario').value = '';
            document.getElementById('motivo').value = '';
            
            new bootstrap.Modal(document.getElementById('modalSolicitarDonacion')).show();
        });
    });
    
    // Validación del formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        const destinatario = document.getElementById('destinatario').value.trim();
        const motivo = document.getElementById('motivo').value.trim();
        
        if (destinatario.length < 3) {
            e.preventDefault();
            alert('El destinatario debe tener al menos 3 caracteres');
            return;
        }
        
        if (motivo.length < 20) {
            e.preventDefault();
            alert('El motivo debe tener al menos 20 caracteres');
            return;
        }
        
        if (!confirm('¿Estás seguro de que deseas solicitar la donación de este equipo? Esta solicitud será revisada por un administrador.')) {
            e.preventDefault();
        }
    });
});
</script>
