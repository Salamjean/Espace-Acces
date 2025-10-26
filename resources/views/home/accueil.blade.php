@extends('home.layouts.template')
@section('content')

<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

        <div class="carousel-item active">
          <img src="{{asset('assets/assets/img/imageData.jpg')}}" alt="">
          <div class="container">
           <h2>Bienvenue sur le portail du Datacenter</h2>
            <p>
            Notre datacenter offre une infrastructure moderne, sécurisée et performante pour l’hébergement de vos données et applications critiques. 
            Nous garantissons une haute disponibilité, une connectivité optimale et une surveillance 24h/24 pour assurer la continuité de vos services.
            </p>
            <a href="#about" class="btn-get-started">En savoir plus</a>
          </div>
        </div><!-- End Carousel Item -->

        <div class="carousel-item">
          <img src="{{asset('assets/assets/img/DATA-CENTER-1.jpg')}}" alt="">
          <div class="container">
            <h2>Une infrastructure fiable et performante</h2>
            <p>
            Grâce à nos installations de dernière génération, notre datacenter vous offre une connectivité continue, une redondance complète et une sécurité renforcée. 
            Nos équipes techniques veillent au bon fonctionnement de vos serveurs afin d’assurer la disponibilité et la protection de vos données en toute circonstance.
            </p>
            <a href="#about" class="btn-get-started">En savoir plus</a>
          </div>
        </div><!-- End Carousel Item -->

        <div class="carousel-item">
          <img src="{{asset('assets/assets/img/sante.jpg')}}" alt="Campagne de santé publique">
          <div class="container">
              <h2>Ministère de la Santé - Votre santé, notre priorité</h2>
              <p>
                  Le Ministère de la Santé œuvre quotidiennement pour protéger la santé de tous les citoyens. 
                  À travers des campagnes de prévention, un système de santé robuste et des actions ciblées, 
                  nous nous engageons pour votre bien-être et celui de vos proches.
              </p>
              <a href="#campagnes-sante" class="btn-get-started">Découvrir nos actions</a>
          </div>
      </div><!-- End Carousel Item -->

        <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>

        <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>

        <ol class="carousel-indicators"></ol>

      </div>

    </section>
    <!-- /Hero Section -->

    <!-- Featured Services Section -->
    <section id="featured-services" class="featured-services section">

    <div class="container">

        <div class="row gy-4">

        <!-- Étape 1 : Soumission de la demande -->
        <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item position-relative">
            <div class="icon"><i class="fas fa-file-signature icon"></i></div>
            <h4><a href="#" class="stretched-link">Soumettre une demande</a></h4>
            <p>Le demandeur remplit le formulaire en ligne pour accéder à la salle du datacenter, en précisant le motif, la durée et les équipements concernés.</p>
            </div>
        </div><!-- End Service Item -->

        <!-- Étape 2 : Validation par l’administration -->
        <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
            <div class="icon"><i class="fas fa-user-check icon"></i></div>
            <h4><a href="#" class="stretched-link">Validation de la DIEMP</a></h4>
            <p>La DIEMP examine la demande, vérifie les autorisations et approuve l’accès selon les règles de sécurité et de disponibilité des salles.</p>
            </div>
        </div><!-- End Service Item -->

        <!-- Étape 3 : Contrôle d’accès -->
        <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
            <div class="icon"><i class="fas fa-id-card icon"></i></div>
            <h4><a href="#" class="stretched-link">Contrôle à l’entrée</a></h4>
            <p>À son arrivée, l’agent ou le demandeur présente son badge d’accès ou son QR code pour vérification par le personnel de sécurité.</p>
            </div>
        </div><!-- End Service Item -->

        <!-- Étape 4 : Accès et traçabilité -->
        <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
            <div class="icon"><i class="fas fa-server icon"></i></div>
            <h4><a href="#" class="stretched-link">Accès et suivi</a></h4>
            <p>Une fois autorisé, l’accès est enregistré et surveillé en temps réel. Toutes les interventions sont tracées pour garantir la sécurité du datacenter.</p>
            </div>
        </div><!-- End Service Item -->

        </div>

    </div>
    </section>


    </section><!-- /Featured Services Section -->

    <!-- Call To Action Section -->
   <section id="call-to-action" class="call-to-action section accent-background">

    <div class="container">
        <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
        <div class="col-xl-10">
            <div class="text-center">
            <h3>Besoin d’un accès urgent au datacenter ?</h3>
            <p>
                En cas d’intervention critique ou de maintenance urgente, la DIEMP met en place une procédure accélérée 
                pour autoriser l’accès à la salle du datacenter. 
                Nos équipes sont disponibles pour garantir la continuité des services et la sécurité des infrastructures.
            </p>
            <a class="cta-btn" href="{{route('pages.access')}}">Faire une demande d’accès</a>
            </div>
        </div>
        </div>
    </div>

    </section><!-- /Call To Action Section -->


   <!-- Stats Section -->
