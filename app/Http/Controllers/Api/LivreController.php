<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $livres = Livre::with('category')
            ->when($request->filled('titre'), fn ($query) => $query->where('titre', 'like', '%'.$request->titre.'%'))
            ->when($request->filled('auteur'), fn ($query) => $query->where('auteur', 'like', '%'.$request->auteur.'%'))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->category_id))
            ->when($request->filled('statut'), fn ($query) => $query->where('statut', $request->statut))
            ->latest()
            ->paginate(12);

        return response()->json($livres);
    }
}
