@extends('layouts.app')

@section('title', 'Mes emprunts - BiblioTech Cloud')

@section('content')
<section class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-end mb-4">
        <div>
            <span class="text-primary fw-bold">Espace membre</span>
            <h1 class="section-title mb-0">Mes emprunts</h1>
        </div>
        <a href="{{ route('livres.index') }}" class="btn btn-primary rounded-pill">Emprunter un livre</a>
    </div>

    <div class="table-responsive book-card p-2">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Livre</th>
                    <th>Date emprunt</th>
                    <th>Retour prevu</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($emprunts as $emprunt)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $emprunt->livre->image ? asset('images/livres/'.$emprunt->livre->image) : asset('images/slider/book2.jpg') }}" alt="{{ $emprunt->livre->titre }}" style="width:56px;height:72px;object-fit:cover;border-radius:12px;">
                                <div>
                                    <strong>{{ $emprunt->livre->titre }}</strong>
                                    <div class="small text-muted">{{ $emprunt->livre->auteur }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                        <td>{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                        <td><span class="badge badge-status {{ $emprunt->statut === 'En cours' ? 'text-bg-primary' : 'text-bg-success' }}">{{ $emprunt->statut }}</span></td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                @if($emprunt->statut === 'En cours')
                                    <form method="POST" action="{{ route('emprunts.return', $emprunt) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-accent rounded-pill" data-confirm="Confirmer le retour de ce livre ?">Retourner</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('emprunts.destroy', $emprunt) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-pill" data-confirm="Supprimer cet emprunt ?">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">Vous n'avez aucun emprunt pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $emprunts->links() }}
    </div>
</section>
@endsection
