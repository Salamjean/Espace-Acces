<aside class="mdc-drawer mdc-drawer--dismissible mdc-drawer--open" style="background-color: red">
      <div class="mdc-drawer__header" >
        <a href="{{route('agent.dashboard')}}" class="brand-logo">
          <img src="{{asset('assets/assets/img/kks.jpg')}}" style="width: 50%; margin-left: 50px" alt="logo">
        </a>
      </div>
      <div class="mdc-drawer__content">
        <div class="user-info">
          <p class="name text-center"> {{Auth::guard('agent')->user()->name}} {{Auth::guard('agent')->user()->prenom}} </p>
          <p class="email text-center">{{Auth::guard('agent')->user()->email}}</p>
        </div>
        <div class="mdc-list-group">
          <nav class="mdc-list mdc-drawer-menu">
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('agent.dashboard')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">home</i>
                Tableau de bord
              </a>
            </div>
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('visite.access')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">save</i>
                Enregistrer un visiteur
              </a>
            </div>
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('visite.history')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">dashboard</i>
                Historques des visites
              </a>
            </div>
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('agent.scanner.view')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">dashboard</i>
               Scanner
              </a>
            </div>
            {{-- <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="#">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">dashboard</i>
                Les colis livrés 
              </a>
            </div> --}}
            <div class="mdc-list-item mdc-drawer-item">
              {{-- <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="ui-sub-agent">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">home</i>
                Agent
                <i class="mdc-drawer-arrow material-icons">chevron_right</i>
              </a> --}}
              <div class="mdc-expansion-panel" id="ui-sub-agent">
                <nav class="mdc-list mdc-drawer-submenu">
                  {{-- <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="#">
                      Ajout Agent
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="#">
                      Liste Agent
                    </a>
                  </div> --}}
                  {{-- <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="#">
                      Structure desactivées
                    </a>
                  </div> --}}
                </nav>
              </div>
            </div>
            <div class="mdc-list-item mdc-drawer-item">
              {{-- <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="ui-sub-menu">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">home</i>
                Structure
                <i class="mdc-drawer-arrow material-icons">chevron_right</i>
              </a> --}}
              <div class="mdc-expansion-panel" id="ui-sub-menu">
                <nav class="mdc-list mdc-drawer-submenu">
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="#">
                      Ajout Structure
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="#">
                      Liste Structure
                    </a>
                  </div>
                  {{-- <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="#">
                      Structure desactivées
                    </a>
                  </div> --}}
                </nav>
              </div>
            </div>
          </div>
    </aside>