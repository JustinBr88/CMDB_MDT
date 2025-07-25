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
        <!-- Noticia 1 - Hardware -->
        <div class="col-lg-12 mb-4">
          <div class="d-flex bg-light p-3 align-items-center">
            <img
              src="img/noticia 1.jpg"
              alt="Hardware"
              class="img-fluid"
              style="width: 300px; height: 260px; margin-right: 20px"
            />
            <div>
              <h4 class="text-dark">
                Nuevos Procesadores Intel Core Ultra: Mayor Eficiencia para Empresas
              </h4>
              <p class="text-muted">
                Intel presenta su nueva línea de procesadores Core Ultra diseñados específicamente para entornos empresariales. 
                Estos chips ofrecen un 40% más de eficiencia energética y mejor rendimiento en aplicaciones de gestión de inventarios 
                y bases de datos. Ideal para actualizar los equipos de tu organización y mejorar la productividad del personal.
              </p>
              <a
                href="https://www.intel.com/content/www/us/en/newsroom/news/intel-core-ultra-processors.html"
                class="text-primary"
                target="_blank"
                >Leer más</a
              >
            </div>
          </div>
        </div>
        <!-- Noticia 2 - Software -->
        <div class="col-lg-12 mb-4">
          <div class="d-flex bg-light p-3 align-items-center">
            <img
              src="img/noticia 2.jpeg"
              alt="Software"
              class="img-fluid"
              style="width: 300px; height: 260px; margin-right: 20px"
            />
            <div>
              <h4 class="text-dark">
                Microsoft 365 Copilot: IA que Revoluciona la Gestión Empresarial
              </h4>
              <p class="text-muted">
                Microsoft lanza nuevas funcionalidades de inteligencia artificial en Office 365 que automatizan 
                tareas de inventario y reporting. Copilot puede generar reportes automáticos de activos, 
                predecir necesidades de mantenimiento y optimizar la asignación de equipos en tiempo real. 
                Una herramienta esencial para modernizar los sistemas CMDB.
              </p>
              <a
                href="https://news.microsoft.com/2024/03/21/microsoft-365-copilot-ai-powered-productivity/"
                class="text-primary"
                target="_blank"
                >Leer más</a
              >
            </div>
          </div>
        </div>
        <!-- Noticia 3 - Importancia de Sistemas de Inventario -->
        <div class="col-lg-12 mb-4">
          <div class="d-flex bg-light p-3 align-items-center">
            <!-- Contenedor del video, ajustado al mismo tamaño que las imágenes -->
            <div class="embed-responsive embed-responsive-16by9">
              <iframe
                title="importancia-cmdb"
                class="embed-responsive-item"
                src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ"
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
              <h4 class="text-dark">¿Por Qué Tu Empresa Necesita un Sistema CMDB?</h4>
              <p class="text-muted">
                Los sistemas de gestión de inventario y configuración (CMDB) son fundamentales para el éxito empresarial moderno. 
                Permiten reducir costos operativos hasta un 30%, mejorar la toma de decisiones con datos en tiempo real, 
                y garantizar el cumplimiento de auditorías. Descubre cómo MD Tecnología puede ayudar a tu organización 
                a implementar estas mejores prácticas.
              </p>
              <a
                href="https://www.servicenow.com/products/it-service-management/what-is-cmdb.html"
                class="text-primary"
                target="_blank"
                >Conoce más sobre CMDB</a
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Noticias End -->

<?php include('footer.php'); ?>

    <!-- JavaScript  -->
    <script src="../js/home.js"></script>
  </body>
</html>