<section id="stats" class="stats section">

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row gy-4">

      <!-- Nombre de serveurs -->
      <div class="col-lg-3 col-md-6">
        <div class="stats-item d-flex align-items-center w-100 h-100">
          <i class="fas fa-server flex-shrink-0"></i>
          <div>
            <span data-purecounter-start="0" data-purecounter-end="120" data-purecounter-duration="1" class="purecounter"></span>
            <p>Serveurs</p>
          </div>
        </div>
      </div><!-- End Stats Item -->

      <!-- Nombre de racks -->
      <div class="col-lg-3 col-md-6">
        <div class="stats-item d-flex align-items-center w-100 h-100">
          <i class="fas fa-network-wired flex-shrink-0"></i>
          <div>
            <span data-purecounter-start="0" data-purecounter-end="30" data-purecounter-duration="1" class="purecounter"></span>
            <p>Racks</p>
          </div>
        </div>
      </div><!-- End Stats Item -->

      <!-- Nombre d’interventions / maintenance -->
      <div class="col-lg-3 col-md-6">
        <div class="stats-item d-flex align-items-center w-100 h-100">
          <i class="fas fa-tools flex-shrink-0"></i>
          <div>
            <span data-purecounter-start="0" data-purecounter-end="200" data-purecounter-duration="1" class="purecounter"></span>
            <p>Interventions techniques</p>
          </div>
        </div>
      </div><!-- End Stats Item -->

      <!-- Nombre d’utilisateurs autorisés -->
      <div class="col-lg-3 col-md-6">
        <div class="stats-item d-flex align-items-center w-100 h-100">
          <i class="fas fa-user-shield flex-shrink-0"></i>
          <div>
            <span data-purecounter-start="0" data-purecounter-end="150" data-purecounter-duration="1" class="purecounter"></span>
            <p>Accès autorisés</p>
          </div>
        </div>
      </div><!-- End Stats Item -->

    </div>

  </div>

</section><!-- /Stats Section -->


    

    <!-- Section Témoignages -->
<section id="testimonials" class="testimonials section">

  <!-- Titre de la section -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Témoignages</h2>
    <p>Découvrez ce que disent nos partenaires et utilisateurs autorisés sur la sécurité et l’efficacité du datacenter de la DIEMP.</p>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="swiper init-swiper" data-speed="600" data-delay="5000" data-breakpoints="{ &quot;320&quot;: { &quot;slidesPerView&quot;: 1, &quot;spaceBetween&quot;: 40 }, &quot;1200&quot;: { &quot;slidesPerView&quot;: 3, &quot;spaceBetween&quot;: 40 } }">
      <script type="application/json" class="swiper-config">
        {
          "loop": true,
          "speed": 600,
          "autoplay": { "delay": 5000 },
          "slidesPerView": "auto",
          "pagination": { "el": ".swiper-pagination", "type": "bullets", "clickable": true },
          "breakpoints": { "320": { "slidesPerView": 1, "spaceBetween": 40 }, "1200": { "slidesPerView": 3, "spaceBetween": 20 } }
        }
      </script>

      <div class="swiper-wrapper">

        <div class="swiper-slide">
          <div class="testimonial-item">
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Le datacenter de la DIEMP offre une sécurité maximale et une disponibilité continue. Chaque intervention est rigoureusement tracée et contrôlée.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
            <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
            <h3>Marie Dupont</h3>
            <h4>Administratrice Systèmes</h4>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Grâce au processus d’accès sécurisé de la DIEMP, nous pouvons intervenir rapidement dans le datacenter tout en respectant les normes strictes de sécurité.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
            <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img" alt="">
            <h3>Jean-Louis Koffi</h3>
            <h4>Ingénieur Réseau</h4>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Le suivi en temps réel des interventions et des accès garantit la fiabilité et la continuité des services hébergés dans le datacenter.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
            <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img" alt="">
            <h3>Ali Traoré</h3>
            <h4>Technicien Datacenter</h4>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Nous apprécions la rigueur et la transparence des procédures d’accès, ce qui rend nos interventions plus sûres et plus efficaces.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
            <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img" alt="">
            <h3>Sophie Martin</h3>
            <h4>Consultante IT</h4>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Le datacenter DIEMP combine sécurité, performance et traçabilité, ce qui en fait un environnement fiable pour nos projets critiques.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
            <img src="assets/img/testimonials/testimonials-5.jpg" class="testimonial-img" alt="">
            <h3>Marc Lemoine</h3>
            <h4>Chef de Projet IT</h4>
          </div>
        </div><!-- End testimonial item -->

      </div>
      <div class="swiper-pagination"></div>
    </div>

  </div>

</section><!-- /Testimonials Section -->


  </main>

@endsection