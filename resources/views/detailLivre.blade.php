@extends('layouts.app')

@section('title', $livre->titre.' - BiblioTech')

@section('content')
<section class="bt-page-section">
    <div class="container">
        @php
            $description = "Découvrez {$livre->titre}, un ouvrage signé {$livre->auteur}. Ce livre s'inscrit dans la catégorie ".($livre->category?->nom ?? 'générale')." et propose une lecture agréable pour enrichir votre bibliothèque personnelle.";
        @endphp

        <div class="row g-5 align-items-start">
            <div class="col-lg-5">
                <div class="book-card p-3 bt-detail-cover-card">
                    <img class="book-cover" src="{{ $livre->image ? asset('images/livres/'.$livre->image) : asset('images/slider/book2.jpg') }}" alt="{{ $livre->titre }}">
                </div>
            </div>

            <div class="col-lg-7">
                <a href="{{ route('livres.index') }}" class="bt-back-link">&larr; Retour au catalogue</a>

                <div class="mt-4">
                    <span class="badge text-bg-light text-primary mb-3">{{ $livre->category?->nom }}</span>
                    <h1 class="section-title">{{ $livre->titre }}</h1>
                    <p class="lead text-muted">Par {{ $livre->auteur }}</p>

                    <div class="bt-book-summary mt-4">
                        <span class="bt-label">Résumé du livre</span>
                        <h2>Une présentation rapide</h2>
                        <p>{{ $description }}</p>

                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="bt-summary-item">
                                    <span>Auteur</span>
                                    <strong>{{ $livre->auteur }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bt-summary-item">
                                    <span>Catégorie</span>
                                    <strong>{{ $livre->category?->nom ?? 'Non classé' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bt-summary-item">
                                    <span>Statut</span>
                                    <strong class="{{ $livre->statut === 'Disponible' ? 'text-success' : 'text-secondary' }}">{{ $livre->statut }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row  g-3 my-4">
                        <div class="col-sm-6">
                            <div class="stat-card text-center p-3">
                                <span class="d-block small text-muted">Exemplaires</span>
                                <strong>{{ $livre->nombre_exemplaires }}</strong>
                            </div>
                        </div>
                    
                    </div>

                    @auth
                        @if(auth()->user()->role === 'membre')
                            <form method="POST" action="{{ route('emprunts.borrow', $livre) }}">
                                @csrf
                                <button class="btn btn-accent btn-lg  rounded-pill px-5" type="submit" @disabled(! $livre->isAvailable())>
                                    Emprunter ce livre
                                </button>
                            </form>
                        @else
                            <div class="d-flex  flex-wrap gap-2">
                                <a href="{{ route('emprunts.create', ['livre_id' => $livre->id]) }}" class="btn btn-accent btn-lg rounded-pill px-5">
                                    Ajouter un emprunt
                                </a>
                                <a href="/admin" class="btn btn-outline-primary btn-lg rounded-pill px-5">Admin</a>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning rounded-4">
                            Connectez-vous comme membre pour emprunter ce livre.
                            <a href="{{ route('login') }}" class="fw-bold">Connexion</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
