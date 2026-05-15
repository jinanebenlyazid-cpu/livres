@extends('layouts.app')

@section('title', 'Profil - BiblioTech')

@section('content')
<section class="bt-page-section">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="book-card p-4 p-lg-5">
                <span class="bt-label">Compte</span>
                <h1 class="section-title mb-4">Profil</h1>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="stat-card p-3">
                            <span class="small text-muted d-block">Nom</span>
                            <strong>{{ $user->name }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card p-3">
                            <span class="small text-muted d-block">Email</span>
                            <strong>{{ $user->email }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card p-3">
                            <span class="small text-muted d-block">Rôle</span>
                            <strong>{{ $user->role }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card p-3">
                            <span class="small text-muted d-block">Membre depuis</span>
                            <strong>{{ $user->created_at?->format('d/m/Y') }}</strong>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('emprunts.index') }}" class="btn btn-primary rounded-pill px-4">Voir mes emprunts</a>
                    @if($user->isAdmin())
                        <a href="/admin" class="btn btn-outline-primary rounded-pill px-4">Admin</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection
