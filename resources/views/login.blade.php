@extends('layouts.app')

@section('title', 'Connexion - BiblioTech')

@section('content')
<section class="bt-page-section">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="book-card p-4 p-lg-5">
                <span class="bt-label">Bienvenue</span>
                <h1 class="section-title mb-4">Connexion</h1>

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-pill @error('email') is-invalid @enderror" required autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control rounded-pill @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Se souvenir de moi</label>
                    </div>
                    <button class="btn btn-primary rounded-pill w-100 py-2" type="submit">Se connecter</button>
                </form>

                <p class="text-muted mt-4 mb-0">Pas encore membre ? <a href="{{ route('register') }}">Créer un compte</a></p>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection
