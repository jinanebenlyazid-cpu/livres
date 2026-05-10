@extends('layouts.app')

@section('title', $livre->titre.' - BiblioTech Cloud')

@section('content')
<section class="container">
    <div class="row g-5 align-items-start">
        <div class="col-lg-5">
            <div class="book-card p-3">
                <img class="book-cover" src="{{ $livre->image ? asset('images/livres/'.$livre->image) : asset('images/slider/book2.jpg') }}" alt="{{ $livre->titre }}">
            </div>
        </div>
        <div class="col-lg-7">
            <a href="{{ route('livres.index') }}" class="text-decoration-none">&larr; Retour au catalogue</a>
            <div class="mt-4">
                <span class="badge text-bg-light text-primary mb-3">{{ $livre->category?->nom }}</span>
                <h1 class="section-title">{{ $livre->titre }}</h1>
                <p class="lead text-muted">Par {{ $livre->auteur }}</p>
                <div class="row g-3 my-4">
                    <div class="col-sm-4">
                        <div class="stat-card p-3">
                            <span class="d-block small text-muted">ISBN</span>
                            <strong>{{ $livre->isbn }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="stat-card p-3">
                            <span class="d-block small text-muted">Exemplaires</span>
                            <strong>{{ $livre->nombre_exemplaires }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="stat-card p-3">
                            <span class="d-block small text-muted">Statut</span>
                            <strong class="{{ $livre->statut === 'Disponible' ? 'text-success' : 'text-secondary' }}">{{ $livre->statut }}</strong>
                        </div>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->role === 'membre')
                        <form method="POST" action="{{ route('emprunts.store', $livre) }}">
                            @csrf
                            <button class="btn btn-accent btn-lg rounded-pill px-5" type="submit" @disabled(! $livre->isAvailable())>
                                Emprunter ce livre
                            </button>
                        </form>
                    @else
                        <a href="/admin" class="btn btn-primary btn-lg rounded-pill px-5">Gerer depuis Filament</a>
                    @endif
                @else
                    <div class="alert alert-warning rounded-4">
                        Connectez-vous comme membre pour emprunter ce livre.
                        <a href="{{ route('login') }}" class="fw-bold">Login</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection
