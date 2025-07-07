<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <title>MD Tecnología - Productos</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
      rel="stylesheet"
    />

    <!-- Font Awesome -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      rel="stylesheet"
    />

    <link href="css/style.css" rel="stylesheet" />
  </head>

  <body>
    <!-- Header Start -->
<header>
    <div
      class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex"
      style="margin-right: 0px"
    >
      <div class="col-lg-4">
        <a href="Home.html" class="text-decoration-none">
          <img src="img\logo.png" alt="logo" />
        </a>
      </div>
      <div class="col-lg-4 col-6 text-left">
        <form
          id="searchForm"
          action="https://www.google.com/search"
          method="get"
          target="_blank"
        >
          <div class="input-group">
            <input
              type="text"
              class="form-control"
              name="q"
              placeholder="Buscar en Google..."
              required
            />
            <div class="input-group-append">
              <button
                id="clearButton"
                type="button"
                class="input-group-text bg-transparent text-primary"
                style="border: none; cursor: pointer"
              >
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-lg-4 col-6 text-right">
        <p class="m-0">Nuestro Instagram</p>
        <a><h5 class="m-0">@mdtecnologiapa</h5></a>
      </div>
    </div>
    <!-- Header End -->

    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
      <div class="row px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
          <a
            class="btn d-flex align-items-center justify-content-between bg-primary w-100"
            data-toggle="collapse"
            href="#navbar-vertical"
            style="height: 65px; padding: 0 30px"
          >
            <h6 class="text-dark m-0">
              <i class="fa fa-bars mr-2"></i>Categorías
            </h6>
            <i class="fa fa-angle-down text-dark"></i>
          </a>
          <nav
            class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light"
            id="navbar-vertical"
            style="width: calc(100% - 30px); z-index: 999"
            aria-label="Navegación de categorías"
          >
            <div class="navbar-nav w-100" id="navbar-categorias">
              <!-- Categorías generadas de forma dinámica -->
            </div>
          </nav>
        </div>
        <div class="col-lg-9">
          <nav
            class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0"
            aria-label="Barra de navegación principal"
          >
            <a href="Home.html" class="text-decoration-none d-block d-lg-none">
              <img src="img\logoMDT.png" alt="logo" />
            </a>
            <button
              type="button"
              class="navbar-toggler"
              data-toggle="collapse"
              data-target="#navbarCollapse"
            >
              <span class="navbar-toggler-icon"></span>
            </button>
            <div
              class="collapse navbar-collapse justify-content-between"
              id="navbarCollapse"
            >
              <div class="navbar-nav mr-auto py-0">
                <a href="Home.html" class="nav-item nav-link">Home</a>
                <a href="Productos.html" class="nav-item nav-link">Productos</a>
                <a href="Nosotros.html" class="nav-item nav-link"
                  >Sobre Nosotros</a
                >
              </div>
              <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
                <a href="Perfil.html" class="btn px-0">
                  <i
                    class="fa-solid fa-circle-user text-primary"
                    style="font-size: 1.3rem"
                  ></i>
                </a>
                <a href="Carrito.html" class="btn px-0 ml-3">
                  <i class="fas fa-shopping-cart text-primary"></i>
                  <span
                    class="badge text-secondary border border-secondary rounded-circle"
                    style="padding-bottom: 2px"
                    id="cart-total"
                    >0</span
                  >
                </a>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </div>
