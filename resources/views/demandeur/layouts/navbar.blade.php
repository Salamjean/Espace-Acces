<header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center" style="background-color: #193561">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="d-none d-md-flex align-items-center">
          <i class="bi bi-clock me-1"></i> Lundi - Vendredi, 8H00 à 17H30
        </div>
        <div class="d-flex align-items-center">
          <i class="bi bi-phone me-1"></i> Appelez-nous +225 07 11 11 79 79
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-end">
        <a href="{{route('demandeur.dashboard')}}" class="logo d-flex align-items-center me-auto">
          <img src="{{asset('assets/assets/img/kks.jpg')}}" alt="">
          <!-- Uncomment the line below if you also wish to use a text logo -->
          <!-- <h1 class="sitename">Medicio</h1>  -->
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="{{route('demandeur.dashboard')}}" class="active">Accueil</a></li>
            <li><a href="{{route('demandes.list')}}">Mes demandes</a></li>
            <li><a href="{{route('demandeur.pages.about')}}">A propos</a></li>
            <li><a href="{{route('demandeur.pages.service')}}">Services</a></li>
            <li><a href="{{route('demandeur.pages.contact')}}">Contact</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <div class="header-buttons d-flex align-items-center gap-2">
          <a class="cta-btn d-none d-md-inline-flex" href="{{route('demandes.create')}}">Faire une demande d'accès</a>
          <a class="logout-btn d-none d-md-inline-flex" href="{{ route('demandeur.logout') }}" 
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
          </a>
          
          <!-- Version mobile avec icônes seulement -->
          <div class="d-md-none mobile-buttons d-flex gap-2">
            <a class="cta-btn-mobile" href="{{route('demandes.create')}}" title="Faire une demande d'accès">
              <i class="bi bi-plus-circle"></i>
            </a>
            <a class="logout-btn-mobile" href="{{ route('demandeur.logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               title="Déconnexion">
              <i class="bi bi-box-arrow-right"></i>
            </a>
          </div>
        </div>

        <!-- Formulaire de déconnexion caché -->
        <form id="logout-form" action="{{ route('demandeur.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

      </div>

    </div>

</header>

<style>
/* Styles pour les boutons desktop */
.cta-btn {
    background: #1977cc;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.cta-btn:hover {
    background: #166ab5;
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.logout-btn {
    background: #dc3545;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.logout-btn:hover {
    background: #c82333;
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

/* Styles pour les boutons mobile */
.mobile-buttons {
    margin-left: 10px;
}

.cta-btn-mobile, .logout-btn-mobile {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
}

.cta-btn-mobile {
    background: #1977cc;
    color: white;
}

.cta-btn-mobile:hover {
    background: #166ab5;
    color: white;
    transform: scale(1.1);
}

.logout-btn-mobile {
    background: #dc3545;
    color: white;
}

.logout-btn-mobile:hover {
    background: #c82333;
    color: white;
    transform: scale(1.1);
}

/* Responsive pour les boutons */
.header-buttons {
    margin-left: 15px;
}

@media (max-width: 768px) {
    .header-buttons {
        margin-left: 10px;
    }
    
    .mobile-buttons {
        gap: 8px;
    }
}
</style>