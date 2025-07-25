<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Primero intenta validar como usuario administrador
    $conexion = new Conexion();
    $admin = $conexion->validarUsuario($usuario, $password);
    if ($admin) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = 'admin';
        header('Location: ../Home.php');
        exit;
    }

    // Si no es admin, intenta validar como colaborador
    $colaborador = $conexion->validarColaborador($usuario, $password);
    if ($colaborador) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = 'colab';
        header('Location: ../colaboradores/portal_colaborador.php');
        exit;
    }

    // Si no es ninguno, regresa al login con error
    header('Location: login.php?error=1');
    exit;
}
?>

<?php include(__DIR__ . '/../navbar.php'); ?>
<!-- Breadcrumb Start -->
<div class="container-fluid">
  <div class="row px-xl-5">
    <div class="col-12">
      <nav class="breadcrumb bg-light mb-30" aria-label="Ruta de navegación iniciar sesión">
        <a class="breadcrumb-item text-dark" href="Home.php">Inicio</a>
        <a class="breadcrumb-item text-dark" href="Perfil.php">Perfil</a>
        <span class="breadcrumb-item active">Login</span>
      </nav>
    </div>
  </div>
</div>
<!--formulario inicio sesion-->
<div class="container-fluid d-flex justify-content-center" style="margin-top: 60px;">
  <div class="col-md-4">
    <h2 class="text-center">Iniciar Sesión</h2>
    <form id="loginForm" method="POST">
      <div class="mb-3">
        <input
          type="text"
          id="username"
          name="usuario"
          class="form-control"
          placeholder="Correo / Usuario"
          required
        />
      </div>
      <div class="mb-3 input-group">
        <input
          type="password"
          id="password"
          name="password"
          class="form-control"
          placeholder="Contraseña"
          required
        />
        <span class="input-group-text">
          <img
            src="../img/eye-slash.webp"
            alt="Mostrar/Ocultar"
            style="cursor: pointer; width: 20px; height: 20px"
            class="toggle-password"
            data-target="password"
          />
        </span>
      </div>
      <button type="submit" class="btn btn-dark w-100 mt-3">
        Iniciar Sesión
      </button>
    </form>
  </div>
</div>
<!-- Login End -->
<?php include('footer.php'); ?>
<!-- JavaScript -->
<script src="../js/login.js"></script>