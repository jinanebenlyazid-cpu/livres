@extends('layouts.app')

@section('title', 'Livres - BiblioTech')

@section('content')
<section class="bt-page-section">
    <div class="container">
    <div class="row align-items-center g-4 mb-4">
        <div class="col-lg-7">
            <span class="bt-label">Catalogue</span>
            <h1 class="section-title">Tous les livres</h1>
            <p class="text-muted">Recherchez par titre, auteur, catégorie ou disponibilité.</p>
        </div>
        <div class="col-lg-5">
            <div class="stat-card p-4">
                <strong>{{ $livres->total() }}</strong>
                <span class="text-muted">résultats trouvés</span>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('livres.index') }}" class="filter-panel p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-6 col-xl-3">
                <label class="form-label">Titre</label>
                <input type="text" name="titre" value="{{ request('titre') }}" class="form-control rounded-pill" placeholder="Atomic Habits">
            </div>
            <div class="col-md-6 col-xl-3">
                <label class="form-label">Auteur</label>
                <input type="text" name="auteur" value="{{ request('auteur') }}" class="form-control rounded-pill" placeholder="James Clear">
            </div>
            <div class="col-md-6 col-xl-2">
                <label class="form-label">Catégorie</label>
                <select name="category_id" class="form-select rounded-pill">
                    <option value="">Toutes</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-xl-2">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select rounded-pill">
                    <option value="">Tous</option>
                    <option value="Disponible" @selected(request('statut') === 'Disponible')>Disponible</option>
                    <option value="Indisponible" @selected(request('statut') === 'Indisponible')>Indisponible</option>
                </select>
            </div>
            <div class="col-xl-2 d-flex align-items-end gap-2">
                <button class="btn btn-primary rounded-pill w-100" type="submit">Filtrer</button>
                <a href="{{ route('livres.index') }}" class="btn btn-outline-secondary rounded-pill">Reset</a>
            </div>
        </div>
    </form>

    <div class="row g-4">
        @forelse($livres as $livre)
            <div class="col-md-6 col-xl-4">
                <div class="book-card h-100 p-3 fade-up">
                    <img class="book-cover mb-3" src="{{ $livre->image ? asset('images/livres/'.$livre->image) : asset('images/slider/book2.jpg') }}" alt="{{ $livre->titre }}">
                    <div class="d-flex justify-content-between gap-2 mb-2">
                        <span class="badge text-bg-light text-primary">{{ $livre->category?->nom }}</span>
                        <span class="badge badge-status {{ $livre->statut === 'Disponible' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $livre->statut }}</span>
                    </div>
                    <h4 class="fw-bold">{{ $livre->titre }}</h4>
                    <p class="text-muted mb-1">{{ $livre->auteur }}</p>
                    <p class="small text-muted">Exemplaires: {{ $livre->nombre_exemplaires }}</p>
                    <a href="{{ route('livres.show', $livre) }}" class="btn btn-outline-primary rounded-pill w-100">Voir détails</a>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info rounded-4">Aucun livre ne correspond à votre recherche.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $livres->links() }}
    </div>
    </div>
</section>
@endsection
