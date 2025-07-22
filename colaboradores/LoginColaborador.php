<?php
session_start();
require_once(__DIR__ . '/../conexion.php');

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $contrasena = $_POST['contrasena'];

    $conexion = new Conexion();
    $colaborador = $conexion->validarColaborador($usuario, $contrasena);

    if ($colaborador) {
        $_SESSION['colaborador_logeado'] = true;
        $_SESSION['colaborador_id'] = $colaborador['id'];
        $_SESSION['colaborador_nombre'] = $colaborador['nombre'];
        $_SESSION['colaborador_foto'] = $colaborador['foto'];
        header('Location: portal_colaborador.php');
        exit;
    } else {
        $msg = "<div class='alert alert-danger'>Usuario o contraseña incorrectos.</div>";
    }
}
?>

<?php include(__DIR__ . '/../navbar.php'); ?>

<!-- Breadcrumb Start -->
<div class="container-fluid">
  <div class="row px-xl-5">
    <div class="col-12">
      <nav class="breadcrumb bg-light mb-30" aria-label="Ruta de navegación iniciar sesión colaborador">
        <a class="breadcrumb-item text-dark" href="Home.php">Inicio</a>
        <span class="breadcrumb-item active">Login Colaborador</span>
      </nav>
    </div>
  </div>
</div>
<!-- Breadcrumb End -->

<div class="container d-flex justify-content-center" style="margin-top: 60px;">
    <div class="col-md-5">
        <h2 class="text-center mb-4">Portal de Colaboradores</h2>
        <?= $msg ?>
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" id="usuario" name="usuario" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
    </div>
</div>

<?php include(__DIR__ . '/../footer.php'); ?>