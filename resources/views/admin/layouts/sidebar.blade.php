<aside class="mdc-drawer mdc-drawer--dismissible mdc-drawer--open" style="background-color: red">
      <div class="mdc-drawer__header" >
        <a href="{{route('admin.dashboard')}}" class="brand-logo">
          <img src="{{asset('assets/assets/img/kks.jpg')}}" style="width: 50%; margin-left: 50px" alt="logo">
        </a>
      </div>
      <div class="mdc-drawer__content">
        <div class="user-info">
          <p class="name text-center"> {{Auth::guard('admin')->user()->name}} </p>
          <p class="email text-center">{{Auth::guard('admin')->user()->email}}</p>
        </div>
        <div class="mdc-list-group">
          <nav class="mdc-list mdc-drawer-menu">
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('admin.dashboard')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">home</i>
                Tableau de bord
              </a>
            </div>
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('admin.demandes.index')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">save</i>
                Demandes 
              </a>
            </div>
            {{-- <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('admin.permanent-personnel.create')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">dashboard</i>
                Personnel permanent
              </a>
            </div> --}}
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-drawer-link" href="{{route('admin.visite.history')}}">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">dashboard</i>
                Historiques des visites
              </a>
            </div>
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="ui-sub-perso">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">home</i>
                 Personnel 
                <i class="mdc-drawer-arrow material-icons">chevron_right</i>
              </a>
              <div class="mdc-expansion-panel" id="ui-sub-perso">
                <nav class="mdc-list mdc-drawer-submenu">
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('admin.permanent-personnel.create')}}">
                      Ajout Personnel
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('admin.permanent-personnel.index')}}">
                      Liste Personnel
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
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="ui-sub-agent">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">home</i>
                Agent
                <i class="mdc-drawer-arrow material-icons">chevron_right</i>
              </a>
              <div class="mdc-expansion-panel" id="ui-sub-agent">
                <nav class="mdc-list mdc-drawer-submenu">
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('agent.create')}}">
                      Ajout Agent
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('agent.index')}}">
                      Liste Agent
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('agent.info')}}">
                      Présences
                    </a>
                  </div>
                </nav>
              </div>
            </div>
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="ui-sub-menu">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">home</i>
                Structure
                <i class="mdc-drawer-arrow material-icons">chevron_right</i>
              </a>
              <div class="mdc-expansion-panel" id="ui-sub-menu">
                <nav class="mdc-list mdc-drawer-submenu">
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('demandeur.create')}}">
                      Ajout Structure
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('demandeur.index')}}">
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
            <div class="mdc-list-item mdc-drawer-item">
              <a class="mdc-expansion-panel-link" href="#" data-toggle="expansionPanel" data-target="ui-sub-code">
                <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">home</i>
                Code QR Accès
                <i class="mdc-drawer-arrow material-icons">chevron_right</i>
              </a>
              <div class="mdc-expansion-panel" id="ui-sub-code">
                <nav class="mdc-list mdc-drawer-submenu">
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('admin.code.create')}}">
                      Ajouter
                    </a>
                  </div>
                  <div class="mdc-list-item mdc-drawer-item">
                    <a class="mdc-drawer-link" href="{{route('admin.code.index')}}">
                      Code QR accès
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