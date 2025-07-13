<?php include 'loginSesion.php';
      include('navbar.php'); ?>

    <!-- Carousel Start -->
    <div class="container-fluid mb-3">
      <div class="px-xl-5">
        <div class="carrusel">
          <div id="header-carousel" class="carousel-container">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="img/baner-slide1.png" alt="Slide 1" />
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

    <h1>Bienvenido Usuario al sistema CMDB de MDT</h1>

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

<?php include('footer.php'); ?>

    <!-- JavaScript  -->
    <script type="module" src="js/main.js"></script>
    <script type="module" src="js/home.js"></script>
  </body>
</html>
