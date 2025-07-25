<?php 
include 'loginSesionColaborador.php';
include('../navbar_unificado.php');
require_once(__DIR__ . '/../conexion.php');

$conexion = new Conexion();
$colaborador_id = $_SESSION['colaborador_id'];

// Obtener datos del colaborador
$colaborador = $conexion->obtenerColaboradorPorId($colaborador_id);

if (!$colaborador) {
    header('Location: ../Usuario/Login.php');
    exit;
}

// Procesar cambio de contraseña si es necesario
$msg_password = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_password'])) {
    $password_actual = $_POST['password_actual'];
    $password_nueva = $_POST['password_nueva'];
    $password_confirmar = $_POST['password_confirmar'];
    
    if ($password_nueva !== $password_confirmar) {
        $msg_password = "<div class='alert alert-danger'>Las contraseñas nuevas no coinciden.</div>";
    } elseif (strlen($password_nueva) < 6) {
        $msg_password = "<div class='alert alert-danger'>La contraseña debe tener al menos 6 caracteres.</div>";
    } else {
        // Verificar contraseña actual
        if ($conexion->verificarPasswordColaborador($colaborador_id, $password_actual)) {
            if ($conexion->cambiarPasswordColaborador($colaborador_id, $password_nueva)) {
                $msg_password = "<div class='alert alert-success'>Contraseña cambiada exitosamente.</div>";
            } else {
                $msg_password = "<div class='alert alert-danger'>Error al cambiar la contraseña.</div>";
            }
        } else {
            $msg_password = "<div class='alert alert-danger'>La contraseña actual es incorrecta.</div>";
        }
    }
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="row">
        <!-- Información del Perfil -->
        <div class="col-xl-8">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Mi Perfil</h6>
                
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="profile-photo-container mb-3">
                            <?php 
                            $foto_url = !empty($colaborador['foto']) ? $colaborador['foto'] : '../img/usuarios/default.jpg';
                            ?>
                            <img src="<?php echo htmlspecialchars($foto_url); ?>" 
                                 class="rounded-circle" 
                                 width="150" height="150" 
                                 style="object-fit: cover; border: 4px solid #007bff;">
                        </div>
                        <h5><?php echo htmlspecialchars($colaborador['nombre'] . ' ' . $colaborador['apellido']); ?></h5>
                        <p class="text-muted">Colaborador</p>
                        <span class="badge bg-info">Activo</span>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Información Personal</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Nombre:</strong></div>
                                    <div class="col-sm-8"><?php echo htmlspecialchars($colaborador['nombre']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Apellido:</strong></div>
                                    <div class="col-sm-8"><?php echo htmlspecialchars($colaborador['apellido']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Identificación:</strong></div>
                                    <div class="col-sm-8"><?php echo htmlspecialchars($colaborador['identificacion']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Correo:</strong></div>
                                    <div class="col-sm-8"><?php echo htmlspecialchars($colaborador['correo']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Teléfono:</strong></div>
                                    <div class="col-sm-8"><?php echo htmlspecialchars($colaborador['telefono'] ?? 'No especificado'); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Dirección:</strong></div>
                                    <div class="col-sm-8"><?php echo htmlspecialchars($colaborador['direccion'] ?? 'No especificada'); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Departamento:</strong></div>
                                    <div class="col-sm-8"><?php echo htmlspecialchars($colaborador['departamento_nombre'] ?? 'Sin asignar'); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="portal_colaborador.php" class="btn btn-primary">
                                <i class="fa fa-edit me-2"></i>Editar Perfil
                            </a>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#cambiarPasswordModal">
                                <i class="fa fa-key me-2"></i>Cambiar Contraseña
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Estadísticas -->
        <div class="col-xl-4">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Mis Estadísticas</h6>
                
                <?php
                // Obtener estadísticas del colaborador
                $stats = $conexion->obtenerEstadisticasColaborador($colaborador_id);
                ?>
                
                <div class="d-flex align-items-center border-bottom py-3">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0">Solicitudes Pendientes</h6>
                            <small class="text-warning"><?php echo $stats['solicitudes_pendientes'] ?? 0; ?></small>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex align-items-center border-bottom py-3">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0">Solicitudes Aprobadas</h6>
                            <small class="text-success"><?php echo $stats['solicitudes_aprobadas'] ?? 0; ?></small>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex align-items-center border-bottom py-3">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0">Equipos Asignados</h6>
                            <small class="text-info"><?php echo $stats['equipos_asignados'] ?? 0; ?></small>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex align-items-center py-3">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0">Entregas Realizadas</h6>
                            <small class="text-primary"><?php echo $stats['entregas_realizadas'] ?? 0; ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Contraseña -->
<div class="modal fade" id="cambiarPasswordModal" tabindex="-1" aria-labelledby="cambiarPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cambiarPasswordModalLabel">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <?php echo $msg_password; ?>
                    
                    <div class="mb-3">
                        <label for="password_actual" class="form-label">Contraseña Actual</label>
                        <input type="password" class="form-control" id="password_actual" name="password_actual" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_nueva" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password_nueva" name="password_nueva" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmar" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password_confirmar" name="password_confirmar" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="cambiar_password" class="btn btn-warning">Cambiar Contraseña</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
