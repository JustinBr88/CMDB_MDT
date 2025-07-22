<?php
session_start();
include 'loginSesionColaborador.php';
require_once(__DIR__ . '/../conexion.php');

// Validación de sesión colaborador
if (!isset($_SESSION['colaborador_logeado']) || !$_SESSION['colaborador_logeado']) {
    header('Location: LoginColaborador.php');
    exit;
}
$conexion = new Conexion();

// --- Edición de perfil ---
$edit_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_perfil'])) {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $ubicacion = trim($_POST['ubicacion']);
    $foto_path = null;

    // Validar correo duplicado (excluyendo el suyo)
    if ($conexion->correoDuplicadoColaborador($correo, $_SESSION['colaborador_id'])) {
        $edit_msg = "<div class='alert alert-danger'>El correo ya está registrado por otro colaborador.</div>";
    } else {
        // Validación y procesamiento de foto nueva
        if (!empty($_FILES['foto']['name'])) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif'];
            if (in_array($ext, $allowed) && $_FILES['foto']['size'] <= 2*1024*1024) { // 2MB max
                $foto_path = "fotos/colaboradores/" . uniqid('col_', true) . "." . $ext;
                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $foto_path)) {
                    $edit_msg = "<div class='alert alert-warning'>No se pudo subir la foto. Se mantendrá la anterior.</div>";
                    $foto_path = null;
                }
            } else {
                $edit_msg = "<div class='alert alert-warning'>Formato o tamaño de imagen no válido. Debe ser JPG, PNG o GIF y menor a 2MB.</div>";
                $foto_path = null;
            }
        }

        $ok = $conexion->actualizarPerfilColaborador(
            $_SESSION['colaborador_id'],
            $nombre, $apellido, $correo, $telefono, $direccion, $ubicacion, $foto_path
        );

        if ($ok) {
            $edit_msg = "<div class='alert alert-success'>Perfil actualizado correctamente.</div>";
        } else {
            $edit_msg = "<div class='alert alert-danger'>Error al actualizar el perfil.</div>";
        }
    }
}

// Recargar datos actualizados del colaborador
$colab = $conexion->obtenerColaboradorPorId($_SESSION['colaborador_id']);

// Registrar acceso en historial
$conexion->registrarAccesoColaborador($colab['id']);

// Mensaje de cambio de contraseña
$pass_msg = "";
if (isset($_SESSION['colab_pass_msg'])) {
    $pass_msg = $_SESSION['colab_pass_msg'];
    unset($_SESSION['colab_pass_msg']);
}
?>
<?php include(__DIR__ . '/../navbar.php'); ?>

<!-- Breadcrumb Start -->
<div class="container-fluid">
  <div class="row px-xl-5">
    <div class="col-12">
      <nav class="breadcrumb bg-light mb-30" aria-label="Ruta de navegación portal colaborador">
        <a class="breadcrumb-item text-dark" href="Home.php">Inicio</a>
        <span class="breadcrumb-item active">Portal Colaborador</span>
      </nav>
    </div>
  </div>
</div>
<!-- Breadcrumb End -->

