@extends('layouts.app')

@section('title', 'Profile - BiblioTech Cloud')

@section('content')
<section class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="book-card p-4 p-lg-5">
                <span class="text-primary fw-bold">Compte</span>
                <h1 class="section-title mb-4">Profile</h1>
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
                            <span class="small text-muted d-block">Role</span>
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
                <a href="{{ route('emprunts.index') }}" class="btn btn-primary rounded-pill mt-4">Voir mes emprunts</a>
            </div>
        </div>
    </div>
</section>
@endsection
