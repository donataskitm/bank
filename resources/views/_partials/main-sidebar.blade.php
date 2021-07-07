<aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
    <div class="main-navbar">
        <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0">
            <a class="navbar-brand w-100 mr-0" href="/" style="line-height: 25px;">
                <div class="d-table m-auto">
                    <img id="main-logo" class="d-inline-block align-top mr-1" style="max-width: 25px;" src="img/shards-dashboards-logo.svg" alt="Shards Dashboard">
                    <span class="d-none d-md-inline ml-1">Bankas</span>
                </div>
            </a>
            <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                <i class="material-icons">&#xE5C4;</i>
            </a>
        </nav>
    </div>
    <form action="#" class="main-sidebar__search w-100 border-right d-sm-flex d-md-none d-lg-none">
        <div class="input-group input-group-seamless ml-3">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <input class="navbar-search form-control" type="text" placeholder="Search for something..." aria-label="Search"> </div>
    </form>
    <div class="nav-wrapper">
        <ul class="nav flex-column">
            @if(Auth::check())
            <li class="nav-item">
                <a class="nav-link active" href="/">
                    <i class="material-icons">person</i>
                    <span>Profilis</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="/search">
                    <i class="material-icons">vertical_split</i>
                    <span>Atlikti paiešką</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="/transfer">
                    <i class="material-icons">note_add</i>
                    <span>Atlikti pavedimą</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="/logout">
                    <i class="material-icons">view_module</i>
                    <span>Atsijungti</span>
                </a>
            </li>
            @else
                <li class="nav-item">
                    <a class="nav-link " href="/login">
                        <i class="material-icons">view_module</i>
                        <span>Prisijungti</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/register">
                        <i class="material-icons">view_module</i>
                        <span>Registruotis</span>
                    </a>
                </li>

            @endif
        </ul>
    </div>
</aside>
