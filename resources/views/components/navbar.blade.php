<!--NAVBAR-->
<nav class="fixed-top navbar navbar-expand-xxl bg-light main-nav pe-md-2 pe-lg-5" style="z-index: 999;">
    <div class="container-fluid">
        <a class="d-flex align-items-center" href="{{ route('dashboard') }}" style="text-decoration: none;">
            <span class="d-inline-block ms-md-3 ms-lg-5">
                <img src="{{ asset('immagini/logo_angeltracking.png') }}" style="height: 40px;" alt="Logo Angel Air">
            </span>
            <span class="navbar-brand text-uppercase fw-5 fw-normal ms-1 text-primary">Angel Air</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-0">
                <li class="nav-item position-relative">
                    <a class="nav-link text-center {{ request()->is('dashboard*') ? 'with-bar navbar-selection' : '' }}" aria-current="page" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link text-center d-flex justify-content-center align-items-center {{ request()->is('monitoraggio*') ? 'with-bar navbar-selection' : '' }}" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Monitoraggio
                        <span class="material-icons ms-1" style="font-size: 18px; width: 18px; height: 18px;">
                            expand_more
                        </span>

                    </a>
                    <ul class="dropdown-menu m-0 p-0">
                        <li><a class="dropdown-item text-center text-xxl-start" href="{{ route('monitoraggio') }}">Monitoraggio</a></li>
                        <li><a class="dropdown-item text-center text-xxl-start" href="{{ route('grafici') }}">Grafici</a></li>
                        <li><a class="dropdown-item text-center text-xxl-start" href="{{ route('statistiche') }}">Statistiche</a></li>
                        <li><a class="dropdown-item text-center text-xxl-start" href="{{ route('real_time') }}">Real time</a></li>
                        <li><a class="dropdown-item text-center text-xxl-start" href="{{ route('logs') }}">Log di sistema</a></li>
                    </ul>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link text-center {{ request()->is('strutture*') ? 'with-bar navbar-selection' : '' }}" aria-current="page" href="{{ route('strutture') }}">Strutture</a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link text-center {{ request()->is('dispositivi*') ? 'with-bar navbar-selection' : '' }}" aria-current="page" href="{{ route('dispositivi') }}">Dispositivi</a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link text-center {{ request()->is('comandi*') ? 'with-bar navbar-selection' : '' }}" aria-current="page" href="{{ route('comandi') }}">Comandi</a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link text-center {{ request()->is('soglie*') ? 'with-bar navbar-selection' : '' }}" aria-current="page" href="{{ route('soglie') }}">Soglie</a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link text-center {{ request()->is('reports*') ? 'with-bar navbar-selection' : '' }}" aria-current="page" href="{{ route('reports') }}">Reports</a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link text-center {{ request()->is('utenti*') ? 'with-bar navbar-selection' : '' }}" aria-current="page" href="{{ route('utenti') }}">Utenti</a>
                </li>

            </ul>
            <div class="d-flex flex-column-reverse flex-xxl-row align-items-center" role="search">
                <a href="http://www.axatel.it" class="text-center  mb-4 mb-xxl-0" target="_blank">
                    <img class="img-fluid ms-3 me-4" src="{{ asset('immagini/axatel_logo.png') }}" />
                </a>
                <div class="d-flex align-items-center position-relative">
                    <a type="button" class="btn btn-light nav-link rounded-start-5 rounded-end-0 ps-0 {{ request()->is('profilo*') ? 'with-bar navbar-selection' : '' }} p-2" href="{{ route('profilo') }}">
                        <span class="axatel-link ps-2 d-inline-block py-0">Karolina</span>
                    </a>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <div class="btn-group">
                            <button type="button" class="btn btn-light dropdown-toggle-split rounded-end-5 rounded-start-0 d-flex justify-content-center align-items-center ps-1 mt-2 mt-lg-0 dropdown-logout" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-icons text-navbar d-inline-block" style="font-size: 18px; width: 18px; height: 18px;">
                                    expand_more
                                </span>
                            </button>
                            <ul class="dropdown dropdown-center dropdown-menu position-absolute py-0 dropdown-menu-logout">
                                <li><a class="dropdown-item cursor-pointer text-center" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
