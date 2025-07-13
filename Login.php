
<?php include('navbar.php'); ?>
   <!-- Breadcrumb Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <div class="col-12">
          <nav
            class="breadcrumb bg-light mb-30"
            aria-label="Ruta de navegación iniciar sesión"
          >
            <a class="breadcrumb-item text-dark" href="Home.html">Inicio</a>
            <a class="breadcrumb-item text-dark" href="Perfil.html">Perfil</a>
            <span class="breadcrumb-item active">Login</span>
          </nav>
        </div>
      </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Login Start -->
    <div class="container d-flex justify-content-center align-items-start my-5">
      <div class="col-md-5 mx-3">
        <h2 class="text-center">Registrar Cuenta</h2>
        <form id="loginRST">
          <div class="mb-3">
            <input
              id="name"
              type="text"
              class="form-control"
              placeholder="Nombre"
              required
            />
          </div>
          <div class="mb-3">
            <input
              id="lastname"
              type="text"
              class="form-control"
              placeholder="Apellido"
              required
            />
          </div>
          <div class="mb-3">
            <input
              id="email"
              type="email"
              class="form-control"
              placeholder="Correo"
              required
            />
          </div>
          <div class="mb-3">
            <input
              id="phone"
              type="tel"
              class="form-control"
              placeholder="Teléfono"
            />
          </div>
          <div class="mb-3">
            <input
              id="user"
              type="text"
              class="form-control"
              placeholder="Usuario"
              required
            />
          </div>
          <div class="mb-3 input-group">
            <input
              id="pass"
              type="password"
              class="form-control"
              placeholder="Contraseña"
              minlength="8"
              required
            />
            <span class="input-group-text">
              <img
                src="img/eye-slash.webp"
                alt="Mostrar/Ocultar"
                style="cursor: pointer; width: 20px; height: 20px"
                class="toggle-password"
                data-target="pass"
              />
            </span>
          </div>
          <div class="mb-3 input-group">
            <input
              id="confirm"
              type="password"
              class="form-control"
              placeholder="Confirmar Contraseña"
              minlength="8"
              required
            />
            <span class="input-group-text">
              <img
                src="img/eye-slash.webp"
                alt="Mostrar/Ocultar"
                style="cursor: pointer; width: 20px; height: 20px"
                class="toggle-password"
                data-target="confirm"
              />
            </span>
          </div>
          <button type="submit" class="btn btn-dark w-100">Crear Cuenta</button>
        </form>
      </div>

      <div class="divider"></div>

      <div class="col-md-4 mx-3">
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
