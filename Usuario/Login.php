<?php include('navbar.php'); ?>
   <!-- Breadcrumb Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <div class="col-12">
          <nav
            class="breadcrumb bg-light mb-30"
            aria-label="Ruta de navegación iniciar sesión"
          >
            <a class="breadcrumb-item text-dark" href="Home.php">Inicio</a>
            <a class="breadcrumb-item text-dark" href="Perfil.php">Perfil</a>
            <span class="breadcrumb-item active">Login</span>
          </nav>
        </div>
      </div>
    </div>
    <!--formulario inisio sesion-->
      <div class="container-fluid d-flex justify-content-center" style="margin-top: 60px;">
        <div class="col-md-4">
          <h2 class="text-center">Iniciar Sesión</h2>
          <form id="loginForm" method="POST">
            <div class="mb-3">
              <input
                type="text"
                id="username"
                class="form-control"
                placeholder="Correo / Usuario"
                required
              />
            </div>
            <div class="mb-3 input-group">
              <input
                type="password"
                id="password"
                class="form-control"
                placeholder="Contraseña"
                required
              />
              <span class="input-group-text">
                <img
                  src="img/eye-slash.webp"
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
<footer>
    <!-- Footer Start -->
  <?php include('footer.php'); ?>
    <!-- Footer End -->
    <!-- JavaScript -->
    <script type="module" src="js/login.js"></script>
  </body>
</html>
