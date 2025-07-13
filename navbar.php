<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>MD Tecnología</title>
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
    <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex" style="margin-right: 0px">
      <div class="col-lg-4">
        <a href="Home.php" class="text-decoration-none">
          <img src="img/logo.png" alt="logo" />
        </a>
      </div>
      <div class="col-lg-4 col-6 text-left">
        <form id="searchForm" action="https://www.google.com/search" method="get" target="_blank">
          <div class="input-group">
            <input type="text" class="form-control" name="q" placeholder="Buscar en Google..." required />
            <div class="input-group-append">
              <button id="clearButton" type="button" class="input-group-text bg-transparent text-primary" style="border: none; cursor: pointer">
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
    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
      <div class="row px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
          <a class="btn d-flex align-items-center justify-content-between bg-primary w-100"
             data-toggle="collapse"
             href="#navbar-vertical"
             style="height: 65px; padding: 0 30px">
            <h6 class="text-dark m-0">
              <i class="fa fa-bars mr-2"></i>Categorías
            </h6>
            <i class="fa fa-angle-down text-dark"></i>
          </a>
          <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light"
               id="navbar-vertical"
               style="width: calc(100% - 30px); z-index: 999"
               aria-label="Navegación de categorías">
            <div class="navbar-nav w-100" id="navbar-categorias">
              <!-- Aquí puedes cargar dinámicamente las categorías -->
            </div>
          </nav>
        </div>
        <!-- Puedes agregar aquí más ítems del menú horizontal -->
      </div>
    </div>
    <!-- Navbar End -->
  </header>
  <!-- Header End -->