@extends('societe.layouts.template')
@section('content')
<!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contactez-nous</h2>
        <p>Si vous avez des préoccupations et que vous souhaitez nous notifier faites le ici.</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-6 ">
            <div class="row gy-4">

              <div class="col-lg-12">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                  <i class="bi bi-geo-alt"></i>
                  <h3>Adresse</h3>
                  <p>Abidjan, Côte d'ivoire, Plateau</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
                  <i class="bi bi-telephone"></i>
                  <h3>Appelez - nous</h3>
                  <p>+225 07 11 11 79 79</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
                  <i class="bi bi-envelope"></i>
                  <h3>Email</h3>
                  <p>info@example.com</p>
                </div>
              </div><!-- End Info Item -->

            </div>
          </div>

          <div class="col-lg-6">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="500">
              <div class="row gy-4">

                <div class="col-md-6">
                  <input type="text" name="name" class="form-control" placeholder="Ton Nom" required="">
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="Ton Email" required="">
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="subject" placeholder="Sujet" required="">
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="4" placeholder="Message" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>

                  <button type="submit">Envoyez votre message</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->
           <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
                <iframe style="border:0; width: 100%; height: 370px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.625731689903!2d-4.015859325256744!3d5.320932894657536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc1ebbed7897411%3A0x2eb96cf2c149f12f!2sDIEMP%20%26%20Direction%20Regionale%20Sante%20Lagune%201!5e0!3m2!1sfr!2sci!4v1759786822643!5m2!1sfr!2sci" frameborder="0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div><!-- End Google Maps -->
        </div>

      </div>

    </section><!-- /Contact Section -->

@endsection