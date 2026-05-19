@extends('layouts.app')

@section('title', 'Ajouter un emprunt - BiblioTech')

@section('content')
<section class="bt-page-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="book-card p-4 p-lg-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-end mb-4">
                        <div>
                            <span class="bt-label">Emprunts</span>
                            <h1 class="section-title mb-0">Ajouter un emprunt</h1>
                        </div>

                        <a href="{{ route('emprunts.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                            Mes emprunts
                        </a>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Veuillez corriger les erreurs du formulaire.</strong>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('emprunts.store') }}" class="bt-form">
                        @csrf

                        <div class="row g-4">
                            @if(auth()->user()->isAdmin())
                                <div class="col-12">
                                    <label for="user_id" class="form-label">Membre</label>
                                    <select
                                        id="user_id"
                                        name="user_id"
                                        class="form-select rounded-pill @error('user_id') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">Choisir un membre</option>
                                        @foreach($membres as $membre)
                                            <option value="{{ $membre->id }}" @selected(old('user_id') == $membre->id)>
                                                {{ $membre->name }} - {{ $membre->email }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="col-12">
                                <label for="livre_id" class="form-label">Livre</label>
                                <select
                                    id="livre_id"
                                    name="livre_id"
                                    class="form-select rounded-pill @error('livre_id') is-invalid @enderror"
                                    required
                                >
                                    <option value="">Choisir un livre</option>
                                    @foreach($livres as $livre)
                                        <option value="{{ $livre->id }}" @selected(old('livre_id', request('livre_id')) == $livre->id)>
                                            {{ $livre->titre }} - {{ $livre->auteur }} ({{ $livre->nombre_exemplaires }})
                                        </option>
                                    @endforeach
                                </select>

                                @error('livre_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date_emprunt" class="form-label">Date d'emprunt</label>
                                <input
                                    type="date"
                                    id="date_emprunt"
                                    name="date_emprunt"
                                    value="{{ old('date_emprunt', now()->toDateString()) }}"
                                    max="{{ now()->toDateString() }}"
                                    class="form-control rounded-pill @error('date_emprunt') is-invalid @enderror"
                                    required
                                >

                                @error('date_emprunt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date_retour_prevue" class="form-label">Date de retour prévue</label>
                                <input
                                    type="date"
                                    id="date_retour_prevue"
                                    name="date_retour_prevue"
                                    value="{{ old('date_retour_prevue', now()->addDays(14)->toDateString()) }}"
                                    min="{{ old('date_emprunt', now()->toDateString()) }}"
                                    class="form-control rounded-pill @error('date_retour_prevue') is-invalid @enderror"
                                    required
                                >

                                @error('date_retour_prevue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button class="btn btn-accent rounded-pill w-100 py-3" type="submit">
                                    Enregistrer l'emprunt
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