</header>
    <!-- Navbar End -->

    <!-- Breadcrumb Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <div class="col-12">
          <nav
            class="breadcrumb bg-light mb-30"
            aria-label="Ruta de Navegación productos"
          >
            <a class="breadcrumb-item text-dark" href="Home.html">Inicio</a>
            <span class="breadcrumb-item active">Productos</span>
          </nav>
        </div>
      </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Contenedor de filtros y productos -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <!-- Filtros -->
        <div class="col-lg-3 col-md-4">
          <!-- Filtro por Categorías -->
          <div class="bg-light p-4 mb-30">
            <h5 class="section-title position-relative text-uppercase mb-3">
              <span class="bg-secondary pr-3">Filtrar por Categorías</span>
            </h5>
            <form>
              <div id="type-filter-container">
                <!-- Se cargan los filtros de forma dinámica -->
              </div>
              <!-- Botón Confirmar Filtro -->
              <button type="button" class="btn btn-primary mt-3 w-100">
                Filtrar
              </button>
            </form>
          </div>
        </div>
        <!-- Filtros - END -->

        <!-- Productos -->
        <div class="col-lg-9 col-md-8">
          <div class="row pb-3" id="filtcontiner"></div>
          <!-- Productos generados aquí de forma dinámica -->
          <!-- Botones de cambio de página -->
          <div class="col-12">
            <nav aria-label="Navegación de paginas productos">
              <ul class="pagination justify-content-center">
                <!-- Se carga de forma dinámica los botones de paginación -->
              </ul>
            </nav>
          </div>
        </div>
        <!-- Productos - END -->
      </div>
    </div>

    <!-- Footer Start -->
</footer>
    <div class="container-fluid bg-dark text-secondary mt-5 pt-5">
      <div class="row px-xl-5 pt-5">
        <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
          <h5 class="text-secondary text-uppercase mb-4">Contáctenos</h5>
          <p class="mb-2">
            <i class="fa fa-envelope text-primary mr-3"></i>mdtpanama@gmail.com
          </p>
          <p class="mb-0">
            <i class="fa fa-phone-alt text-primary mr-3"></i>+507 6030-9572
          </p>
        </div>
        <div class="col-lg-8 col-md-12">
          <div class="row">
            <div class="col-md-4 mb-5">
              <h5 class="text-secondary text-uppercase mb-4">MD Tech</h5>
              <div class="d-flex flex-column justify-content-start">
                <a class="text-secondary mb-2" href="Home.html"
                  ><i class="fa fa-angle-right mr-2"></i>Inicio</a
                >
                <a class="text-secondary mb-2" href="Productos.html"
                  ><i class="fa fa-angle-right mr-2"></i>Productos</a
                >
                <a class="text-secondary" href="Nosotros.html"
                  ><i class="fa fa-angle-right mr-2"></i>Sobre Nosotros</a
                >
              </div>
            </div>
            <div class="col-md-4 mb-5">
              <h5 class="text-secondary text-uppercase mb-4">Mi cuenta</h5>
              <div class="d-flex flex-column justify-content-start">
                <a class="text-secondary mb-2" href="Login.html"
                  ><i class="fa fa-angle-right mr-2"></i>Iniciar Sesión</a
                >
                <a class="text-secondary mb-2" href="Carrito.html"
                  ><i class="fa fa-angle-right mr-2"></i>Ver Carrito</a
                >
                <a class="text-secondary mb-2" href="Perfil.html"
                  ><i class="fa fa-angle-right mr-2"></i>Perfil</a
                >
              </div>
            </div>
            <div class="col-md-4 mb-5">
              <h6 class="text-secondary text-uppercase mt-4 mb-3">Síguenos</h6>
              <div class="d-flex">
                <a
                  class="btn btn-primary btn-square mr-2"
                  href="https://www.facebook.com/profile.php?id=100069208843188"
                  ><i class="fa-brands fa-facebook fa-lg"></i
                ></a>
                <a
                  class="btn btn-primary btn-square"
                  href="https://www.instagram.com/mdtecnologiapa?igsh=ZHdrNXoxeXZjaXBh"
                  ><i class="fa-brands fa-instagram fa-xl"></i
                ></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</footer>
    <!-- Footer End -->

    <!-- JavaScript -->
    <script type="module" src="js/main.js"></script>
    <script type="module" src="js/productos.js"></script>
  </body>
</html>
