<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <title>MD Tecnología - Home</title>
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

    <!-- Carousel Start -->
    <div class="container-fluid mb-3">
      <div class="px-xl-5">
        <div class="carrusel">
          <div id="header-carousel" class="carousel-container">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="img/baner-slide1.png"" alt="Slide 1" />
              </div>
              <div class="carousel-item">
                <img src="img/baner-slide2.png" alt="Slide 2" />
              </div>
              <div class="carousel-item">
                <img src="img/baner-slide3.png" alt="Slide 3" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Carousel End -->

    <!-- Categorías Start -->
    <div class="container-fluid pt-5">
      <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Categorías</span>
      </h2>
      <div class="row px-xl-5 pb-3" id="categorias-bonitas-container">
        <!-- Categorías creadas de forma dinámica -->
      </div>
    </div>
    <!-- Categorías End -->

    <!-- Productos Start -->
    <div class="container-fluid pt-5 pb-3">
      <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Productos recomendados</span>
      </h2>
      <div class="row px-xl-5" id="equipos-container">
        <!-- Productos generados de forma dinámica se insertarán aquí -->
      </div>
    </div>
    <!-- Productos End -->

    <!-- Noticias Start -->
    <div class="container-fluid pt-5">
      <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Noticias Relevantes</span>
      </h2>
      <div class="row px-xl-5">
        <!-- Noticia 1 -->
        <div class="col-lg-12 mb-4">
          <div class="d-flex bg-light p-3 align-items-center">
            <img
              src="img/noticia 1.jpg"
              alt="Noticia 1"
              class="img-fluid"
              style="width: 300px; height: 260px; margin-right: 20px"
            />
            <div>
              <h4 class="text-dark">
                Ventas ‘online’ podrían alcanzar los $1,173.0 millones en Panamá
              </h4>
              <p class="text-muted">
                Se espera que el valor de las ventas online en Panamá alcancen
                los $1,173.0 millones para 2024, lo que posicionaría al país
                como el 65º mercado más grande para el comercio electrónico, así
                lo prevé las últimas estimaciones de Statista Digital Market
                Insights.
              </p>
              <a
                href="https://www.laestrella.com.pa/economia/ventas-online-podrian-alcanzar-los-11730-millones-en-panama-HF7979283"
                class="text-primary"
                >Leer más</a
              >
            </div>
          </div>
        </div>
        <!-- Noticia 2 -->
        <div class="col-lg-12 mb-4">
          <div class="d-flex bg-light p-3 align-items-center">
            <img
              src="img/noticia 2.jpeg"
              alt="Noticia 2"
              class="img-fluid"
              style="width: 300px; height: 260px; margin-right: 20px"
            />
            <div>
              <h4 class="text-dark">
                CES Las Vegas 2024: La IA protagoniza la feria tecnológica más
                grande del mundo
              </h4>
              <p class="text-muted">
                El CES, que comienza el 9 de enero en Las Vegas, Nevada, en
                Estados Unidos, presentará la habitual variedad de nuevos
                productos tecnológicos de consumo. Este año, incluso todavía más
                de esas novedades estarán potenciadas por la inteligencia
                artificial.
              </p>
              <a
                href="https://es.wired.com/articulos/ces-2024-ia-protagoniza-feria-tecnologica-mas-grande-del-mundo"
                class="text-primary"
                >Leer más</a
              >
            </div>
          </div>
        </div>
        <!-- Video -->
        <div class="col-lg-12 mb-4">
          <div class="d-flex bg-light p-3 align-items-center">
            <!-- Contenedor del video, ajustado al mismo tamaño que las imágenes -->
            <div class="embed-responsive embed-responsive-16by9">
              <iframe
                title="noticia3-youtube"
                class="embed-responsive-item"
                src="https://www.youtube-nocookie.com/embed/ACECFE1kSis"
                allow="fullscreen"
                style="
                  width: 300px;
                  height: 260px;
                  margin-right: 20px;
                  border-radius: 5px;
                  object-fit: cover;
                "
              >
              </iframe>
            </div>
            <!-- Contenido del video -->
            <div>
              <h4 class="text-dark">ROG Swift OLED PG27AQDM Disponible</h4>
              <p class="text-muted">
                El nuevo Monitor gaming ROG Swift OLED PG27AQDM ya esta
                disponible a la venta aqui en MD Tecnologia a descuento,
                consigue el tuyo hoy a $724.99 antes de que se agoten! (Precio
                Original: $879.99, no incluye impuesto)
              </p>
              <a
                href="https://www.youtube.com/shorts/ACECFE1kSis"
                class="text-primary"
                >Ver en YouTube</a
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Noticias End -->

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

    <!-- JavaScript  -->
    <script type="module" src="js/main.js"></script>
    <script type="module" src="js/home.js"></script>
  </body>
</html>
