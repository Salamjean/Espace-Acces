 <header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center" style="background-color: #193561">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="d-none d-md-flex align-items-center">
          <i class="bi bi-clock me-1"></i> Lundi - Vendredi, 8H30 à 16H
        </div>
        <div class="d-flex align-items-center">
          <i class="bi bi-phone me-1"></i> Appelez-nous +225 07 11 11 79 79
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-end">
        <a href="index.html" class="logo d-flex align-items-center me-auto">
          <img src="{{asset('assets/assets/img/kks.jpg')}}" alt="">
          <!-- Uncomment the line below if you also wish to use a text logo -->
          <!-- <h1 class="sitename">Medicio</h1>  -->
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="{{route('pages.home')}}" class="active">Accueil</a></li>
            <li><a href="{{route('pages.about')}}">A propos</a></li>
            <li><a href="{{route('pages.service')}}">Services</a></li>
            {{-- <li><a href="#departments">Departments</a></li>
            <li><a href="#doctors">Doctors</a></li> --}}
            {{-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                <li><a href="#">Dropdown 1</a></li>
                <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                  <ul>
                    <li><a href="#">Deep Dropdown 1</a></li>
                    <li><a href="#">Deep Dropdown 2</a></li>
                    <li><a href="#">Deep Dropdown 3</a></li>
                    <li><a href="#">Deep Dropdown 4</a></li>
                    <li><a href="#">Deep Dropdown 5</a></li>
                  </ul>
                </li>
                <li><a href="#">Dropdown 2</a></li>
                <li><a href="#">Dropdown 3</a></li>
                <li><a href="#">Dropdown 4</a></li>
              </ul>
            </li> --}}
            <li><a href="{{route('pages.contact')}}">Contact</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="cta-btn" href="{{route('demandes.create')}}">Faire une demande d’accès</a>

      </div>

    </div>

  </header>