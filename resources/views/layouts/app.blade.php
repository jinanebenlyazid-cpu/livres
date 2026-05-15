<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'BiblioTech')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/bibliotech.css') }}" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container py-2">
            <a class="navbar-brand bt-navbar-brand" href="{{ route('home') }}">

    <img
        src="{{ asset('images/logo.png') }}"
        alt="BiblioTech Logo"
        class="bt-logo"
    >

</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Accueil</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('livres.index') }}">Livres</a>
                    </li>

                    @auth
                        <li class="nav-item dropdown">
                            <a class="btn btn-primary rounded-pill dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">Profil</a>
                                </li>

                                @if(auth()->user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item" href="/admin">Admin</a>
                                    </li>
                                @endif

                                <li>
                                    <a class="dropdown-item" href="{{ route('emprunts.index') }}">Mes emprunts</a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('emprunts.create') }}">Ajouter un emprunt</a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>

                        <li class="nav-item">
                            <a class="btn btn-accent rounded-pill px-3" href="{{ route('register') }}">Inscription</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="bt-footer">

    <div class="bt-footer-overlay"></div>

    <div class="container position-relative">

        <div class="row g-5">

            <!-- LEFT -->
            <div class="col-lg-4">

                <div class="d-flex align-items-center mb-4">

                    <a class="navbar-brand bt-navbar-brand" href="{{ route('home') }}">

                            <img
                                src="{{ asset('images/logo.png') }}"
                                alt="BiblioTech Logo"
                                class="bt-logo" >

                        </a>
                </div>

                <p class="bt-footer-text">
                    Découvrez une plateforme moderne pour gérer
                    vos livres, emprunts et catégories avec une
                    expérience élégante et intuitive.
                </p>

                <div class="bt-footer-socials">

                    <a href="#">
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a href="#">
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a href="#">
                        <i class="bi bi-linkedin"></i>
                    </a>

                </div>

            </div>

            <!-- CENTER -->
            <div class="col-lg-3">

                <h5 class="bt-footer-title">
                    Découvrir
                </h5>

                <ul class="bt-footer-links">

                    <li>
                        <a href="{{ route('home') }}">
                            Accueil
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('livres.index') }}">
                            Livres
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Catégories
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Nouveautés
                        </a>
                    </li>

                </ul>

            </div>

            <!-- RIGHT -->
            <div class="col-lg-5">

                <h5 class="bt-footer-title">
                    Contact
                </h5>

                <div class="bt-contact-item">
                    📍 Tanger, Maroc
                </div>

                <div class="bt-contact-item">
                    ✉️ contact@bibliotech.com
                </div>

                <div class="bt-contact-item">
                    ☎️ +212 600000000
                </div>

                <a href="{{ route('register') }}" class="bt-footer-btn">
                    Commencer maintenant
                </a>

            </div>

        </div>

        <div class="bt-footer-bottom">

            <span>
                © {{ date('Y') }} BiblioTech. Tous droits réservés.
            </span>

            <div class="d-flex gap-4">
                <a href="#">Confidentialité</a>
                <a href="#">Conditions</a>
            </div>

        </div>

    </div>

</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/bibliotech.js') }}"></script>
</body>
</html>
