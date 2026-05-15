@extends('layouts.app')

@section('title', 'Accueil - BiblioTech')

@section('content')

<section class="bt-hero-section">
    <div class="container">

        <div class="bt-hero-box">

            <div class="row align-items-center g-5">

                <div class="col-lg-6">

                    <span class="bt-hero-badge">
                        Bibliothèque intelligente
                    </span>

                    <h1 class="bt-hero-title">
                        Explorez, empruntez
                        et suivez vos livres.
                    </h1>

                    <p class="bt-hero-text">
                        Une expérience moderne pour gérer les livres,
                        catégories, membres et emprunts avec une interface
                        claire et rapide.
                    </p>

                    <div class="bt-hero-buttons">

                        <a href="{{ route('livres.index') }}" class="bt-btn-orange">
                            Voir les livres
                        </a>

                        @guest
                        <a href="{{ route('register') }}" class="bt-btn-white">
                            Créer un compte
                        </a>
                        @endguest

                    </div>

                </div>

                <div class="col-lg-6">

                    <div id="homeSlider" class="carousel slide" data-bs-ride="carousel">

                        <div class="carousel-inner">

                            @foreach(['book1.jpg', 'book2.jpg', 'book3.jpg'] as $index => $image)

                            <div class="carousel-item @if($index === 0) active @endif">

                                <div class="bt-hero-image">

                                    <img
                                        src="{{ asset('images/slider/'.$image) }}"
                                        alt="Livre"
                                    >

                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

<section class="bt-section bt-section-purple">
    <div class="container">
        <div class="bt-section-head">
            <div>
                <span class="bt-label">Collection</span>
                <h2 class="bt-section-title">
                    Livres <span>populaires</span>
                </h2>
            </div>

            <a href="{{ route('livres.index') }}" class="bt-link-btn">Voir tout</a>
        </div>

        <div class="row g-4">
            @forelse($popularLivres as $livre)
                <div class="col-md-6 col-xl-3">
                    <div class="bt-book-card">
                        <div class="bt-book-image">
                            <img src="{{ $livre->image ? asset('images/livres/'.$livre->image) : asset('images/slider/book1.jpg') }}" alt="{{ $livre->titre }}">
                        </div>

                        <div class="bt-book-content">
                            <span class="bt-book-author">{{ $livre->auteur }}</span>
                            <h4>{{ $livre->titre }}</h4>
                            <a href="{{ route('livres.show', $livre) }}">Voir détails →</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info rounded-4">Aucun livre disponible.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="bt-section bt-section-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="bt-label">Explorer</span>
            <h2 class="bt-section-title">
                Nos <span>catégories</span>
            </h2>
        </div>

        <div class="row g-4">
            @forelse($categories as $category)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('livres.index', ['category_id' => $category->id]) }}" class="text-decoration-none">
                        <div class="bt-category-card">
                            <div>
                                <h4>{{ $category->nom }}</h4>
                                <p>{{ $category->livres_count }} livres disponibles</p>
                            </div>

                            <div class="bt-arrow">→</div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info rounded-4">Catégories bientôt disponibles.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="bt-section bt-section-cream">
    <div class="container">
        <div class="bt-section-head">
            <div>
                <span class="bt-label">Nouveautés</span>
                <h2 class="bt-section-title">
                    Derniers <span>livres</span>
                </h2>
            </div>
        </div>

        <div class="row g-4">
            @foreach($latestLivres as $livre)
                <div class="col-md-6 col-xl-3">
                    <div class="bt-book-card">
                        <div class="bt-book-image">
                            <img src="{{ $livre->image ? asset('images/livres/'.$livre->image) : asset('images/slider/book2.jpg') }}" alt="{{ $livre->titre }}">
                        </div>

                        <div class="bt-book-content">
                            <div class="mb-2">
                                <span class="bt-status {{ $livre->statut === 'Disponible' ? 'available' : 'unavailable' }}">
                                    {{ $livre->statut }}
                                </span>
                            </div>

                            <h4>{{ $livre->titre }}</h4>
                            <p class="bt-book-author">{{ $livre->auteur }}</p>
                            <a href="{{ route('livres.show', $livre) }}">Voir détails →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bt-section bt-section-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="bt-label">Avantages</span>
            <h2 class="bt-section-title">
                Pourquoi choisir <span>BiblioTech ?</span>
            </h2>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="bt-category-card">
                    <div>
                        <h4>Recherche rapide</h4>
                        <p>Trouvez facilement vos livres par titre, auteur ou catégorie.</p>
                    </div>
                    <div class="bt-arrow">⌕</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bt-category-card">
                    <div>
                        <h4>Suivi des emprunts</h4>
                        <p>Consultez vos emprunts en cours et vos retours à tout moment.</p>
                    </div>
                    <div class="bt-arrow">✓</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bt-category-card">
                    <div>
                        <h4>Interface moderne</h4>
                        <p>Profitez d’une expérience claire, fluide et simple à utiliser.</p>
                    </div>
                    <div class="bt-arrow">✦</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bt-section bt-section-dark bt-last-section">
    <div class="container text-center">
        <span class="bt-label">Commencer</span>

        <h2 class="bt-section-title mb-4">
            Prêt à explorer votre <span class="text-white">bibliothèque digitale ?</span>
        </h2>

        <p class="bt-description mx-auto">
            Rejoignez BiblioTech et découvrez une façon simple, moderne et intelligente de gérer vos lectures.
        </p>

        <div class="bt-actions justify-content-center mt-4">
            <a href="{{ route('livres.index') }}" class="bt-btn-primary m-3 bt-btn-orange">
                Voir les livres
            </a>

            @guest
                <a href="{{ route('register') }}" class="bt-btn-outline bt-btn-white">
                    Créer un compte
                </a>
            @endguest
        </div>
    </div>
</section>

@endsection