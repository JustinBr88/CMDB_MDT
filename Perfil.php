<?php include('loginSesion.php'); ?>
<?include('navbar.php'); ?>

    <!-- Breadcrumb Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <div class="col-12">
          <nav
            class="breadcrumb bg-light mb-30"
            aria-label="Ruta de navegación perfil"
          >
            <a class="breadcrumb-item text-dark" href="Home.html">Inicio</a>
            <span class="breadcrumb-item active">Perfil</span>
          </nav>
        </div>
      </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Perfil Start -->
    <div class="container my-5">
      <h2 class="text-center">Mi Cuenta</h2>
      <div class="d-flex justify-content-between align-items-center my-4">
        <div class="d-flex align-items-center">
          <img
            src="img\perfil.jpg"
            alt="Perfil"
            class="rounded-circle"
            style="width: 100px; height: 100px"
          />
          <div class="ms-3" style="padding-left: 20px">
            <h4 id="user_name">Default</h4>
            <p id="user_email"></p>
            <p id="user_phone"></p>
          </div>
        </div>
        <div>
          <h5>Direcciones</h5>
          <ul id="user_addresses">
            <li></li>
          </ul>
        </div>
      </div>

      <h3 class="text-center my-4">Mis Pedidos</h3>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="bg-light">
            <tr>
              <th>#Pedido</th>
              <th>Total</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody id="orders_table_body">
            <!-- Generación de filas pedidos -->
          </tbody>
        </table>
      </div>
      
      <div class="text-center mt-4">
        <button id="logout_button" class="btn btn-danger">Cerrar sesión</button>
      </div>
    </div>
    <!-- Perfil End -->

  <?php include('footer.php'); ?>
    <!-- JavaScript -->
    <script type="module" src="js/main.js"></script>
    <script type="module" src="js/perfil.js"></script>
  </body>
</html>
