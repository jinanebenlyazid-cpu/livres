<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Livre;
use App\Services\BorrowingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EmpruntController extends Controller
{
    public function index(Request $request): View
    {
        $emprunts = $request->user()
            ->emprunts()
            ->with('livre.category')
            ->latest()
            ->paginate(10);

        return view('mesEmprunts', compact('emprunts'));
    }

    public function store(Request $request, Livre $livre): RedirectResponse
    {
        if ($request->user()->isAdmin()) {
            return back()->with('error', 'Les administrateurs gerent les emprunts depuis Filament.');
        }

        $alreadyBorrowed = Emprunt::where('user_id', $request->user()->id)
            ->where('livre_id', $livre->id)
            ->where('statut', Emprunt::STATUT_EN_COURS)
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'Vous avez deja un emprunt en cours pour ce livre.');
        }

        try {
            app(BorrowingService::class)->borrow($request->user(), $livre);
        } catch (ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        }

        return back()->with('success', 'Livre emprunte avec succes.');
    }

    public function destroy(Request $request, Emprunt $emprunt): RedirectResponse
    {
        abort_unless($emprunt->user_id === $request->user()->id, 403);

        app(BorrowingService::class)->delete($emprunt);

        return back()->with('success', 'Emprunt supprime.');
    }

    public function returnBook(Request $request, Emprunt $emprunt): RedirectResponse
    {
        abort_unless($emprunt->user_id === $request->user()->id, 403);

        if ($emprunt->statut !== Emprunt::STATUT_EN_COURS) {
            return back()->with('error', 'Cet emprunt est deja cloture.');
        }

        app(BorrowingService::class)->returnBook($emprunt);

        return back()->with('success', 'Livre retourne. Merci !');
    }
}
