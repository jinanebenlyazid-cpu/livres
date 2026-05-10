<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LivreController extends Controller
{
    public function home(): View
    {
        return view('Home', [
            'popularLivres' => Livre::with('category')->withCount('emprunts')->orderByDesc('emprunts_count')->take(8)->get(),
            'latestLivres' => Livre::with('category')->latest()->take(8)->get(),
            'categories' => Category::withCount('livres')->take(6)->get(),
        ]);
    }

    public function index(Request $request): View
    {
        $livres = Livre::with('category')
            ->when($request->filled('titre'), fn ($query) => $query->where('titre', 'like', '%'.$request->titre.'%'))
            ->when($request->filled('auteur'), fn ($query) => $query->where('auteur', 'like', '%'.$request->auteur.'%'))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->category_id))
            ->when($request->filled('statut'), fn ($query) => $query->where('statut', $request->statut))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('listeLivre', [
            'livres' => $livres,
            'categories' => Category::orderBy('nom')->get(),
        ]);
    }

    public function show(Livre $livre): View
    {
        return view('detailLivre', [
            'livre' => $livre->load('category'),
        ]);
    }
}
