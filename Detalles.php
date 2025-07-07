<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <title>MD Tecnología - Detalles</title>
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
    <!-- Navbar End -->
</header>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <div class="col-12">
          <nav
            class="breadcrumb bg-light mb-30"
            aria-label="Ruta de navegación producto detalle"
          >
            <a class="breadcrumb-item text-dark" href="Home.html">Inicio</a>
            <a class="breadcrumb-item text-dark" href="Productos.html"
              >Productos</a
            >
            <span id="producto-name-breadcrumb" class="breadcrumb-item active"
              >Detalle</span
            >
          </nav>
        </div>
      </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Detail Start -->
    <div class="container-fluid pb-5">
      <div class="row px-xl-5">
        <div class="col-lg-5 mb-30">
          <div id="carousel" class="carousel">
            <div class="carousel-inner" id="carousel-inner"></div>
            <button class="carousel-control-prev" id="prev">
              <i class="fa fa-2x fa-angle-left text-dark"></i>
            </button>
            <button class="carousel-control-next" id="next">
              <i class="fa fa-2x fa-angle-right text-dark"></i>
            </button>
          </div>
        </div>

        <div class="col-lg-7 h-auto mb-30">
          <div class="h-100 bg-light p-30">
            <h3 id="product-name">Nombre</h3>
            <h5 id="product-brand" class="font-weight-semi-bold mb-4">Marca</h5>
            <h3 id="product-price" class="font-weight-bold mb-4">Precio</h3>

            <div class="d-flex align-items-center mb-4 pt-2">
              <div class="input-group quantity mr-3" style="width: 130px">
                <div class="input-group-btn">
                  <button class="btn btn-primary btn-minus">
                    <i class="fa fa-minus"></i>
                  </button>
                </div>
                <input
                  type="text"
                  class="form-control bg-secondary border-0 text-center"
                  id="product-amount"
                  name="product-amount"
                  value="1"
                />
                <div class="input-group-btn">
                  <button class="btn btn-primary btn-plus">
                    <i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>
              <button class="btn btn-primary px-3" id="add-cart">
                <i class="fa fa-shopping-cart mr-1"></i>Añadir al Carrito
              </button>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="bg-light p-30">
            <div class="tab-content">
              <div class="tab-pane fade show active" id="tab-pane-1">
                <h4 class="mb-3">Descripción del Producto</h4>
                <p id="product-description"></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Shop Detail End -->

    <!-- Footer Start -->
<footer>
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

    <!-- JavaScript Libraries -->
    <script type="module" src="js/main.js"></script>
    <script type="module" src="js/detalleProducto.js"></script>
  </body>
</html>