<div class="container mt-5">
  <div class="row">
    <div class="col-md-4 text-center">
      <img src="<?= $colab['foto'] ? htmlspecialchars($colab['foto']) : 'img/default_profile.png' ?>" alt="Foto Perfil" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit:cover;" />
      <h3><?= htmlspecialchars($colab['nombre'] . ' ' . $colab['apellido']) ?></h3>
      <p class="mb-1"><i class="fa fa-envelope"></i> <?= htmlspecialchars($colab['correo']) ?></p>
      <p class="mb-1"><i class="fa fa-phone"></i> <?= htmlspecialchars($colab['telefono']) ?></p>
      <p class="mb-1"><i class="fa fa-map-marker"></i> <?= htmlspecialchars($colab['ubicacion']) ?></p>
      <p class="mb-1"><i class="fa fa-building"></i> <?= htmlspecialchars($colab['direccion']) ?></p>
      <a href="logout_colaborador.php" class="btn btn-danger mt-3" id="btnLogoutColaborador">Cerrar sesión</a>
    </div>

    <div class="col-md-8">
      <?= $edit_msg ?>
      <h4>Editar Perfil</h4>
      <form method="post" enctype="multipart/form-data" class="mb-4" style="max-width:500px;" id="formEditarPerfil">
        <div class="row">
          <div class="col-md-6 mb-2">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($colab['nombre']) ?>" required>
          </div>
          <div class="col-md-6 mb-2">
            <label>Apellido</label>
            <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($colab['apellido']) ?>" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-2">
            <label>Correo</label>
            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($colab['correo']) ?>" required>
          </div>
          <div class="col-md-6 mb-2">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($colab['telefono']) ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-2">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($colab['direccion']) ?>">
          </div>
          <div class="col-md-6 mb-2">
            <label>Ubicación</label>
            <input type="text" name="ubicacion" class="form-control" value="<?= htmlspecialchars($colab['ubicacion']) ?>">
          </div>
        </div>
        <div class="mb-2">
          <label>Cambiar foto de perfil (opcional)</label>
          <input type="file" name="foto" class="form-control">
        </div>
        <button type="submit" name="editar_perfil" class="btn btn-success mb-2">Guardar cambios</button>
      </form>

      <h4>Mis Equipos Asignados</h4>
      <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped">
          <thead class="thead-dark">
            <tr>
              <th>Equipo</th>
              <th>Marca</th>
              <th>Modelo</th>
              <th>Serie</th>
              <th>Fecha de Asignación</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $res = $conexion->getConexion()->query(
              "SELECT i.nombre_equipo, i.marca, i.modelo, i.numero_serie, a.fecha_asignacion, a.estado as estado_asignacion
               FROM inventario i
               JOIN asignaciones a ON i.id = a.inventario_id
               WHERE a.colaborador_id = {$colab['id']} AND a.estado IN ('asignado','dañado','donado')"
            );
            if ($res->num_rows > 0) {
              while($eq = $res->fetch_assoc()) {
                echo "<tr>
                  <td>" . htmlspecialchars($eq['nombre_equipo']) . "</td>
                  <td>" . htmlspecialchars($eq['marca']) . "</td>
                  <td>" . htmlspecialchars($eq['modelo']) . "</td>
                  <td>" . htmlspecialchars($eq['numero_serie']) . "</td>
                  <td>" . htmlspecialchars($eq['fecha_asignacion']) . "</td>
                  <td>" . htmlspecialchars($eq['estado_asignacion']) . "</td>
                </tr>";
              }
            } else {
              echo "<tr><td colspan='6'>No tiene equipos asignados actualmente.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>

      <h4 class="mt-4">Cambiar Contraseña</h4>
      <form method="post" action="cambiar_password_colaborador.php" class="row g-3" style="max-width:400px;">
        <div class="col-12">
          <input type="password" name="oldpass" class="form-control" placeholder="Contraseña actual" required>
        </div>
        <div class="col-12">
          <input type="password" name="newpass" class="form-control" placeholder="Nueva contraseña" required>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary w-100">Actualizar contraseña</button>
        </div>
      </form>
      <?php if($pass_msg): ?>
        <div class="alert alert-info mt-3"><?= $pass_msg ?></div>
      <?php endif; ?>

      <h4 class="mt-5">Historial de Accesos</h4>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="thead-dark">
            <tr>
              <th>Fecha y Hora</th>
              <th>IP</th>
              <th>Navegador</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $historial = $conexion->obtenerHistorialAccesosColaborador($colab['id'], 20);
            if ($historial && count($historial) > 0) {
              foreach ($historial as $h) {
                echo "<tr>
                  <td>" . htmlspecialchars($h['fecha_hora']) . "</td>
                  <td>" . htmlspecialchars($h['ip']) . "</td>
                  <td>" . htmlspecialchars($h['user_agent']) . "</td>
                </tr>";
              }
            } else {
              echo "<tr><td colspan='3'>No hay historial de accesos registrado.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../footer.php'); ?>