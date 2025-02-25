<header>
    <!-- DESKTOP -->
    <div class="desktop">
        <div class="titulo">
            <h1>Gestor academia</h1>
        </div>

        <nav class="menu">
            <ul class="menu-list">
                <li><a class="menu-item" href="{{ route('welcome') }}">Inicio</a></li>
                <li><a class="menu-item" href="">Cursos</a></li>
                <li><a class="menu-item" href="">Incidencias</a></li>
                <li><a class="menu-item" href="{{ route('alumnos.index') }}">Alumnos</a></li>
                <li><a class="menu-item" href="{{ route('profesores.index') }}">Profesores</a></li>
                <li><a class="menu-item" href="{{ route('perfil') }}">Perfil</a></li>
            </ul>
        </nav>
    </div>
    

    <!-- MOBILE -->
    <nav class="mobile navbar navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <h1 class="navbar-brand">Gestor academia</h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menú</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('welcome') }}">Inicio</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">Cursos</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">Incidencias</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('alumnos.index') }}">Alumnos</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">Profesores</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('perfil') }}">Perfil</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>