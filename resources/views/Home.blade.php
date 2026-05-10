@extends('layouts.app')

@section('title', 'Accueil - BiblioTech Cloud')

@section('content')
<section class="container">
    <div class="hero p-4 p-lg-5 fade-up">
        <div class="row align-items-center g-5 position-relative">
            <div class="col-lg-6">
                <span class="badge text-bg-light text-primary rounded-pill mb-3">Bibliotheque intelligente</span>
                <h1 class="display-4 fw-bold mb-3">Explorez, empruntez et suivez vos livres en cloud.</h1>
                <p class="lead opacity-75 mb-4">Une experience moderne pour gerer les livres, categories, membres et emprunts avec une interface claire et rapide.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('livres.index') }}" class="btn btn-accent btn-lg rounded-pill px-4">Voir les livres</a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">Creer un compte</a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6">
                <div id="homeSlider" class="carousel slide glass-card overflow-hidden" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach(['book1.jpg', 'book2.jpg', 'book3.jpg'] as $index => $image)
                            <div class="carousel-item @if($index === 0) active @endif">
                                <img src="{{ asset('images/slider/'.$image) }}" class="d-block w-100 slider-img" alt="BiblioTech slider {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#homeSlider" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#homeSlider" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container mt-5">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <span class="text-primary fw-bold">Selection</span>
            <h2 class="section-title mb-0">Livres populaires</h2>
        </div>
        <a href="{{ route('livres.index') }}" class="btn btn-outline-primary rounded-pill">Tout voir</a>
    </div>
    <div id="popularBooks" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @forelse($popularLivres->chunk(4) as $chunkIndex => $chunk)
                <div class="carousel-item @if($chunkIndex === 0) active @endif">
                    <div class="row g-4">
                        @foreach($chunk as $livre)
                            <div class="col-md-6 col-xl-3">
                                <div class="book-card h-100 p-3">
                                    <img class="book-cover mb-3" src="{{ $livre->image ? asset('images/livres/'.$livre->image) : asset('images/slider/book2.jpg') }}" alt="{{ $livre->titre }}">
                                    <h5 class="fw-bold">{{ $livre->titre }}</h5>
                                    <p class="text-muted mb-2">{{ $livre->auteur }}</p>
                                    <a href="{{ route('livres.show', $livre) }}" class="stretched-link text-decoration-none">Details</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="alert alert-info rounded-4">Aucun livre disponible pour le moment.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="container mt-5">
    <div class="text-center mb-4">
        <span class="text-primary fw-bold">Explorer</span>
        <h2 class="section-title">Categories</h2>
    </div>
    <div class="row g-4">
        @forelse($categories as $category)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('livres.index', ['category_id' => $category->id]) }}" class="text-decoration-none text-reset">
                    <div class="category-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold">{{ $category->nom }}</h4>
                                <p class="text-muted mb-0">{{ $category->livres_count }} livres</p>
                            </div>
                            <span class="btn btn-accent rounded-circle">&rarr;</span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info rounded-4">Les categories seront bientot ajoutees.</div></div>
        @endforelse
    </div>
</section>

<section class="container mt-5">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <span class="text-primary fw-bold">Nouveautes</span>
            <h2 class="section-title mb-0">Derniers livres</h2>
        </div>
    </div>
    <div class="row g-4">
        @foreach($latestLivres as $livre)
            <div class="col-md-6 col-xl-3">
                <div class="book-card h-100 p-3 fade-up">
                    <img class="book-cover mb-3" src="{{ $livre->image ? asset('images/livres/'.$livre->image) : asset('images/slider/book3.jpg') }}" alt="{{ $livre->titre }}">
                    <span class="badge badge-status {{ $livre->statut === 'Disponible' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $livre->statut }}</span>
                    <h5 class="fw-bold mt-3">{{ $livre->titre }}</h5>
                    <p class="text-muted">{{ $livre->auteur }}</p>
                    <a href="{{ route('livres.show', $livre) }}" class="btn btn-outline-primary rounded-pill w-100">Voir details</a>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
