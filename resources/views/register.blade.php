@extends('layouts.app')

@section('title', 'Inscription - BiblioTech')

@section('content')
<section class="bt-page-section">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="book-card p-4 p-lg-5">
                <span class="bt-label">Espace membre</span>
                <h1 class="section-title mb-4">Inscription</h1>

                <form method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control rounded-pill @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-pill @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control rounded-pill @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-pill" required>
                    </div>
                    <button class="btn btn-accent rounded-pill w-100 py-2" type="submit">Créer mon compte</button>
                </form>

                <p class="text-muted mt-4 mb-0">Déjà inscrit ? <a href="{{ route('login') }}">Connexion</a></p>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection
